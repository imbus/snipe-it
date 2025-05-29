<?php

namespace Tests\Unit\Labels;

use App\Models\Asset;
use App\Models\Setting;
use App\View\Label;
use Tests\TestCase;

class LabelTest extends TestCase
{
    /**
     * @link https://app.shortcut.com/grokability/story/29302
     */
    public function test_handles_location_not_being_set_on_asset_gracefully()
    {
        $this->settings->set([
            'label2_enable' => 1,
            'label2_2d_type' => 'QRCODE',
            'label2_2d_target' => 'location',
        ]);

        $asset = Asset::factory()->create(['location_id' => null]);

        // pulled from BulkAssetsController@edit method
        $label = (new Label)
            // receives eloquent collection in practice
            ->with('assets', Asset::findMany($asset->id))
            ->with('settings', Setting::getSettings())
            ->with('bulkedit', true)
            ->with('count', 0);

        $label->render();
    }
}
