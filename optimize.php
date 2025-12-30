#!/usr/bin/env php
<?php
/**
 * Performance Optimization Checklist & Validator
 * Run: php optimize.php
 */

class PerformanceChecker
{
    private $projectPath;
    private $checks = [];
    private $warnings = [];
    private $errors = [];

    public function __construct($projectPath = '.')
    {
        $this->projectPath = rtrim($projectPath, '/');
    }

    public function run()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║     LOKALLOOK PERFORMANCE OPTIMIZATION CHECKER v1.0           ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        $this->checkConfiguration();
        $this->checkFiles();
        $this->checkEnvironment();
        $this->checkDependencies();

        $this->displayResults();
        $this->generateReport();
    }

    private function checkConfiguration()
    {
        echo "🔍 Checking Configuration...\n";

        // Check if config/performance.php exists
        if (file_exists($this->projectPath . '/config/performance.php')) {
            $this->addCheck('✅ Performance Config', 'config/performance.php exists');
        } else {
            $this->addError('❌ Performance Config', 'config/performance.php not found');
        }

        // Check if provider is registered
        $providersFile = $this->projectPath . '/bootstrap/providers.php';
        if (file_exists($providersFile)) {
            $content = file_get_contents($providersFile);
            if (str_contains($content, 'PerformanceOptimizationProvider')) {
                $this->addCheck('✅ Provider Registered', 'PerformanceOptimizationProvider registered');
            } else {
                $this->addWarning('⚠️  Provider Not Registered', 'Add to bootstrap/providers.php');
            }
        }

        // Check .env
        $envFile = $this->projectPath . '/.env';
        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);
            
            if (str_contains($content, 'CACHE_STORE')) {
                $this->addCheck('✅ Cache Driver Configured', 'CACHE_STORE is set');
            } else {
                $this->addWarning('⚠️  Cache Not Configured', 'Add CACHE_STORE to .env');
            }

            if (str_contains($content, 'RESPONSE_CACHE_ENABLED')) {
                $this->addCheck('✅ Response Cache Setting', 'RESPONSE_CACHE_ENABLED is set');
            } else {
                $this->addWarning('⚠️  Response Cache Not Set', 'Add to .env');
            }
        }
    }

    private function checkFiles()
    {
        echo "📁 Checking Files...\n";

        $requiredFiles = [
            'app/Providers/PerformanceOptimizationProvider.php',
            'app/Helpers/PerformanceHelper.php',
            'app/Http/Middleware/SetHttpCacheHeaders.php',
            'resources/js/utils/asyncComponents.js',
            'config/performance.php',
        ];

        foreach ($requiredFiles as $file) {
            $fullPath = $this->projectPath . '/' . $file;
            if (file_exists($fullPath)) {
                $this->addCheck('✅ ' . basename($file), "File exists ($file)");
            } else {
                $this->addWarning('⚠️  ' . basename($file), "Missing ($file)");
            }
        }

        // Check Vite config modifications
        $viteShop = $this->projectPath . '/packages/Webkul/Shop/vite.config.js';
        if (file_exists($viteShop)) {
            $content = file_get_contents($viteShop);
            if (str_contains($content, 'cssCodeSplit') && str_contains($content, 'manualChunks')) {
                $this->addCheck('✅ Vite Shop Config', 'Code splitting configured');
            } else {
                $this->addWarning('⚠️  Vite Shop Config', 'May need code splitting setup');
            }
        }
    }

    private function checkEnvironment()
    {
        echo "🌍 Checking Environment...\n";

        // Check if we're in development or production
        $env = getenv('APP_ENV') ?: 'unknown';
        $this->addCheck('ℹ️  Environment', "Running in $env mode");

        // Check PHP version
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '8.2.0', '>=')) {
            $this->addCheck('✅ PHP Version', "PHP $phpVersion (8.2+)");
        } else {
            $this->addError('❌ PHP Version', "PHP $phpVersion (Need 8.2+)");
        }

        // Check if Redis is available
        if (extension_loaded('redis')) {
            $this->addCheck('✅ Redis Extension', 'Installed');
        } else {
            $this->addWarning('⚠️  Redis Extension', 'Not installed (Optional but recommended)');
        }

        // Check if Node.js is available
        exec('node --version 2>/dev/null', $output, $returnCode);
        if ($returnCode === 0) {
            $this->addCheck('✅ Node.js', trim($output[0] ?? ''));
        } else {
            $this->addError('❌ Node.js', 'Not installed (Required for Vite)');
        }
    }

    private function checkDependencies()
    {
        echo "📦 Checking Dependencies...\n";

        $composerFile = $this->projectPath . '/composer.json';
        $packageFile = $this->projectPath . '/package.json';

        if (file_exists($composerFile)) {
            $composer = json_decode(file_get_contents($composerFile), true);
            
            // Check Laravel version
            if (isset($composer['require']['laravel/framework'])) {
                $version = $composer['require']['laravel/framework'];
                if (str_contains($version, '11')) {
                    $this->addCheck('✅ Laravel Version', 'v11.x');
                } else {
                    $this->addWarning('⚠️  Laravel Version', "Version: $version (Optimized for 11.x)");
                }
            }

            // Check required packages
            $requiredPackages = [
                'laravel/framework',
                'bagisto/bagisto',
                'spatie/laravel-responsecache',
            ];

            foreach ($requiredPackages as $package) {
                if (isset($composer['require'][$package]) || isset($composer['require-dev'][$package])) {
                    $this->addCheck('✅ ' . basename($package), 'Installed');
                } else {
                    $this->addWarning('ℹ️  ' . basename($package), 'Not explicitly required');
                }
            }
        }

        if (file_exists($packageFile)) {
            $package = json_decode(file_get_contents($packageFile), true);
            
            // Check Vite
            if (isset($package['devDependencies']['vite'])) {
                $version = $package['devDependencies']['vite'];
                if (str_contains($version, '5') || str_contains($version, '7')) {
                    $this->addCheck('✅ Vite', $version);
                }
            }

            // Check Vue
            if (isset($package['dependencies']['vue']) || isset($package['devDependencies']['vue'])) {
                $this->addCheck('✅ Vue.js', 'Installed');
            }
        }
    }

    private function displayResults()
    {
        echo "\n╔════════════════════════════════════════════════════════════════╗\n";
        echo "║                         RESULTS                               ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        if (!empty($this->checks)) {
            echo "✅ PASSED CHECKS (" . count($this->checks) . ")\n";
            echo "─────────────────────────────────────────────────────────────\n";
            foreach ($this->checks as $check) {
                echo "  " . $check['status'] . " " . str_pad($check['name'], 30) . " " . $check['message'] . "\n";
            }
            echo "\n";
        }

        if (!empty($this->warnings)) {
            echo "⚠️  WARNINGS (" . count($this->warnings) . ")\n";
            echo "─────────────────────────────────────────────────────────────\n";
            foreach ($this->warnings as $warning) {
                echo "  " . $warning['status'] . " " . str_pad($warning['name'], 30) . " " . $warning['message'] . "\n";
            }
            echo "\n";
        }

        if (!empty($this->errors)) {
            echo "❌ ERRORS (" . count($this->errors) . ")\n";
            echo "─────────────────────────────────────────────────────────────\n";
            foreach ($this->errors as $error) {
                echo "  " . $error['status'] . " " . str_pad($error['name'], 30) . " " . $error['message'] . "\n";
            }
            echo "\n";
        }
    }

    private function generateReport()
    {
        $totalChecks = count($this->checks) + count($this->warnings) + count($this->errors);
        $passRate = !empty($totalChecks) ? (count($this->checks) / $totalChecks) * 100 : 0;

        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║                    SUMMARY REPORT                             ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        echo sprintf("Total Checks: %d\n", $totalChecks);
        echo sprintf("Passed: %d (%d%%)\n", count($this->checks), (int)$passRate);
        echo sprintf("Warnings: %d\n", count($this->warnings));
        echo sprintf("Errors: %d\n\n", count($this->errors));

        if (count($this->errors) === 0 && count($this->warnings) === 0) {
            echo "🎉 ALL CHECKS PASSED! Ready for implementation.\n\n";
            echo "Next Steps:\n";
            echo "1. Register provider in bootstrap/providers.php\n";
            echo "2. Configure .env with cache settings\n";
            echo "3. Run: php artisan cache:clear\n";
            echo "4. Run: npm run build\n";
            echo "5. Test: php artisan serve\n\n";
        } elseif (count($this->errors) === 0) {
            echo "✅ READY WITH WARNINGS\n\n";
            echo "Please review warnings above before implementing.\n\n";
        } else {
            echo "❌ ISSUES FOUND\n\n";
            echo "Please resolve errors above before implementing.\n\n";
        }

        echo "📚 For more details, see:\n";
        echo "- QUICK_START_PERFORMANCE.md\n";
        echo "- PERFORMANCE_OPTIMIZATION.md\n";
        echo "- INSTALL_OPTIMIZATION.md\n\n";
    }

    private function addCheck($name, $message)
    {
        $this->checks[] = [
            'status' => '✅',
            'name' => $name,
            'message' => $message,
        ];
    }

    private function addWarning($name, $message)
    {
        $this->warnings[] = [
            'status' => '⚠️',
            'name' => $name,
            'message' => $message,
        ];
    }

    private function addError($name, $message)
    {
        $this->errors[] = [
            'status' => '❌',
            'name' => $name,
            'message' => $message,
        ];
    }
}

// Run checker
$checker = new PerformanceChecker(__DIR__);
$checker->run();
?>
