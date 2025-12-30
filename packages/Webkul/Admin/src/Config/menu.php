<?php

/**
 * Minimal admin menu: only Products (CRUD), Shipping, Payment, and Sales Reporting
 */
return [
    // Dashboard
    [
        'key'   => 'dashboard',
        'name'  => 'admin::app.components.layouts.sidebar.dashboard',
        'route' => 'admin.dashboard.index',
        'sort'  => 1,
        'icon'  => 'icon-dashboard',
    ],

    // Catalog -> Products
    [
        'key'   => 'catalog',
        'name'  => 'admin::app.components.layouts.sidebar.catalog',
        'route' => 'admin.catalog.products.index',
        'sort'  => 2,
        'icon'  => 'icon-product',
    ], [
        'key'   => 'catalog.products',
        'name'  => 'admin::app.components.layouts.sidebar.products',
        'route' => 'admin.catalog.products.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Sales -> Orders (for reports/overview)
    [
        'key'   => 'sales',
        'name'  => 'admin::app.components.layouts.sidebar.sales',
        'route' => 'admin.sales.orders.index',
        'sort'  => 3,
        'icon'  => 'icon-sales',
    ], [
        'key'   => 'sales.orders',
        'name'  => 'admin::app.components.layouts.sidebar.orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Reporting -> Sales
    [
        'key'   => 'reporting',
        'name'  => 'admin::app.components.layouts.sidebar.reporting',
        'route' => 'admin.reporting.sales.index',
        'sort'  => 4,
        'icon'  => 'icon-report',
    ], [
        'key'   => 'reporting.sales',
        'name'  => 'admin::app.components.layouts.sidebar.sales',
        'route' => 'admin.reporting.sales.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Settings: Shipping & Payment only
    [
        'key'   => 'settings',
        'name'  => 'admin::app.components.layouts.sidebar.settings',
        'route' => 'admin.configuration.index',
        'sort'  => 5,
        'icon'  => 'icon-settings',
    ], [
        'key'   => 'settings.shipping',
        'name'  => 'admin::app.components.layouts.sidebar.shipping',
        'route' => 'admin.configuration.index',
        'route_params' => 'sales/carriers',
        'sort'  => 1,
        'icon'  => '',
    ], [
        'key'   => 'settings.payment',
        'name'  => 'admin::app.components.layouts.sidebar.payment',
        'route' => 'admin.configuration.index',
        'route_params' => 'sales/payment_methods',
        'sort'  => 2,
        'icon'  => '',
    ],
];
