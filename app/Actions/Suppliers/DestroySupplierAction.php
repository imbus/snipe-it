<?php

namespace App\Actions\Suppliers;

use App\Models\Supplier;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasLicenses;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestroySupplierAction
{
    /**
     * @throws ModelStillHasLicenses
     * @throws ModelStillHasAssets
     * @throws ModelStillHasAssetMaintenances
     */
    static function run(Supplier $supplier): bool
    {
        $supplier->loadCount([
            'asset_maintenances as asset_maintenances_count',
            'assets as assets_count',
            'licenses as licenses_count'
        ]);
        if ($supplier->assets_count > 0) {
            throw new ModelStillHasAssets($supplier);
        }

        if ($supplier->asset_maintenances_count > 0) {
            throw new ModelStillHasAssetMaintenances($supplier);
        }

        if ($supplier->licenses_count > 0) {
            throw new ModelStillHasLicenses($supplier);
        }

        if ($supplier->image) {
            try {
                Storage::disk('public')->delete('suppliers/'.$supplier->image);
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }

        $supplier->delete();

        return true;
    }
}
