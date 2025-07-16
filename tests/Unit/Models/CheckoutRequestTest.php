<?php

namespace Tests\Unit\Models;

use App\Models\Asset;
use App\Models\CheckoutRequest;
use Tests\TestCase;

class CheckoutRequestTest extends TestCase
{
    public function test_checkout_request_soft_deleted_when_requested_asset_soft_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->create();

        $requestedAsset = $checkoutRequest->requestedItem;

        $this->assertInstanceOf(Asset::class, $requestedAsset);

        $requestedAsset->delete();

        $this->assertSoftDeleted($checkoutRequest->fresh());
    }

    public function test_checkout_request_deleted_when_requested_asset_force_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->create();

        $requestedAsset = $checkoutRequest->requestedItem;

        $this->assertInstanceOf(Asset::class, $requestedAsset);

        $requestedAsset->forceDelete();

        $this->assertDatabaseMissing('checkout_requests', ['id' => $checkoutRequest->id]);
    }

    public function test_checkout_request_soft_deleted_when_requesting_user_soft_deleted()
    {
        $checkoutRequest = CheckoutRequest::factory()->create();

        $requestingUser = $checkoutRequest->user;

        $requestingUser->delete();

        $this->assertSoftDeleted($checkoutRequest->fresh());
    }

    public function test_checkout_request_deleted_when_requesting_user_force_deleted()
    {
        $this->markTestIncomplete();

        $checkoutRequest = CheckoutRequest::factory()->create();

        $requestingUser = $checkoutRequest->user;

        $requestingUser->forceDelete();

        $this->assertDatabaseMissing('checkout_requests', ['id' => $checkoutRequest->id]);
    }
}
