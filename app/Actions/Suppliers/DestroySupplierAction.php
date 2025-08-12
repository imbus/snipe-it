<?php

namespace App\Actions\Suppliers;

use App\Models\Supplier;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasMaintenances;
use App\Exceptions\ModelStillHasLicenses;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestroySupplierAction
{
    /**
     * @throws ModelStillHasLicenses
     * @throws ModelStillHasAssets
     * @throws ModelStillHasMaintenances
     */
    static function run(Supplier $supplier): bool
    {
        $supplier->loadCount([
            'maintenances as maintenances_count',
            'assets as assets_count',
            'licenses as licenses_count'
        ]);
        if ($supplier->assets_count > 0) {
            throw new ModelStillHasAssets($supplier);
        }

        if ($supplier->maintenances_count > 0) {
            throw new ModelStillHasMaintenances($supplier);
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
