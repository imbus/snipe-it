<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class PredefinedFilter extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ValidatingTrait;

    protected $casts = [

    ];

    protected $fillable = [
        'created_by',
        'name',
        // search attributes
        'company_id',
        'location_id',
        'rtd_location_id',
        'supplier_id',
        'model_id',
        'manufacturer_id',
        'category_id',
        'status_id',
        'created_start',
        'created_end',
        'purchased_start',
        'purchased_end',
        'checked_out_start',
        'checked_out_end',
        'checked_in_start',
        'checked_in_end',
        'expected_check_in_start',
        'expected_check_in_end',
        'asset_eol_date_start',
        'asset_eol_date_end',
        'last_audit_start',
        'last_audit_end',
        'next_audit_start',
        'next_audit_end',
        'last_updated_start',
        'last_updated_end',
        'asset_name',
        'asset_tag',
        'serial',
    ];

    protected $rules = [
        'name'                    => ['required', 'string', 'max:255'],
        'created_by'              => ['required', 'string'],
        'company_id'              => ['nullable', 'integer', 'exists:companies,id'],
        'location_id'             => ['nullable', 'integer', 'exists:locations,id', 'fmcs_location'],
        'rtd_location_id'         => ['nullable', 'integer', 'exists:locations,id', 'fmcs_location'],
        'supplier_id'             => ['nullable', 'integer', 'exists:suppliers,id'],
        'model_id'                => ['nullable', 'integer', 'exists:models,id,deleted_at,NULL', 'not_array'],
        'manufacturer_id'         => ['nullable', 'integer', 'exists:manufacturers,id'],
        'category_id'             => ['nullable', 'integer', 'exists:categories,id'],
        'status_id'               => ['nullable', 'integer', 'exists:status_labels,id'],
        'created_start'           => ['nullable', 'date'],
        'created_end'             => ['nullable', 'date'],
        'purchased_start'         => ['nullable', 'date'],
        'purchased_end'           => ['nullable', 'date'],
        'checked_out_start'       => ['nullable', 'date'],
        'checked_out_end'         => ['nullable', 'date'],
        'checked_in_start'        => ['nullable', 'date'],
        'checked_in_end'          => ['nullable', 'date'],
        'expected_check_in_start' => ['nullable', 'date'],
        'expected_check_in_end'   => ['nullable', 'date'],
        'asset_eol_date_start'    => ['nullable', 'date'],
        'asset_eol_date_end'      => ['nullable', 'date'],
        'last_audit_start'        => ['nullable', 'date'],
        'last_audit_end'          => ['nullable', 'date'],
        'next_audit_start'        => ['nullable', 'date'],
        'next_audit_end'          => ['nullable', 'date'],
        'last_updated_start'      => ['nullable', 'date'],
        'last_updated_end'        => ['nullable', 'date'],
        'asset_name'              => ['nullable', 'string'],
        'asset_tag'               => ['nullable', 'integer'],
        'serial'                  => ['nullable', 'unique_undeleted:assets,serial'],
    ];


    /**
     * -----------------------------------------------
     * BEGIN QUERY SCOPES
     * -----------------------------------------------
     **/

    // public function scopeAuth($query) {
    //     $userId = auth()->id();

    //     return $query->where('created_by', $userId);
    // }
}
