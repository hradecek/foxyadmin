<?php

namespace Foxytouch\User\Repositories\Contracts;

use Foxytouch\Repositories\Contracts\BaseRepository;

/**
 * User repository contract.
 *
 * @package Foxytouch\User\Repositories
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
interface UserRepository extends BaseRepository
{
    /**
     * Called after successful <b>login</b>.
     *
     * Following actions will be performed:
     * <ul>
     *   <li>login count will be increment by 1,</li>
     *   <li>last login date will be updated by current date,</li>
     *   <li>ip address will be persisted,</li>
     *   <li>login flag will be set to true.</li>
     * </ul>
     */
    function updateAfterLogin();

    /**
     * Called after successful <b>logout</b>.
     *
     * Following actions will be performed:
     * <ul>
     *   <li>login flag will be set to false.</li>
     * </ul>
     */
    function updateAfterLogout();
}
