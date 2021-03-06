<?php
/**
 * File containing the Siteaccess Compound matcher interface.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\MVC\Symfony\SiteAccess\Matcher;

use eZ\Publish\Core\MVC\Symfony\SiteAccess\Matcher;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\MatcherBuilderInterface;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\VersatileMatcher;

interface CompoundInterface extends VersatileMatcher
{
    /**
     * Injects the matcher builder, to allow the Compound matcher to properly build the underlying matchers.
     *
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess\MatcherBuilderInterface $matcherBuilder
     */
    public function setMatcherBuilder( MatcherBuilderInterface $matcherBuilder );

    /**
     * Returns all used sub-matchers.
     *
     * @return \eZ\Publish\Core\MVC\Symfony\SiteAccess\Matcher[]
     */
    public function getSubMatchers();

    /**
     * Replaces sub-matchers
     *
     * @param \eZ\Publish\Core\MVC\Symfony\SiteAccess\Matcher[] $subMatchers
     */
    public function setSubMatchers( array $subMatchers );
}
