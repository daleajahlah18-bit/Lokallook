#!/usr/bin/env php
<?php
/**
 * Image Storage Link Fixer
 * Untuk mengatasi masalah gambar tidak muncul
 */

class ImageStorageFixer
{
    private $publicPath;
    private $storagePath;

    public function __construct()
    {
        $this->publicPath = getcwd() . '/public';
        $this->storagePath = getcwd() . '/storage/app/public';
    }

    public function run()
    {
        echo "\n╔════════════════════════════════════════════════════════════════╗\n";
        echo "║          IMAGE STORAGE LINK FIXER v1.0                       ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        $this->checkStorageLink();
        $this->fixPermissions();
        $this->validateImages();
        $this->generateStorageLink();
    }

    private function checkStorageLink()
    {
        echo "🔍 Checking storage link...\n";

        $storageLink = $this->publicPath . '/storage';

        if (is_link($storageLink)) {
            echo "✅ Storage link exists\n";
            echo "   Target: " . readlink($storageLink) . "\n\n";
            return;
        }

        if (is_dir($storageLink)) {
            echo "⚠️  Storage folder exists (not a symlink)\n";
            echo "   Removing folder...\n";
            $this->removeDirectory($storageLink);
        }

        echo "❌ Storage link not found\n";
        echo "   Creating link...\n";
        $this->createStorageLink();
    }

    private function createStorageLink()
    {
        $storageLink = $this->publicPath . '/storage';
        
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }

        // Get relative path
        $relativePath = $this->getRelativePath($this->publicPath, $this->storagePath);

        if (symlink($relativePath, $storageLink)) {
            echo "✅ Symlink created successfully\n";
            echo "   Link: " . $storageLink . "\n";
            echo "   Target: " . $relativePath . "\n\n";
        } else {
            echo "❌ Failed to create symlink\n";
            echo "   Try running: php artisan storage:link\n\n";
        }
    }

    private function fixPermissions()
    {
        echo "🔐 Fixing permissions...\n";

        $dirs = [
            $this->storagePath,
            $this->storagePath . '/products',
            $this->storagePath . '/downloads',
            $this->publicPath . '/storage',
            getcwd() . '/storage/framework/cache',
            getcwd() . '/storage/logs',
        ];

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                chmod($dir, 0755);
                echo "✅ Permissions set for: " . basename($dir) . "\n";
            }
        }

        echo "\n";
    }

    private function validateImages()
    {
        echo "📁 Validating images...\n";

        $imagePath = $this->storagePath . '/products';

        if (!is_dir($imagePath)) {
            echo "ℹ️  No product images found yet\n\n";
            return;
        }

        $files = glob($imagePath . '/*');
        $imageCount = count($files);

        echo "✅ Found $imageCount file(s) in storage\n\n";

        if ($imageCount > 0) {
            echo "Sample files:\n";
            foreach (array_slice($files, 0, 5) as $file) {
                $url = '/storage/' . str_replace($this->storagePath, '', $file);
                echo "  • " . basename($file) . " → " . $url . "\n";
            }
            if ($imageCount > 5) {
                echo "  ... and " . ($imageCount - 5) . " more\n";
            }
            echo "\n";
        }
    }

    private function generateStorageLink()
    {
        echo "🔗 Storage link setup complete!\n\n";

        echo "📋 Configuration Status:\n";
        echo "────────────────────────────────────────────────────────────\n";

        $storageLink = $this->publicPath . '/storage';
        $isLink = is_link($storageLink);
        $isDir = is_dir($storageLink);

        echo "Storage Link Exists: " . ($isLink ? "✅ Yes (symlink)" : ($isDir ? "✅ Yes (folder)" : "❌ No")) . "\n";
        echo "Storage Path: " . $this->storagePath . "\n";
        echo "Public Storage: " . $storageLink . "\n";

        if ($isLink || $isDir) {
            echo "Status: ✅ READY\n\n";
        } else {
            echo "Status: ❌ NEEDS FIX\n\n";
        }
    }

    private function getRelativePath($from, $to)
    {
        $from = explode('/', $from);
        $to = explode('/', $to);

        $relPath = array_diff_assoc($to, $from);

        return implode('/', $relPath);
    }

    private function removeDirectory($dir)
    {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;
                is_dir($path) ? $this->removeDirectory($path) : unlink($path);
            }
            rmdir($dir);
        }
    }
}

// Run fixer
$fixer = new ImageStorageFixer();
$fixer->run();
?>
