<?php

namespace App\Actions\Suppliers;

use App\Models\Supplier;
use App\Exceptions\ItemStillHasAssets;
use App\Exceptions\ItemStillHasMaintenances;
use App\Exceptions\ItemStillHasLicenses;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestroySupplierAction
{
    /**
     * @throws ItemStillHasLicenses
     * @throws ItemStillHasAssets
     * @throws ItemStillHasMaintenances
     */
    static function run(Supplier $supplier): bool
    {
        $supplier->loadCount([
            'maintenances as maintenances_count',
            'assets as assets_count',
            'licenses as licenses_count'
        ]);
        if ($supplier->assets_count > 0) {
            throw new ItemStillHasAssets($supplier);
        }

        if ($supplier->maintenances_count > 0) {
            throw new ItemStillHasMaintenances($supplier);
        }

        if ($supplier->licenses_count > 0) {
            throw new ItemStillHasLicenses($supplier);
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
