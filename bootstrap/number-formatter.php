<?php

/**
 * Register NumberFormatter polyfill if Intl extension is not available.
 * This must be loaded very early in the bootstrap process.
 */

if (!extension_loaded('intl') && !class_exists('NumberFormatter')) {
    class NumberFormatter extends \App\Support\NumberFormatterPolyfill
    {
        // This class extends the polyfill to make it available globally
    }
}
