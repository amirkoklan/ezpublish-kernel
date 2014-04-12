<?php
/**
 * File containing the Test Setup Factory base class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\SetupFactory;

use eZ\Publish\Core\Base\Container\Compiler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PDO;

/**
 * A Test Factory is used to setup the infrastructure for a tests, based on a
 * specific repository implementation to test.
 */
class LegacySolr extends Legacy
{
    /**
     * Returns a configured repository for testing.
     *
     * @param bool $initializeFromScratch
     *
     * @return \eZ\Publish\API\Repository\Repository
     */
    public function getRepository( $initializeFromScratch = true )
    {
        // Load repository first so all initialization steps are done
        $repository = parent::getRepository( $initializeFromScratch );

        if ( $initializeFromScratch )
        {
            $this->indexAll();
        }

        return $repository;
    }

    protected function getServiceContainer()
    {
        if ( !isset( self::$serviceContainer ) )
        {
            $container = parent::getServiceContainer()->getInnerContainer();
            $settings = include __DIR__ . "/../../../../../../config.php";
            $installDir = $settings["service"]["parameters"]["install_dir"];

            $settingsPath = $installDir . "/eZ/Publish/Core/settings/";
            $loader = new YamlFileLoader( $container, new FileLocator( $settingsPath ) );
            $loader->load( 'storage_engines/legacy_solr.yml' );

            $container->addCompilerPass( new Compiler\Storage\Solr\AggregateCriterionVisitorPass() );
            $container->addCompilerPass( new Compiler\Storage\Solr\AggregateFacetBuilderVisitorPass() );
            $container->addCompilerPass( new Compiler\Storage\Solr\AggregateFieldValueMapperPass() );
            $container->addCompilerPass( new Compiler\Storage\Solr\AggregateSortClauseVisitorPass() );
            $container->addCompilerPass( new Compiler\Storage\Solr\FieldRegistryPass() );
            $container->addCompilerPass( new Compiler\Storage\Solr\SignalSlotPass() );

            $container->setAlias(
                "ezpublish.api.persistence_handler",
                "ezpublish.spi.persistence.solr"
            );
            $container->setAlias(
                "ezpublish.api.storage_engine",
                "ezpublish.spi.persistence.solr"
            );
        }

        return self::$serviceContainer;
    }

    /**
     * Indexes all Content objects.
     */
    protected function indexAll()
    {
        // @todo: Is there a nicer way to get access to all content objects? We
        // require this to run a full index here.
        /** @var \eZ\Publish\SPI\Persistence\Handler $persistenceHandler */
        $persistenceHandler = $this->getServiceContainer()->get( 'ezpublish.spi.persistence.solr' );
        $dbHandlerProperty = new \ReflectionProperty( $persistenceHandler, 'dbHandler' );
        $dbHandlerProperty->setAccessible( true );
        /** @var \eZ\Publish\Core\Persistence\Database\DatabaseHandler $db */
        $db = $dbHandlerProperty->getValue( $persistenceHandler );

        $query = $db
            ->createSelectQuery()
            ->select( 'id', 'current_version' )
            ->from( 'ezcontentobject' );

        $stmt = $query->prepare();
        $stmt->execute();

        $contentObjects = array();
        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) )
        {
            $contentObjects[] = $persistenceHandler->contentHandler()->load(
                $row['id'],
                $row['current_version']
            );
        }

        /** @var \eZ\Publish\Core\Persistence\Solr\Content\Search\Handler $searchHandler */
        $searchHandler = $persistenceHandler->searchHandler();
        $searchHandler->setCommit( false );
        $searchHandler->purgeIndex();
        $searchHandler->setCommit( true );
        $searchHandler->bulkIndexContent( $contentObjects );
    }
}
