<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\{Asset, AssetModel, Location, Manufacturer, Statuslabel, User, Company, PredefinedFilter};

class HugeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['assets','models','locations','manufacturers','status_labels','users','companies','settings'] as $t) {
            if (!Schema::hasTable($t)) { echo "Skip: missing table {$t}\n"; return; }
        }

        DB::table('settings')->updateOrInsert(['id'=>1], ['scope_locations_fmcs'=>0]);

        DB::disableQueryLog();

        $models    = (int) env('SEED_MODELS',    300);
        $locations = (int) env('SEED_LOCATIONS', 120);
        $users     = (int) env('SEED_USERS',     2000);
        $assets    = (int) env('SEED_ASSETS',    50000);
        $chunk     = (int) env('SEED_CHUNK',     2000);
        $now       = now();

        Statuslabel::factory()->count(10)->create();

        $companyRows = [];
        for ($i = 1; $i <= 8; $i++) {
            $companyRows[] = [
                'name'       => "perf_company_{$i}",
                'created_by' => 1,
                'notes'      => 'Created by DB seeder',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('companies')->upsert($companyRows, ['name'], ['notes','updated_at','created_by']);

        $manuRows = [];
        for ($i = 1; $i <= 40; $i++) {
            $manuRows[] = [
                'name'       => "perf_manufacturer_{$i}",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('manufacturers')->upsert($manuRows, ['name'], ['updated_at']);

        $companyIds      = Company::pluck('id');
        $manufacturerIds = Manufacturer::pluck('id');

        Location::factory()
            ->count($locations)
            ->state(fn () => ['company_id' => $companyIds->random()])
            ->create();

        User::factory()
            ->count($users)
            ->state(fn () => ['company_id' => $companyIds->random()])
            ->create();

        AssetModel::factory()
            ->count($models)
            ->state(fn () => ['manufacturer_id' => $manufacturerIds->random()])
            ->create();

        if (
            Schema::hasTable('predefined_filters') &&
            class_exists(PredefinedFilter::class) &&
            method_exists(PredefinedFilter::class, 'factory')
        ) {
            $pfCreator = User::inRandomOrder()->first() ?? User::factory()->create();
            $pfCompany = $companyIds->random();

            PredefinedFilter::factory()
                ->count(50)
                ->state(fn () => ['filter_data' => ['company_id' => [$pfCompany]]])
                ->for($pfCreator, 'createdBy')
                ->create();
        }

        Asset::withoutEvents(function () use ($assets, $chunk) {
            for ($i = 0; $i < $assets; $i += $chunk) {
                $count = min($chunk, $assets - $i);
                Asset::factory()->count($count)->create();
                echo "Assets: ".($i + $count)."/{$assets}\n";
            }
        });
    }
}