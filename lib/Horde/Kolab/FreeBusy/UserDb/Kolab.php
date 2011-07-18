<?php
/**
 * This class represents the Kolab user database behind the free/busy system.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_FreeBusy
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */

/**
 * This class represents the Kolab user database behind the free/busy system.
 *
 * Copyright 2010 Kolab Systems AG
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see
 * http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html.
 *
 * @category Kolab
 * @package  Kolab_FreeBusy
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */
class Horde_Kolab_FreeBusy_UserDb_Kolab
implements Horde_Kolab_FreeBusy_UserDb
{
    /**
     * The kolab user database connection.
     *
     * @var Horde_Kolab_Server_Composite
     */
    private $_db;

    /**
     * Constructor.
     *
     * @param Horde_Kolab_Server_Composite $db The connection to the Kolab user
     *                                         database.
     */
    public function __construct(Horde_Kolab_Server_Composite $db)
    {
        $this->_db = $db;
    }

    /**
     * Fetch a user representation from the user database.
     *
     * @param string $user The user name.
     * @param string $pass An optional user password.
     *
     * @return Horde_Kolab_FreeBusy_User The user representation.
     */
    public function getUser($user, $pass = '')
    {
        if (!empty($user)) {
            return new Horde_Kolab_FreeBusy_User_Kolab(
                $user, $this->_db, $pass
            );
        } else {
            return new Horde_Kolab_FreeBusy_User_Anonymous();
        }
    }
}
