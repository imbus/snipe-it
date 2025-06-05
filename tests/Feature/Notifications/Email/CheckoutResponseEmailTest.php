<?php

namespace Tests\Feature\Notifications\Email;

use Tests\TestCase;

class CheckoutResponseEmailTest extends TestCase
{
    public static function scenarios()
    {
        yield 'Accepting checkout acceptance configured to send alert';
        yield 'Declining checkout acceptance configured to send alert';
        yield 'Accepting checkout acceptance not configured to send alert';
        yield 'Declining checkout acceptance not configured to send alert';
    }

    public function test_checkout_response_alert()
    {
        $this->markTestIncomplete();
    }
}
