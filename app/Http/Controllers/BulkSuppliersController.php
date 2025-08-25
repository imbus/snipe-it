<?php

namespace App\Http\Controllers;

use App\Actions\Suppliers\DestroySupplierAction;
use App\Exceptions\ItemStillHasMaintenances;
use App\Exceptions\ItemStillHasAssets;
use App\Exceptions\ItemStillHasLicenses;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class BulkSuppliersController extends Controller
{
    public function destroy(Request $request)
    {
        $this->authorize('delete', Supplier::class);

        $errors = new MessageBag();
        foreach ($request->ids as $id) {
            $supplier = Supplier::find($id);
            if (is_null($supplier)) {
                $errors[] = trans('admin/suppliers/message.delete.not_found');
                continue;
            }
            try {
                DestroySupplierAction::run(supplier: $supplier);
            } catch (ItemStillHasAssets $e) {
                $errors->add('error', trans('admin/suppliers/message.delete.bulk_assoc_assets', ['supplier_name' => $supplier->name]));
            } catch (ItemStillHasMaintenances $e) {
                $errors->add('error', trans('admin/suppliers/message.delete.bulk_assoc_maintenances', ['supplier_name' => $supplier->name]));;
            } catch (ItemStillHasLicenses $e) {
                $errors->add('error', trans('admin/suppliers/message.delete.bulk_assoc_licenses', ['supplier_name' => $supplier->name]));;
            } catch (\Exception $e) {
                report($e);
                $errors->add('error', trans('general.something_went_wrong'));
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('suppliers.index')->with('multi_error_messages', $errors);
        } else {
            return redirect()->route('suppliers.index')->with('success', trans('admin/suppliers/message.delete.bulk_success'));
        }
    }
}
