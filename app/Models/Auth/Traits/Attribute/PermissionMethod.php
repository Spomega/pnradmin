<?php
/**
 * Permission Trait
 * User: spomega
 * Date: 10/3/18
 * Time: 12:03 PM
 */

namespace App\Models\Auth\Traits\Method;
trait PermissionMethod {
    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->name === config('access.users.admin_role');
    }
}
