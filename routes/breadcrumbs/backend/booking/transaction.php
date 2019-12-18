<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/18/19
 * Time: 4:33 PM
 */


Breadcrumbs::for('admin.auth.transaction.index', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.transaction'), route('admin.auth.transaction.index'));
});

Breadcrumbs::for('admin.auth.transaction.filter', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.transaction'), route('admin.auth.transaction.filter'));
});

Breadcrumbs::for('admin.auth.transaction.adminindex', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.booking.transaction'), route('admin.auth.transaction.adminindex'));
});
