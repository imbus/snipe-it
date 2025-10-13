<?php

namespace App\Http\Controllers;

use App\Actions\Manufacturers\DeleteManufacturerAction;
use App\Exceptions\ItemStillHasAccessories;
use App\Exceptions\ItemStillHasAssetModels;
use App\Exceptions\ItemStillHasAssets;
use App\Exceptions\ItemStillHasChildren;
use App\Exceptions\ItemStillHasComponents;
use App\Exceptions\ItemStillHasConsumables;
use App\Exceptions\ItemStillHasLicenses;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class BulkManufacturersController extends Controller
{
    public function destroy(Request $request)
    {
        $this->authorize('delete', Manufacturer::class);

        $errors = [];
        foreach ($request->ids as $id) {
            $manufacturer = Manufacturer::find($id);
            if (is_null($manufacturer)) {
                $errors[] = trans('admin/manufacturers/message.does_not_exist');
                continue;
            }
            try {
                DeleteManufacturerAction::run(manufacturer: $manufacturer);
            } catch (ItemStillHasAssets $e) {
                $errors[] = trans('general.bulk_delete_associations.assoc_assets_no_count', ['item_name' => $manufacturer->name, 'item' => trans('general.manufacturer')]);
            } catch (ItemStillHasAccessories $e) {
                $errors[] = trans('general.bulk_delete_associations.assoc_accessories_no_count', ['item_name' => $manufacturer->name, 'item' => trans('general.manufacturer')]);
            } catch (ItemStillHasConsumables $e) {
                $errors[] = trans('general.bulk_delete_associations.assoc_consumables_no_count', ['item_name' => $manufacturer->name, 'item' => trans('general.manufacturer')]);
            } catch (ItemStillHasComponents $e) {
                $errors[] = trans('general.bulk_delete_associations.assoc_components_no_count', ['item_name' => $manufacturer->name, 'item' => trans('general.manufacturer')]);
            } catch (ItemStillHasLicenses $e) {
                $errors[] = trans('general.bulk_delete_associations.assoc_licenses_no_count', ['item_name' => $manufacturer->name, 'item' => trans('general.manufacturer')]);;
            } catch (\Exception $e) {
                report($e);
                $errors[] = trans('general.something_went_wrong');
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('manufacturers.index')->with('multi_error_messages', $errors);
        } else {
            return redirect()->route('manufacturers.index')->with('success', trans('admin/manufacturers/message.delete.bulk_success'));
        }
    }
}
