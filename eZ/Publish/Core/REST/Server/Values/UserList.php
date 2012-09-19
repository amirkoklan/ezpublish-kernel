<?php
/**
 * File containing the UserList class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\REST\Server\Values;

/**
 * User list view model
 */
class UserList
{
    /**
     * Users
     *
     * @var \eZ\Publish\API\Repository\Values\User\User[]
     */
    public $users;

    /**
     * Path which was used to fetch the list of users
     *
     * @var string
     */
    public $path;

    /**
     * Construct
     *
     * @param \eZ\Publish\API\Repository\Values\User\User[] $users
     * @param string $path
     */
    public function __construct( array $users, $path )
    {
        $this->users = $users;
        $this->path = $path;
    }
}