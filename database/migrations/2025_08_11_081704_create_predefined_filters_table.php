<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predefined_filters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->integer('created_by');
            $table->string('name');

            $table->integer('company_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('rtd_location_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('manufacturer_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('status_id')->nullable();

            $table->date('created_start')->nullable();
            $table->date('created_end')->nullable();
            $table->date('purchased_start')->nullable();
            $table->date('purchased_end')->nullable();
            $table->date('checked_out_start')->nullable();
            $table->date('checked_out_end')->nullable();
            $table->date('checked_in_start')->nullable();
            $table->date('checked_in_end')->nullable();
            $table->date('expected_check_in_start')->nullable();
            $table->date('expected_check_in_end')->nullable();
            $table->date('asset_eol_date_start')->nullable();
            $table->date('asset_eol_date_end')->nullable();
            $table->date('last_audit_start')->nullable();
            $table->date('last_audit_end')->nullable();
            $table->date('next_audit_start')->nullable();
            $table->date('next_audit_end')->nullable();
            $table->date('last_updated_start')->nullable();
            $table->date('last_updated_end')->nullable();

            $table->string('asset_name')->nullable();
            $table->integer('asset_tag')->nullable();
            $table->integer('serial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predefined_filters');
    }
};
