<?php

namespace Tests\Support;

use App\Models\Setting;
use App\Models\Statuslabel;

trait InitializesSettings
{
    protected Settings $settings;

    public function initializeSettings()
    {
        if (app()->environment('testing')) {
            if (class_exists(\Database\Seeders\DemoSeeder::class)) {
                putenv('SEED_DEMO=false');
                config(['snipeit.seed_demo' => false]);
            }
        }

        $this->settings = Settings::initialize();

        $this->beforeApplicationDestroyed(fn () => Setting::$_cache = null);
        // Same idea as Setting::$_cache — a per-request memo lives across
        // tests if we don't explicitly reset it. Without this, the second
        // test in a file would see status_label IDs from the *previous*
        // test's transactional rows (which have since rolled back).
        $this->beforeApplicationDestroyed(fn () => Statuslabel::clearIdCache());
    }
}