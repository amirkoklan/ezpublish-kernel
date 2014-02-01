<?php
/**
 * CopySubtreeSignal class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Repository\SignalSlot\Signal\LocationService;

use eZ\Publish\Core\Repository\SignalSlot\Signal;

/**
 * CopySubtreeSignal class
 * @package eZ\Publish\Core\Repository\SignalSlot\Signal\LocationService
 */
class CopySubtreeSignal extends Signal
{
    /**
     * SubtreeId
     *
     * @var mixed
     */
    public $subtreeId;

    /**
     * TargetParentLocationId
     *
     * @var mixed
     */
    public $targetParentLocationId;

    /**
     * TargetNewSubtreeId
     *
     * @var mixed
     */
    public $targetNewSubtreeId;
}