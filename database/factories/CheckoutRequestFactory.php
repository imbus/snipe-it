<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\CheckoutRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckoutRequestFactory extends Factory
{
    protected $model = CheckoutRequest::class;

    public function definition(): array
    {
        return [
            'requestable_id' => Asset::factory(),
            'requestable_type' => Asset::class,
            'quantity' => 1,
            'user_id' => User::factory(),
        ];
    }
}
