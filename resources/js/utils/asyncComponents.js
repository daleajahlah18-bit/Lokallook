/**
 * Vue 3 Lazy Loading & Code Splitting Utilities
 * File: resources/js/utils/asyncComponents.js
 */

import { defineAsyncComponent } from 'vue';

/**
 * Create async component dengan loading dan error states
 *
 * @param {Function} importFunc Dynamic import function
 * @param {number} delay Delay sebelum show loading (ms)
 * @param {number} timeout Timeout untuk error (ms)
 * @returns {AsyncComponentLoader}
 */
export const createAsyncComponent = (importFunc, delay = 200, timeout = 30000) => {
    return defineAsyncComponent({
        loader: importFunc,
        loadingComponent: {
            template: '<div class="skeleton-loader"></div>',
        },
        errorComponent: {
            template: '<div class="error-fallback">Error loading component</div>',
        },
        delay,
        timeout,
    });
};

/**
 * Lazy load komponen Product
 */
export const ProductCard = createAsyncComponent(
    () => import('../components/Products/ProductCard.vue')
);

export const ProductGallery = createAsyncComponent(
    () => import('../components/Products/ProductGallery.vue')
);

export const ProductReviews = createAsyncComponent(
    () => import('../components/Products/ProductReviews.vue')
);

export const RelatedProducts = createAsyncComponent(
    () => import('../components/Products/RelatedProducts.vue')
);

/**
 * Lazy load komponen Category
 */
export const CategoryBrowser = createAsyncComponent(
    () => import('../components/Categories/CategoryBrowser.vue')
);

export const CategoryFilter = createAsyncComponent(
    () => import('../components/Categories/CategoryFilter.vue')
);

/**
 * Lazy load komponen Cart & Checkout
 */
export const CartSidebar = createAsyncComponent(
    () => import('../components/Cart/CartSidebar.vue')
);

export const CheckoutForm = createAsyncComponent(
    () => import('../components/Checkout/CheckoutForm.vue')
);

export const PaymentMethod = createAsyncComponent(
    () => import('../components/Checkout/PaymentMethod.vue')
);

/**
 * Lazy load komponen Admin
 */
export const DataGrid = createAsyncComponent(
    () => import('../components/Admin/DataGrid.vue')
);

export const FormBuilder = createAsyncComponent(
    () => import('../components/Admin/FormBuilder.vue')
);

export const ChartComponent = createAsyncComponent(
    () => import('../components/Admin/ChartComponent.vue')
);

/**
 * Intersection Observer untuk lazy loading images
 */
export const setupImageLazyLoad = () => {
    if (!('IntersectionObserver' in window)) {
        // Fallback untuk browser yang tidak support IntersectionObserver
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.src = img.dataset.src;
        });
        return;
    }

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
};

/**
 * Preload critical components
 */
export const preloadCriticalComponents = async () => {
    try {
        await Promise.all([
            import('../components/Products/ProductCard.vue'),
            import('../components/Cart/CartSidebar.vue'),
        ]);
    } catch (error) {
        console.error('Error preloading critical components:', error);
    }
};
