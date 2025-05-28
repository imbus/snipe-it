<?php

namespace Tests\Unit\Models\Labels;

use App\Models\Labels\DefaultLabel;
use Tests\TestCase;

class DefaultLabelTest extends TestCase
{
    /**
     * @link https://app.shortcut.com/grokability/story/29281
     */
    public function test_handles_zero_values_for_columns_gracefully()
    {
        // Defaults
        // labels_pagewidth = 8.50000
        // labels_pmargin_left = 0.21975
        // labels_pmargin_right = 0.21975
        // labels_display_sgutter = 0.05000
        // labels_width = 2.62500

        // $this->settings->set([
        //     'labels_width' => 0.00000,
        //     'labels_display_sgutter' => 0.00000,
        // ]);

        $label = new DefaultLabel();

        // $label->getColumns();
    }

    /**
     * @link https://app.shortcut.com/grokability/story/29281
     */
    public function test_handles_zero_values_for_rows_gracefully()
    {
        $this->markTestIncomplete();

        // $this->settings->set([
        //     'labels_height' => 0.00000,
        //     'labels_display_bgutter' => 0.00000,
        // ]);

        $label = new DefaultLabel();

        // $label->getRows()
    }
}
