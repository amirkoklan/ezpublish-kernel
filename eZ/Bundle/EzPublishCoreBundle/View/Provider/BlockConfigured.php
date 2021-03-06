<?php
/**
 * File containing the BlockConfigured class.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishCoreBundle\View\Provider;

use eZ\Publish\Core\MVC\Symfony\SiteAccess\SiteAccessAware;
use eZ\Publish\Core\MVC\Symfony\View\Provider\Block\Configured;
use eZ\Publish\Core\MVC\Symfony\SiteAccess;

class BlockConfigured extends Configured implements SiteAccessAware
{
    /**
     * Changes SiteAccess.
     *
     * @param SiteAccess $siteAccess
     */
    public function setSiteAccess( SiteAccess $siteAccess = null )
    {
        if ( $this->matcherFactory instanceof SiteAccessAware )
        {
            $this->matcherFactory->setSiteAccess( $siteAccess );
        }
    }
}
