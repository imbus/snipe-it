<?php

namespace Tests\Support;

use App\Models\Actionlog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;

trait AssertsActionLogs
{

    public function assertHasTheseActionLogs(Model $item, array $statuses)
    {
        \Log::error("Okay, we're running the test macro now?");
        $logs = Actionlog::where(['item_id' => $item->id, 'item_type' => get_class($item)])->orderBy('id')->get();
        Assert::assertEquals(count($statuses), count($logs), "Wrong count of logs expected - expecting " . count($statuses) . ", got " . count($logs));
        $i = 0;
        foreach ($statuses as $status) {
            Assert::assertEquals($status, $logs[$i]->action_type, "Unexpected action type - " . $logs[$i]->action_type . " - expecting $status");
            $i++;
        }

    }
}

