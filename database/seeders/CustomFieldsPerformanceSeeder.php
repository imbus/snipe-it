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

        $dbColsForThisRun = [];
        $rows = [];
        for ($i = 1; $i <= $count; $i++) {
            $dbcol = 'cf_'.Str::lower(Str::random(12));
            $dbColsForThisRun[] = $dbcol;

            $data = [
                'name'                     => 'perf_field_'.$i,
                'db_column'                => $dbcol,
                'element'                  => 'text',
                'field_values'             => 'A,B,C',
                'help_text'                => 'perf',
                'format'                   => null,
                'created_by'               => $uid,
                'show_in_listview'         => 1,
                'show_in_email'            => 0,
                'show_in_requestable_list' => 0,
                'is_unique'                => 0,
                'display_in_user_view'     => 0,
                'auto_add_to_fieldsets'    => 0,
                'display_checkin'          => 0,
                'display_checkout'         => 0,
                'display_audit'            => 0,
                'field_encrypted'          => 0,
                'created_at'               => $now,
                'updated_at'               => $now,
            ];
            $rows[] = array_intersect_key($data, array_flip($cols));
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('custom_fields')->upsert($chunk, ['db_column'], ['name','help_text','updated_at']);
        }

        if (Schema::hasTable('custom_fieldsets') && Schema::hasTable('custom_field_custom_fieldset')) {

            $fieldsetId = DB::table('custom_fieldsets')
                ->where('name', 'perf_fieldset_all')
                ->value('id');

            if (!$fieldsetId) {
                $fieldsetId = DB::table('custom_fieldsets')->insertGetId([
                    'name'       => 'perf_fieldset_all',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $fieldIds = DB::table('custom_fields')
                ->whereIn('db_column', $dbColsForThisRun)
                ->pluck('id')
                ->all();

            $pivotCols = Schema::getColumnListing('custom_field_custom_fieldset');
            $pivotRows = [];
            $ord = 0;
            foreach ($fieldIds as $fid) {
                $row = [
                    'custom_fieldset_id' => $fieldsetId,
                    'custom_field_id'    => $fid,
                ];
                if (in_array('order', $pivotCols))    { $row['order'] = $ord++; }
                if (in_array('required', $pivotCols)) { $row['required'] = 0; }
                if (in_array('created_at', $pivotCols)) { $row['created_at'] = $now; }
                if (in_array('updated_at', $pivotCols)) { $row['updated_at'] = $now; }
                $pivotRows[] = $row;
            }

            foreach (array_chunk($pivotRows, 1000) as $chunk) {

                DB::table('custom_field_custom_fieldset')->insertOrIgnore($chunk);
            }
        }

        echo "Custom fields upserted: {$count} and attached to fieldset 'perf_fieldset_all'\n";
    }
}