<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class ImageHandlerProvider extends ServiceProvider
{
    public function register()
    {
        // Override the intervention image manager to handle missing GD gracefully
        $this->app->extend('image', function ($manager) {
            return new class($manager) {
                private $originalManager;

                public function __construct($manager)
                {
                    $this->originalManager = $manager;
                }

                public function __call($method, $arguments)
                {
                    try {
                        return call_user_func_array([$this->originalManager, $method], $arguments);
                    } catch (\Exception $e) {
                        // If it's a GD Library error, return a stub object
                        if (str_contains($e->getMessage(), 'GD Library')) {
                            return new ImageStub();
                        }
                        throw $e;
                    }
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}

/**
 * Stub image class for when GD Library is not available.
 * Allows the application to continue without crashing on image operations.
 */
class ImageStub
{
    public function __call($method, $arguments)
    {
        return $this;
    }

    public function save($path = null, $quality = 90, $format = null)
    {
        // Don't actually save, just return self for chaining
        return $this;
    }

    public function encode($format = null, $quality = 90)
    {
        // Return self for chaining
        return $this;
    }

    public function __toString()
    {
        return '';
    }
}
