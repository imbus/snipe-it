<?php

namespace App\Http\Controllers;

use App\Actions\Suppliers\DestroySupplierAction;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasLicenses;
use Illuminate\Http\Request;

class BulkSuppliersController extends Controller
{
    public function destroy($ids)
    {
        $errors = [];
        foreach ($ids as $id) {
            try {
                DestroySupplierAction::run($id);
            } catch (ModelStillHasAssets $e) {
                $errors[] = `{$id} still has assets`;
            } catch (ModelStillHasAssetMaintenances $e) {
                $errors[] = `{$id} still has asset maintenances`;
            } catch (ModelStillHasLicenses $e) {
                $errors[] = `{$id} still has licenses`;
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('suppliers.index')->with('error', implode(', ', $errors));
        } else {
            return redirect()->route('suppliers.index')->with('success', trans('admin/suppliers/message.delete.success'));
        }
    }
}
