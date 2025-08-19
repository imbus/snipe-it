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
        Schema::table('settings', function (Blueprint $table) {

            if (!Schema::hasColumn('settings', 'ldap_display_name')) {
                $table->text('ldap_display_name')->after('ldap_fname_field')->nullable()->default(null);
            }

            if (!Schema::hasColumn('settings', 'ldap_zip')) {
                $table->text('ldap_zip')->after('ldap_manager')->nullable()->default(null);
            }

            if (!Schema::hasColumn('settings', 'ldap_state')) {
                $table->text('ldap_state')->after('ldap_manager')->nullable()->default(null);
            }

            if (!Schema::hasColumn('settings', 'ldap_city')) {
                $table->text('ldap_city')->after('ldap_manager')->nullable()->default(null);
            }

            if (!Schema::hasColumn('settings', 'ldap_address')) {
                $table->text('ldap_address')->after('ldap_manager')->nullable()->default(null);
            }

            if (!Schema::hasColumn('settings', 'ldap_mobile')) {
                $table->text('ldap_mobile')->after('ldap_phone_field')->nullable()->default(null);
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_display_name')) {
                $table->dropColumn('ldap_display_name');
            }
        });

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_zip')) {
                $table->dropColumn('ldap_zip');
            }
        });
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_address')) {
                $table->dropColumn('ldap_address');
            }
        });
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_city')) {
                $table->dropColumn('ldap_city');
            }
        });
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_state')) {
                $table->dropColumn('ldap_state');
            }
        });
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'ldap_mobile')) {
                $table->dropColumn('ldap_mobile');
            }
        });


    }
};
