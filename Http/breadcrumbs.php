<?php

Breadcrumbs::register('user-admin', function($breadcrumbs) {
    $breadcrumbs->parent('admin');
    $breadcrumbs->push('Admins', url('admin/users/admins'));
});

Breadcrumbs::register('admin-notifications', function($breadcrumbs) {
    $breadcrumbs->parent('notifications');
    $breadcrumbs->push('Notifications List', url('admin/account/notifications'));
});