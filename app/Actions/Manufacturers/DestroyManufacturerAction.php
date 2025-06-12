<?php

namespace App\Actions\Manufacturers;

use App\Exceptions\ModelStillHasAccessories;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasComponents;
use App\Exceptions\ModelStillHasConsumables;
use App\Exceptions\ModelStillHasLicenses;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestroyManufacturerAction
{
    /**
     * @throws ModelStillHasAssets
     * @throws ModelStillHasComponents
     * @throws ModelStillHasAccessories
     * @throws ModelStillHasLicenses
     * @throws ModelStillHasConsumables
     */
    static function run(Manufacturer $manufacturer): bool
    {
        $manufacturer->loadCount([
            'assets as assets_count',
            'accessories as accessories_count',
            'consumables as consumables_count',
            'components as components_count',
            'licenses as licenses_count',
        ]);

        if ($manufacturer->assets_count > 0) {
            throw new ModelStillHasAssets($manufacturer);
        }
        if ($manufacturer->accessories_count > 0) {
            throw new ModelStillHasAccessories($manufacturer);
        }
        if ($manufacturer->consumables_count > 0) {
            throw new ModelStillHasConsumables($manufacturer);
        }
        if ($manufacturer->components_count > 0) {
            throw new ModelStillHasComponents($manufacturer);
        }
        if ($manufacturer->licenses_count > 0) {
            throw new ModelStillHasLicenses($manufacturer);
        }

        if ($manufacturer->image) {
            try {
                Storage::disk('public')->delete('manufacturers/'.$manufacturer->image);
            } catch (\Exception $e) {
                Log::info($e);
            }
        }

        $manufacturer->delete();

        return true;
    }

}