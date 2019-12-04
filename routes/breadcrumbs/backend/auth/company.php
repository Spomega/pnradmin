<?php
/**
 * Breadcrumb settings for permissions.
 * User: spomega
 * Date: 9/28/19
 * Time: 10:47 PM
 */

Breadcrumbs::for('admin.auth.company.index', function($trail){
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.access.company.management'), route('admin.auth.company.index'));
});


Breadcrumbs::for('admin.auth.company.create', function($trail){
    $trail->parent('admin.auth.company.index');
    $trail->push(__('menus.backend.access.company.create'), route('admin.auth.permission.create'));
});

Breadcrumbs::for('admin.auth.company.edit', function ($trail, $id) {
    $trail->parent('admin.auth.permission.index');
    $trail->push(__('menus.backend.access.permissions.edit'), route('admin.auth.permission.edit', $id));
});
