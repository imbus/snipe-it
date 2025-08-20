<?php

namespace App\Console\Commands;

use App\Models\Accessory;
use App\Models\AccessoryCheckout;
use App\Models\CheckoutAcceptance;
use App\Models\User;
use Illuminate\Console\Command;

class CleanDeclinedAccessoryCheckouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snipeit:clean-declined-accessory-checkouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    // @todo

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $accessoryCheckouts = AccessoryCheckout::where('assigned_type', User::class)->get();
        //
        // dd($accessoryCheckouts);

        $declinedCheckoutAcceptances = CheckoutAcceptance::query()
            ->where([
                'checkoutable_type' => Accessory::class,
            ])
            // if it was declined and the qty is null that means it potentially left
            // some entries in the `accessories_checkout` table behind.
            ->whereNull('qty')
            ->declined()
            ->get();

        

        return 0;
    }
}
