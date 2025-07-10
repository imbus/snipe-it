<?php

namespace App\Http\Controllers;

use App\Actions\Manufacturers\DeleteManufacturerAction;
use App\Exceptions\ModelStillHasChildren;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class BulkManufacturersController extends Controller
{
    public function destroy(Request $request)
    {
        // hm, we actually probably need to do this on a per model basis below, but that makes this a little dirtier so leaving like this for now.
        $this->authorize('delete', Manufacturer::class);

        $errors = [];
        foreach ($request->ids as $id) {
            $manufacturer = Manufacturer::find($id);
            if (is_null($manufacturer)) {
                $errors[] = 'Manufacturer not found';
                continue;
            }
            try {
                DeleteManufacturerAction::run(manufacturer: $manufacturer);
            } catch (ModelStillHasChildren $e) {
                $errors[] = `{$manufacturer->name} still has models`;
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
