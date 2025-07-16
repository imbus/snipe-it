<?php

namespace Tests\Feature\Console;

use App\Models\Asset;
use App\Models\CheckoutRequest;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class CleanOldCheckoutRequestsTest extends TestCase
{
    public function test_clean_old_checkout_requests_command_for_soft_deleted_asset()
    {
        $validRequest = CheckoutRequest::factory()->create();

        $requestForSoftDeletedAsset = CheckoutRequest::factory()->create();
        $this->assertInstanceOf(Asset::class, $requestForSoftDeletedAsset->requestedItem);
        Model::withoutEvents(fn() => $requestForSoftDeletedAsset->requestedItem->delete());

        $this->artisan('snipeit:clean-old-checkout-requests')->assertExitCode(0);

        $this->assertNotSoftDeleted($validRequest);
        $this->assertSoftDeleted($requestForSoftDeletedAsset->fresh());
    }

    public function test_clean_old_checkout_requests_command_for_missing_asset()
    {
        $validRequest = CheckoutRequest::factory()->create();
        $missingAsset = CheckoutRequest::factory()->create(['requestable_id' => 99999999]);

        $this->artisan('snipeit:clean-old-checkout-requests')->assertExitCode(0);

        $this->assertNotSoftDeleted($validRequest);
        $this->assertDatabaseMissing('checkout_requests', ['requestable_id' => $missingAsset->requestable_id]);
    }

    public function test_clean_old_checkout_requests_command_for_soft_deleted_model()
    {
        $this->markTestIncomplete();
    }

    public function test_clean_old_checkout_requests_command_for_missing_model()
    {
        $this->markTestIncomplete();
    }

    public function test_clean_old_checkout_requests_command_for_soft_deleted_user()
    {
        $validRequest = CheckoutRequest::factory()->create();

        $requestForSoftDeletedUser = CheckoutRequest::factory()->create();
        Model::withoutEvents(fn() => $requestForSoftDeletedUser->user->delete());

        $this->artisan('snipeit:clean-old-checkout-requests')->assertExitCode(0);

        $this->assertNotSoftDeleted($validRequest);
        $this->assertSoftDeleted($requestForSoftDeletedUser->fresh());
    }

    public function test_clean_old_checkout_requests_command_for_missing_user()
    {
        $validRequest = CheckoutRequest::factory()->create();
        $missingUser = CheckoutRequest::factory()->create(['user_id' => 99999999]);

        $this->artisan('snipeit:clean-old-checkout-requests')->assertExitCode(0);

        $this->assertNotSoftDeleted($validRequest);
        $this->assertDatabaseMissing('checkout_requests', ['user_id' => $missingUser->user_id]);
    }
}
