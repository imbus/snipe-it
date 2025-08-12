<?php

namespace App\Http\Controllers;

use App\Actions\Manufacturers\DeleteManufacturerAction;
use App\Exceptions\ModelStillHasAccessories;
use App\Exceptions\ModelStillHasAssetModels;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasChildren;
use App\Exceptions\ModelStillHasComponents;
use App\Exceptions\ModelStillHasConsumables;
use App\Exceptions\ModelStillHasLicenses;
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
                $errors[] = trans('admin/manufacturers/message.delete.not_found');
                continue;
            }
            try {
                DeleteManufacturerAction::run(manufacturer: $manufacturer);
            } catch (ModelStillHasAssets $e) {
                $errors[] = trans('admin/manufacturers/message.delete.bulk_assoc_assets', ['manufacturer_name' => $manufacturer->name]);
            } catch (ModelStillHasAccessories $e) {
                $errors[] = trans('admin/manufacturers/message.delete.bulk_assoc_accessories', ['manufacturer_name' => $manufacturer->name]);
            } catch (ModelStillHasConsumables $e) {
                $errors[] = trans('admin/manufacturers/message.delete.bulk_assoc_consumables', ['manufacturer_name' => $manufacturer->name]);
            } catch (ModelStillHasComponents $e) {
                $errors[] = trans('admin/manufacturers/message.delete.bulk_assoc_components', ['manufacturer_name' => $manufacturer->name]);;
            } catch (ModelStillHasLicenses $e) {
                $errors[] = trans('admin/manufacturers/message.delete.bulk_assoc_licenses', ['manufacturer_name' => $manufacturer->name]);;
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
