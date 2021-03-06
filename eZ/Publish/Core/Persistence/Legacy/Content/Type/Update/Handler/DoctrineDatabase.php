<?php
/**
 * File containing the DoctrineDatabase Type Update Handler class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Legacy\Content\Type\Update\Handler;

use eZ\Publish\Core\Persistence\Legacy\Content\Type\Update\Handler;
use eZ\Publish\Core\Persistence\Legacy\Content\Type\Gateway;
use eZ\Publish\Core\Persistence\Legacy\Content\Type\ContentUpdater;
use eZ\Publish\SPI\Persistence\Content\Type;

/**
 * Doctrine database based type update handler
 */
class DoctrineDatabase extends Handler
{
    /**
     * @var \eZ\Publish\Core\Persistence\Legacy\Content\Type\Gateway
     */
    protected $contentTypeGateway;

    /**
     * Content updater
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\Content\Type\ContentUpdater
     */
    protected $contentUpdater;

    /**
     * Creates a new content type update handler
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\Content\Type\Gateway $contentTypeGateway
     * @param \eZ\Publish\Core\Persistence\Legacy\Content\Type\ContentUpdater $contentUpdater
     */
    public function __construct( Gateway $contentTypeGateway, ContentUpdater $contentUpdater )
    {
        $this->contentTypeGateway = $contentTypeGateway;
        $this->contentUpdater = $contentUpdater;
    }

    /**
     * Updates existing content objects from $fromType to $toType
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $fromType
     * @param \eZ\Publish\SPI\Persistence\Content\Type $toType
     *
     * @return void
     */
    public function updateContentObjects( Type $fromType, Type $toType )
    {
        $this->contentUpdater->applyUpdates(
            $fromType->id,
            $this->contentUpdater->determineActions( $fromType, $toType )
        );
    }

    /**
     * Deletes $fromType and all of its field definitions
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $fromType
     *
     * @return void
     */
    public function deleteOldType( Type $fromType )
    {
        $this->contentTypeGateway->delete( $fromType->id, $fromType->status );
    }

    /**
     * Publishes $toType to $newStatus
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $toType
     * @param int $newStatus
     *
     * @return void
     */
    public function publishNewType( Type $toType, $newStatus )
    {
        $this->contentTypeGateway->publishTypeAndFields(
            $toType->id,
            $toType->status,
            $newStatus
        );
    }
}
