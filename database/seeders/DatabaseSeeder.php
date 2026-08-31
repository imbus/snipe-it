<?php

namespace Database\Seeders;

use App\Models\Setting;
use Database\Seeders\Concerns\ReportsMemory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    use ReportsMemory;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->reportMemory('DatabaseSeeder start');

        // Only create default settings if they do not exist in the db.
        if (! Setting::first()) {
            // factory(Setting::class)->create();
            $this->call(SettingsSeeder::class);
        }

        $this->call(CompanySeeder::class);
        $this->reportMemory('after CompanySeeder');
        $this->call(CategorySeeder::class);
        $this->reportMemory('after CategorySeeder');
        $this->call(LocationSeeder::class);
        $this->reportMemory('after LocationSeeder');
        $this->call(DepartmentSeeder::class);
        $this->reportMemory('after DepartmentSeeder');
        $this->call(UserSeeder::class);
        $this->reportMemory('after UserSeeder');
        $this->call(DepreciationSeeder::class);
        $this->reportMemory('after DepreciationSeeder (1st)');
        $this->call(ManufacturerSeeder::class);
        $this->reportMemory('after ManufacturerSeeder');
        $this->call(SupplierSeeder::class);
        $this->reportMemory('after SupplierSeeder');

        // Custom-field seeders must run before AssetModelSeeder. Some model
        // factories look up fieldsets by name while building the models.
        $dataset = env('TEST_DATASET', 'default');
        if ($dataset === 'huge+custom') {
            $this->call(CustomFieldsStandaloneSeeder::class);
            $this->reportMemory('after CustomFieldsStandaloneSeeder');
        } else {
            $this->call(CustomFieldSeeder::class);
            $this->reportMemory('after CustomFieldSeeder');
        }

        $this->call(AssetModelSeeder::class);
        $this->reportMemory('after AssetModelSeeder');
        $this->call(DepreciationSeeder::class);
        $this->reportMemory('after DepreciationSeeder (2nd)');
        $this->call(StatuslabelSeeder::class);
        $this->reportMemory('after StatuslabelSeeder');

        // Wipe Orders / OrderItems before any inventory seeder runs so a
        // re-seed doesn't inherit acquisition rows from the previous
        // session. The inventory seeders (Accessory / Asset / Component /
        // Consumable) truncate their own tables but the observer-written
        // Orders / OrderItems live in a shared polymorphic pair, so we
        // clear them centrally here.
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();

        $this->call(AccessorySeeder::class);
        $this->reportMemory('after AccessorySeeder');
        $this->call(AssetSeeder::class);
        $this->reportMemory('after AssetSeeder');
        $this->call(LicenseSeeder::class);
        $this->reportMemory('after LicenseSeeder');
        $this->call(ComponentSeeder::class);
        $this->reportMemory('after ComponentSeeder');
        $this->call(ConsumableSeeder::class);
        $this->reportMemory('after ConsumableSeeder');
        $this->call(ActionlogSeeder::class);
        $this->reportMemory('after ActionlogSeeder');
        $this->call(MaintenanceSeeder::class);
        $this->reportMemory('after MaintenanceSeeder');
        $this->call(PredefinedFilterSeeder::class);
        $this->call(PredefinedFilterPermissionSeeder::class);

        Artisan::call('snipeit:sync-asset-locations', ['--output' => 'all']);
        Log::info(Artisan::output());

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call(ImportSeeder::class);
        $this->reportMemory('after ImportSeeder');
        DB::table('requested_assets')->truncate();

        $this->reportMemory('DatabaseSeeder end');
    }
}
