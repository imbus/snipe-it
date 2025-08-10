<?php

namespace App\Http\Controllers;

use App\Actions\Suppliers\DestroySupplierAction;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasLicenses;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BulkSuppliersController extends Controller
{
    public function destroy(Request $request)
    {
        // Authorize the user to delete suppliers
        $this->authorize('delete', Supplier::class);

        $errors = [];
        foreach ($request->ids as $id) {
            $supplier = Supplier::find($id);
            if (is_null($supplier)) {
                $errors[] = 'Supplier not found';
                continue;
            }
            try {
                DestroySupplierAction::run(supplier: $supplier);
            } catch (ModelStillHasAssets $e) {
                $errors[] = "{$supplier->name} still has assets";
            } catch (ModelStillHasAssetMaintenances $e) {
                $errors[] = "{$supplier->name} still has asset maintenances";
            } catch (ModelStillHasLicenses $e) {
                $errors[] = "{$supplier->name} still has licenses";
            } catch (\Exception $e) {
                report($e);
                $errors[] = 'Something went wrong';
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('suppliers.index')->with('error', implode(', ', $errors));
        } else {
            return redirect()->route('suppliers.index')->with('success', trans('admin/suppliers/message.delete.success'));
        }
    }
}
