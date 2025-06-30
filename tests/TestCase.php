<?php

namespace Tests;

use App\Http\Middleware\SecurityHeaders;
use App\Models\Actionlog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Tests\Support\AssertsAgainstSlackNotifications;
use Tests\Support\CanSkipTests;
use Tests\Support\CustomTestMacros;
use Tests\Support\InteractsWithAuthentication;
use Tests\Support\InitializesSettings;

abstract class TestCase extends BaseTestCase
{
    use AssertsAgainstSlackNotifications;
    use CanSkipTests;
    use CreatesApplication;
    use CustomTestMacros;
    use InteractsWithAuthentication;
    use InitializesSettings;
    use LazilyRefreshDatabase;

    private array $globallyDisabledMiddleware = [
        SecurityHeaders::class,
    ];

    protected function setUp(): void
    {
        $this->guardAgainstMissingEnv();

        parent::setUp();

        $this->registerCustomMacros();

        $this->withoutMiddleware($this->globallyDisabledMiddleware);

        $this->initializeSettings();
    }

    private function guardAgainstMissingEnv(): void
    {
        if (!file_exists(realpath(__DIR__ . '/../') . '/.env.testing')) {
            throw new RuntimeException(
                '.env.testing file does not exist. Aborting to avoid wiping your local database.'
            );
        }
    }

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
