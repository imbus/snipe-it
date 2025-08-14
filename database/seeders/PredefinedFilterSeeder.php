<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PredefinedFilter;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;

class PredefinedFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PredefinedFilter::truncate();

        $user_to_delete = User::where("email","predefined@filter.com")->first();

        if ($user_to_delete) {
            $user_to_delete->delete();
        }

        $user = User::create([
            'activated' => 1,
            'first_name' => 'Filter',
            'last_name'=> 'Predefined',
            'username' => 'filter',
            'email'=> 'predefined@filter.com',
            'password'=> Hash::make('1234567890'),
            'permissions' => '{"superuser":"1"}',
        ]);

        if (!$user instanceof \App\Models\User) {
            throw new \Exception('user could not be created.. seeder aborting..');
        }

        $predefinedFilters = [
            ['name' => 'a company', 'company_id' => '1', 'created_by' => (string) $user->id],
            ['name' => 'Category I Dont know', 'category_id' => '2', 'created_by' => (string) $user->id],
            ['name' => 'Some Location', 'location_id' => '2', 'created_by' => (string) $user->id],
            ['name' => 'Some rdt location', 'rtd_location_id' => '2', 'created_by' => (string) $user->id],
            ['name' => 'Some Model', 'model_id' => '2', 'created_by' => (string) $user->id],
            ['name' => 'Some Manufacturer', 'manufacturer_id' => '2', 'created_by' => (string) $user->id],
            ['name' => 'Some Status', 'status_id' => '1', 'created_by' => (string) $user->id],
            ['name' => 'created_start', 'created_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'created_end', 'created_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'purchased_start', 'purchased_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'purchased_end', 'purchased_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'checked_out_start', 'checked_out_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'checked_out_end', 'checked_out_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'checked_in_start', 'checked_in_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'checked_in_end', 'checked_in_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'expected_check_in_start', 'expected_check_in_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'expected_check_in_end', 'expected_check_in_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'asset_eol_date_start', 'asset_eol_date_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'asset_eol_date_end', 'asset_eol_date_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'last_audit_start', 'last_audit_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'last_audit_end', 'last_audit_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'next_audit_start', 'next_audit_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'next_audit_end', 'next_audit_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'last_updated_start', 'last_updated_start' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'last_updated_end', 'last_updated_end' => '2025-08-11', 'created_by' => (string) $user->id],
            ['name' => 'asset_tag with 123 in it', 'asset_tag' => '123', 'created_by' => (string) $user->id],
            ['name' => 'custom_field_ram_32', 'custom_fields' => ['_snipeit_ram_3' => '32'], 'created_by' => (string) $user->id],
            // create custom field, and then set instead of 'snipeit_ram_3' the column db_column from the Customfield!
        ];

        try {
            foreach ($predefinedFilters as $index => $filter) {
                PredefinedFilter::create($filter);
            }
        } catch (\Exception $e) {
            echo "Fehler: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString();
        }
    }
}
