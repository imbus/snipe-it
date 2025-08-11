<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PredefinedFilter;
use App\Models\User;

class PredefinedFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PredefinedFilter::truncate();

        // $user = User::create([
        //     'name' => 'filter',
        //     'email' => 'predefined@filter.com',
        //     'password' => 'filter',
        // ]);
        // TODO: use $user inseatd of '1'
        $predefinedFilters = [
            ['name' => 'a company', 'company_id' => '1', 'created_by' => '1'],
            ['name' => 'Category I Dont know', 'category_id' => '2', 'created_by' => '1'],
            ['name' => 'Some Location', 'location_id' => '2', 'created_by' => '1'],
            ['name'=> 'Some rdt location', 'rtd_location_id' => '2', 'created_by' => '1'],
            ['name'=> 'Some Model', 'model_id' => '2', 'created_by' => '1'],
            ['name'=> 'Some Manufacturer', 'manufacturer_id' => '2', 'created_by' => '1'],
            ['name'=> 'Some Status', 'status_id' => '1', 'created_by' => '1'],
            ['name'=> 'created_start', 'created_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'created_end', 'created_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'purchased_start', 'purchased_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'purchased_end', 'purchased_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'checked_out_start', 'checked_out_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'checked_out_end', 'checked_out_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'checked_in_start', 'checked_in_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'checked_in_end', 'checked_in_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'expected_check_in_start', 'expected_check_in_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'expected_check_in_end', 'expected_check_in_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'asset_eol_date_start', 'asset_eol_date_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'asset_eol_date_end', 'asset_eol_date_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'last_audit_start', 'last_audit_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'last_audit_end', 'last_audit_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'next_audit_start', 'next_audit_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'next_audit_end', 'next_audit_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'last_updated_start', 'last_updated_start' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'last_updated_end', 'last_updated_end' => '2025-08-11', 'created_by' => '1'],
            ['name'=> 'asset_tag with 123 in it', 'asset_tag' => '123', 'created_by' => '1'],
        ];

        foreach ($predefinedFilters as $filter) {
            PredefinedFilter::create($filter);
        }
    }
}
