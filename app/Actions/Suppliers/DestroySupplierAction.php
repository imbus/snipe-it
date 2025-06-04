<?php

namespace App\Actions\Suppliers;

use App\Models\Supplier;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasLicenses;

class DestroySupplierAction
{
    public function run(Supplier $supplier)
    {
        if (is_null($supplier = Supplier::with('asset_maintenances', 'assets', 'licenses')->withCount('asset_maintenances as asset_maintenances_count', 'assets as assets_count', 'licenses as licenses_count')->find($supplierId))) {
            return redirect()->route('suppliers.index')->with('error', trans('admin/suppliers/message.not_found'));
        }

        if ($supplier->assets_count > 0) {
            throw new ModelStillHasAssets($supplier);
        }

        if ($supplier->asset_maintenances_count > 0) {
            throw new ModelStillHasAssetMaintenances($supplier);
        }

        if ($supplier->licenses_count > 0) {
            throw new ModelStillHasLicenses($supplier);
        }

        $supplier->delete();
    }
}
