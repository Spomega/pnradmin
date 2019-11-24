<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 9/6/19
 * Time: 5:02 AM
 */

/**
 * Breadcrumb settings for permissions.
 * User: spomega
 * Date: 9/28/18
 * Time: 10:47 PM
 */

Breadcrumbs::for('admin.auth.booking.detail', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.management'), route('admin.auth.booking.detail'));
});

Breadcrumbs::for('admin.auth.booking.view', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.management'), route('admin.auth.booking.view'));
});
Breadcrumbs::for('admin.auth.booking.pay', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.management'), route('admin.auth.booking.pay'));
});
