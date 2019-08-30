<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Attribute\PermissionAttribute;
use App\Models\Auth\Traits\Method\PermissionMethod;


/**
 * Class Permission
 *
 */


class Permission extends \Spatie\Permission\Models\Permission
{
   use PermissionAttribute, PermissionMethod;

}
