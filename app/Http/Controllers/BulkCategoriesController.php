<?php

namespace App\Http\Controllers;

use App\Actions\Categories\DestroyCategoryAction;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasLicenses;
use Illuminate\Http\Request;

class BulkCategoriesController extends Controller
{
    public function destroy($ids)
    {
        $errors = [];
        foreach ($ids as $id) {
            try {
                DestroyCategoryAction::run(category: $id);
            } catch (ModelStillHasAssets $e) {
                $errors[] = `{$id} still has assets`;
            } catch (ModelStillHasAssetMaintenances $e) {
                $errors[] = `{$id} still has asset maintenances`;
            } catch (ModelStillHasLicenses $e) {
                $errors[] = `{$id} still has licenses`;
            } catch (\Exception $e) {
                report($e);
                $errors[] = 'Something went wrong';
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('categories.index')->with('error', implode(', ', $errors));
        } else {
            return redirect()->route('categories.index')->with('success', trans('admin/suppliers/message.delete.success'));
        }
    }
}
