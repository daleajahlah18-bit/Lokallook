<?php

return [
    /**
     * Application service providers.
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\ImageHandlerProvider::class,
    App\Providers\PerformanceOptimizationProvider::class,

    /**
     * Minimal Webkul service providers for simplified admin
     * Only register providers required for:
     * - Product CRUD
     * - Shipping & Payment configuration
     * - Sales reporting
     * - Admin & Core services
     */
    Webkul\Admin\Providers\AdminServiceProvider::class,
    Webkul\Core\Providers\CoreServiceProvider::class,
    Webkul\Shop\Providers\ShopServiceProvider::class,
    Webkul\DataGrid\Providers\DataGridServiceProvider::class,
    Webkul\Product\Providers\ProductServiceProvider::class,
    Webkul\Shipping\Providers\ShippingServiceProvider::class,
    Webkul\Payment\Providers\PaymentServiceProvider::class,
    Webkul\Sales\Providers\SalesServiceProvider::class,
    Webkul\User\Providers\UserServiceProvider::class,
    Webkul\Theme\Providers\ThemeServiceProvider::class,
];
