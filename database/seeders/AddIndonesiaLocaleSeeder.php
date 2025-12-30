<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Filesystem\Filesystem;

class AddIndonesiaLocaleSeeder extends Seeder
{
    public function run()
    {
        $fs = new Filesystem();

        // 1) Insert locale record (safe-check)
        if (Schema::hasTable('locales')) {
            if (! DB::table('locales')->where('code', 'id')->exists()) {
                DB::table('locales')->insert([
                    'name'       => 'Bahasa Indonesia',
                    'code'       => 'id',
                    'direction'  => 'ltr',
                    'status'     => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info('Locale "id" inserted into locales table.');
            } else {
                $this->command->info('Locale "id" already exists in locales table.');
            }
        } else {
            $this->command->info('Table "locales" does not exist. Skipping DB insert.');
        }

        // 2) Copy translation folders en -> id for common packages
        $packageLangPaths = [
            base_path('packages/Webkul/Shop/src/Resources/lang'),
            base_path('packages/Webkul/Admin/src/Resources/lang'),
            base_path('packages/Webkul/Velocity/src/Resources/lang'),
            base_path('packages/Webkul/Catalog/src/Resources/lang'),
            base_path('packages/Webkul/Checkout/src/Resources/lang'),
        ];

        foreach ($packageLangPaths as $langPath) {
            $enPath = $langPath . DIRECTORY_SEPARATOR . 'en';
            $idPath  = $langPath . DIRECTORY_SEPARATOR . 'id';

            if ($fs->exists($enPath) && ! $fs->exists($idPath)) {
                $fs->copyDirectory($enPath, $idPath);
                $this->command->info("Copied translations: {$enPath} -> {$idPath}");
            } elseif (! $fs->exists($enPath)) {
                $this->command->info("Source lang folder not found: {$enPath}");
            } else {
                $this->command->info("Target lang folder already exists: {$idPath}");
            }
        }

        // 3) Ensure placeholder flag exists (public/themes/velocity/... path typical for Bagisto)
        $flagDir = public_path('themes/velocity/assets/images/flags');
        if (! $fs->exists($flagDir)) {
            $fs->makeDirectory($flagDir, 0755, true);
            $this->command->info("Created flag directory: {$flagDir}");
        }

        $flagFile = $flagDir . DIRECTORY_SEPARATOR . 'id.svg';
        if (! $fs->exists($flagFile)) {
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480">
  <rect width="640" height="240" fill="#ff0000"/>
  <rect y="240" width="640" height="240" fill="#ffffff"/>
</svg>
SVG;
            file_put_contents($flagFile, $svg);
            $this->command->info("Created placeholder flag: {$flagFile}");
        } else {
            $this->command->info("Flag already exists: {$flagFile}");
        }

        // 4) Ensure at least one minimal translation file exists (Shop/common.php)
        $shopCommonId = base_path('packages/Webkul/Shop/src/Resources/lang/id/common.php');
        if (! $fs->exists($shopCommonId)) {
            $content = <<<PHP
<?php
return [
    'home'     => 'Beranda',
    'cart'     => 'Keranjang',
    'checkout' => 'Pembayaran',
];
PHP;
            $dir = dirname($shopCommonId);
            if (! $fs->exists($dir)) {
                $fs->makeDirectory($dir, 0755, true);
            }
            file_put_contents($shopCommonId, $content);
            $this->command->info("Wrote minimal translation: {$shopCommonId}");
        } else {
            $this->command->info("Shop common.php (id) already exists.");
        }
    }
}