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
use Illuminate\Support\MessageBag;

class BulkManufacturersController extends Controller
{
    public function destroy(Request $request)
    {
        $this->authorize('delete', Manufacturer::class);

        $errors = new MessageBag();
        foreach ($request->ids as $id) {
            $manufacturer = Manufacturer::find($id);
            if (is_null($manufacturer)) {
                $errors[] = trans('admin/manufacturers/message.delete.not_found');
                continue;
            }
            try {
                DeleteManufacturerAction::run(manufacturer: $manufacturer);
            } catch (ItemStillHasAssets $e) {
                $errors->add('error', trans('admin/manufacturers/message.delete.bulk_assoc_assets', ['manufacturer_name' => $manufacturer->name]));
            } catch (ItemStillHasAccessories $e) {
                $errors->add('error', trans('admin/manufacturers/message.delete.bulk_assoc_accessories', ['manufacturer_name' => $manufacturer->name]));
            } catch (ItemStillHasConsumables $e) {
                $errors->add('error', trans('admin/manufacturers/message.delete.bulk_assoc_consumables', ['manufacturer_name' => $manufacturer->name]));
            } catch (ItemStillHasComponents $e) {
                $errors->add('error', trans('admin/manufacturers/message.delete.bulk_assoc_components', ['manufacturer_name' => $manufacturer->name]));
            } catch (ItemStillHasLicenses $e) {
                $errors->add('error', trans('admin/manufacturers/message.delete.bulk_assoc_licenses', ['manufacturer_name' => $manufacturer->name]));
            } catch (\Exception $e) {
                report($e);
                $errors->add('error', trans('general.something_went_wrong'));
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('manufacturers.index')->with('multi_error_messages', $errors);
        } else {
            return redirect()->route('manufacturers.index')->with('success', trans('admin/manufacturers/message.delete.bulk_success'));
        }
    }
}
