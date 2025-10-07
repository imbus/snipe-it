<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomFieldsPerformanceSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('custom_fields')) { echo "Skip: missing table custom_fields\n"; return; }

        $count = (int) env('SEED_CUSTOM_FIELDS', 300);
        $cols  = Schema::getColumnListing('custom_fields');
        $now   = now();
        $uid   = Schema::hasTable('users') ? DB::table('users')->value('id') : null;

        $rows = [];
        for ($i = 1; $i <= $count; $i++) {
            $data = [
                'name'                       => 'perf_field_'.$i,
                'db_column'                  => 'cf_'.Str::lower(Str::random(12)),
                'element'                    => 'text',
                'field_values'               => 'A,B,C',
                'help_text'                  => 'perf',
                'format'                     => null,
                'created_by'                 => $uid,
                'show_in_listview'           => 1,
                'show_in_email'              => 0,
                'show_in_requestable_list'   => 0,
                'is_unique'                  => 0,
                'display_in_user_view'       => 0,
                'auto_add_to_fieldsets'      => 0,
                'display_checkin'            => 0,
                'display_checkout'           => 0,
                'display_audit'              => 0,
                'field_encrypted'            => 0,
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ];

            $rows[] = array_intersect_key($data, array_flip($cols));
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('custom_fields')->upsert($chunk, ['db_column'], ['name','help_text','updated_at']);
        }

        echo "Custom fields upserted: {$count}\n";
    }
}