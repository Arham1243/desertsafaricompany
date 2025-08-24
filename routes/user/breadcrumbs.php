<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('user.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('user.dashboard'));
});

Breadcrumbs::for('user.recovery.index', function (BreadcrumbTrail $trail, $resource) {
    $trail->parent("user.$resource.index");
    $trail->push('Recovery', route('user.recovery.index', ['resource' => $resource]));
});

Breadcrumbs::for('user.profile.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Personal Information', route('user.profile.index'));
});

Breadcrumbs::for('user.profile.changePassword', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Change Password', route('user.profile.changePassword'));
});

Breadcrumbs::for('user.bookings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Bookings', route('user.bookings.index'));
});

Breadcrumbs::for('user.bookings.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('user.bookings.index');
    $trail->push('Booking Details', route('user.bookings.edit', $item->id));
});

Breadcrumbs::for('user.bookings.pay', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('user.bookings.edit', $item); // pass the model, not $item->id
    $trail->push('Booking Payment', route('user.bookings.pay', $item->id));
});
