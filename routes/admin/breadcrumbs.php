<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

// ---------------Blogs---------------
Breadcrumbs::for('admin.blogs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Blogs', route('admin.blogs.index'));
});
Breadcrumbs::for('admin.blogs.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.blogs.index');
    $trail->push('Add Blog', route('admin.blogs.create'));
});
Breadcrumbs::for('admin.blogs.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.blogs.index');
    $trail->push($item->title ?? 'N/A', route('admin.blogs.edit', $item->id));
});

Breadcrumbs::for('admin.blogs-categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.blogs.index');
    $trail->push('Categories', route('admin.blogs-categories.index'));
});
Breadcrumbs::for('admin.blogs-categories.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.blogs-categories.index');
    $trail->push($item->name ?? 'N/A', route('admin.blogs-categories.edit', $item->id));
});
Breadcrumbs::for('admin.blogs-tags.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.blogs.index');
    $trail->push('Tags', route('admin.blogs-tags.index'));
});
Breadcrumbs::for('admin.blogs-tags.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.blogs-tags.index');
    $trail->push($item->name ?? 'N/A', route('admin.blogs-tags.edit', $item->id));
});
// ---------------Blogs---------------

Breadcrumbs::for('admin.recovery.index', function (BreadcrumbTrail $trail, $resource) {
    $trail->parent("admin.$resource.index");
    $trail->push('Recovery', route('admin.recovery.index', ['resource' => $resource]));
});

// ---------------News---------------
Breadcrumbs::for('admin.news.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('News', route('admin.news.index'));
});
Breadcrumbs::for('admin.news.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.news.index');
    $trail->push('Add News', route('admin.news.create'));
});
Breadcrumbs::for('admin.news.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.news.index');
    $trail->push($item->title ?? 'N/A', route('admin.news.edit', $item->id));
});
Breadcrumbs::for('admin.news-categories.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.news-categories.index');
    $trail->push($item->name ?? 'N/A', route('admin.news-categories.edit', $item->id));
});
Breadcrumbs::for('admin.news-categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.news.index');
    $trail->push('Categories', route('admin.news-categories.index'));
});
Breadcrumbs::for('admin.news-tags.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.news.index');
    $trail->push('Tags', route('admin.news-tags.index'));
});
Breadcrumbs::for('admin.news-tags.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.news-tags.index');
    $trail->push($item->name ?? 'N/A', route('admin.news-tags.edit', $item->id));
});
// ---------------News---------------

// ---------------countries---------------
Breadcrumbs::for('admin.countries.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Countries', route('admin.countries.index'));
});
Breadcrumbs::for('admin.countries.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.countries.index');
    $trail->push('Add Country', route('admin.countries.create'));
});
Breadcrumbs::for('admin.countries.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.countries.index');
    $trail->push($item->name ?? 'N/A', route('admin.countries.edit', $item->id));
});
// ---------------countries---------------

// ---------------cities---------------
Breadcrumbs::for('admin.cities.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Cities', route('admin.cities.index'));
});
Breadcrumbs::for('admin.cities.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.cities.index');
    $trail->push('Add City', route('admin.cities.create'));
});
Breadcrumbs::for('admin.cities.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.cities.index');
    $trail->push($item->name ?? 'N/A', route('admin.cities.edit', $item->id));
});
// ---------------cities---------------

// ---------------Tours---------------

Breadcrumbs::for('admin.tours.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Tours', route('admin.tours.index'));
});
Breadcrumbs::for('admin.tours.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Add Tour', route('admin.tours.create'));
});
Breadcrumbs::for('admin.tours.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tours.index');
    $trail->push($item->title ?? 'N/A', route('admin.tours.edit', $item->id));
});

Breadcrumbs::for('admin.tour-categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Categories', route('admin.tour-categories.index'));
});
Breadcrumbs::for('admin.tour-categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tour-categories.index');
    $trail->push('Add Category', route('admin.tour-categories.create'));
});
Breadcrumbs::for('admin.tour-categories.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tour-categories.index');
    $trail->push($item->name ?? 'N/A', route('admin.tour-categories.edit', $item->id));
});

Breadcrumbs::for('admin.tour-authors.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Authors', route('admin.tour-authors.index'));
});
Breadcrumbs::for('admin.tour-authors.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tour-authors.index');
    $trail->push('Add Author', route('admin.tour-authors.create'));
});
Breadcrumbs::for('admin.tour-authors.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tour-authors.index');
    $trail->push($item->name ?? 'N/A', route('admin.tour-authors.edit', $item->id));
});
Breadcrumbs::for('admin.tour-popups.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Detail Popups', route('admin.tour-popups.index'));
});
Breadcrumbs::for('admin.tour-popups.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tour-popups.index');
    $trail->push('Add Detail Popup', route('admin.tour-popups.create'));
});
Breadcrumbs::for('admin.tour-popups.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tour-popups.index');
    $trail->push($item->main_heading ?? 'N/A', route('admin.tour-popups.edit', $item->id));
});

Breadcrumbs::for('admin.tour-attributes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Attributes', route('admin.tour-attributes.index'));
});
Breadcrumbs::for('admin.tour-attributes.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tour-attributes.index');
    $trail->push($item->name ?? 'N/A', route('admin.tour-attributes.edit', $item->id));
});
Breadcrumbs::for('admin.tour-reviews.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Reviews', route('admin.tour-reviews.index'));
});
Breadcrumbs::for('admin.tour-reviews.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.tour-reviews.index');
    $trail->push($item->title ?? 'N/A', route('admin.tour-reviews.edit', $item->id));
});
Breadcrumbs::for('admin.tour-availability.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Availability', route('admin.tour-availability.index'));
});
Breadcrumbs::for('admin.tour-bookings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Booking', route('admin.tour-bookings.index'));
});
// ---------------Tours---------------

// ---------------Pages---------------
Breadcrumbs::for('admin.pages.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Pages', route('admin.pages.index'));
});

Breadcrumbs::for('admin.pages.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.pages.index');
    $trail->push('Add Page', route('admin.pages.create'));
});

Breadcrumbs::for('admin.pages.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.pages.index');
    $trail->push($item->title ?? 'N/A', route('admin.pages.edit', $item->id));
});

Breadcrumbs::for('admin.pages.page-builder', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.pages.edit', $item);
    $trail->push('Edit Template', route('admin.pages.page-builder', $item->id));
});
// ---------------Pages---------------

// ---------------Sections---------------
Breadcrumbs::for('admin.sections.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Sections', route('admin.sections.index'));
});

Breadcrumbs::for('admin.sections.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.sections.index');
    $trail->push('Add Section', route('admin.sections.create'));
});

Breadcrumbs::for('admin.sections.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.sections.index');
    $trail->push($item->name ?? 'N/A', route('admin.sections.edit', $item->id));
});
// ---------------Sections---------------

// ---------------Testimonials---------------
Breadcrumbs::for('admin.testimonials.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Testimonials', route('admin.testimonials.index'));
});
Breadcrumbs::for('admin.testimonials.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.testimonials.index');
    $trail->push('Add Testimonial', route('admin.testimonials.create'));
});
Breadcrumbs::for('admin.testimonials.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.testimonials.index');
    $trail->push($item->title ?? 'N/A', route('admin.testimonials.edit', $item->id));
});
// ---------------Testimonials---------------

// ---------------Popups---------------
Breadcrumbs::for('admin.popups.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Popups', route('admin.popups.index'));
});
Breadcrumbs::for('admin.popups.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.popups.index');
    $trail->push('Add Popup', route('admin.popups.create'));
});
Breadcrumbs::for('admin.popups.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.popups.index');
    $trail->push($item->title ?? 'N/A', route('admin.popups.edit', $item->id));
});
// ---------------Popups---------------

// ---------------Coupons---------------
Breadcrumbs::for('admin.coupons.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Coupons', route('admin.coupons.index'));
});
Breadcrumbs::for('admin.coupons.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.coupons.index');
    $trail->push('Add Coupon', route('admin.coupons.create'));
});
Breadcrumbs::for('admin.coupons.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.coupons.index');
    $trail->push($item->name ?? 'N/A', route('admin.coupons.edit', $item->id));
});
// ---------------Coupons---------------

// ---------------settings---------------
Breadcrumbs::for('admin.settings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Settings', route('admin.settings.edit', ['resource' => 'general']));
});
// ---------------settings---------------

Breadcrumbs::for('admin.bookings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Bookings', route('admin.bookings.index'));
});
Breadcrumbs::for('admin.bookings.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.bookings.index');
    $trail->push('Booking Details', route('admin.bookings.edit', $item->id));
});

Breadcrumbs::for('admin.inquiries.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.tours.index');
    $trail->push('Inquiries', route('admin.inquiries.index'));
});
Breadcrumbs::for('admin.inquiries.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.inquiries.index');
    $trail->push('Inquiry Details', route('admin.inquiries.edit', $item->id));
});

Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Users', route('admin.users.index'));
});
Breadcrumbs::for('admin.users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.users.index');
    $trail->push('Add User', route('admin.users.create'));
});
Breadcrumbs::for('admin.users.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('admin.users.index');
    $trail->push('User Details', route('admin.users.edit', $item->id));
});

// ---------------Schema---------------
Breadcrumbs::for('admin.schema.index', function (BreadcrumbTrail $trail, $entity, $id, $record) {
    // Handle listing pages (saved in settings)
    if ($id === 'listing') {
        $trail->parent('admin.settings.index');
        $trail->push(ucfirst($entity) . ' Settings', route('admin.settings.edit', ['resource' => $entity]));
        $trail->push('Edit Schema', route('admin.schema.index', ['entity' => $entity, 'id' => $id]));
        return;
    }
    
    // Handle regular entity records
    $entityMap = [
        'tours' => 'admin.tours.edit',
        'pages' => 'admin.pages.edit',
        'cities' => 'admin.cities.edit',
        'countries' => 'admin.countries.edit',
        'tour-categories' => 'admin.tour-categories.edit',
        'blogs' => 'admin.blogs.edit',
        'news' => 'admin.news.edit',
    ];
    
    if (isset($entityMap[$entity]) && $record) {
        $trail->parent($entityMap[$entity], $record);
    }
    
    $trail->push('Edit Schema', route('admin.schema.index', ['entity' => $entity, 'id' => $id]));
});
// ---------------Schema---------------
