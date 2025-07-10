<?php

namespace App\Http\Controllers;

use App\Actions\Categories\DestroyCategoryAction;
use App\Actions\Manufacturers\DeleteManufacturerAction;
use App\Exceptions\ModelStillHasAccessories;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasAssetModels;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasComponents;
use App\Exceptions\ModelStillHasConsumables;
use App\Exceptions\ModelStillHasLicenses;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class BulkManufacturersController extends Controller
{
    public function destroy(Request $request)
    {
        $errors = [];
        foreach ($request->ids as $id) {
            try {
                DeleteManufacturerAction::run(manufacturer: $id);
            } catch (ModelStillHasAccessories|ModelStillHasAssets|ModelStillHasComponents|ModelStillHasConsumables|ModelStillHasLicenses $e) {
                $errors[] = `{$id} still has {$id->thing}`;
            } catch (\Exception $e) {
                report($e);
                $errors[] = 'Something went wrong';
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('manufacturers.index')->with('error', implode(', ', $errors));
        } else {
            return redirect()->route('manufacturers.index')->with('success', trans('admin/suppliers/message.delete.success'));
        }
    }
}
