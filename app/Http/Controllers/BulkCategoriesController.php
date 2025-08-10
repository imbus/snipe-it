<?php

namespace App\Http\Controllers;

use App\Actions\Categories\DestroyCategoryAction;
use App\Exceptions\ModelStillHasAccessories;
use App\Exceptions\ModelStillHasAssetMaintenances;
use App\Exceptions\ModelStillHasAssetModels;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasComponents;
use App\Exceptions\ModelStillHasConsumables;
use App\Exceptions\ModelStillHasLicenses;
use App\Models\Category;
use Illuminate\Http\Request;

class BulkCategoriesController extends Controller
{
    public function destroy(Request $request)
    {
        // Authorize the user to delete categories
        $this->authorize('delete', Category::class);

        $errors = [];
        foreach ($request->ids as $id) {
            $category = Category::find($id);
            if (is_null($category)) {
                $errors[] = 'Category not found';
                continue;
            }
            try {
                DestroyCategoryAction::run(category: $category);
            } catch (ModelStillHasAccessories $e) {
                $errors[] = "{$category->name} still has associated items";
            } catch (ModelStillHasAssetModels) {
                $errors[] = "{$category->name} still has asset models";
            } catch (ModelStillHasAssets) {
                $errors[] = "{$category->name} still has assets";
            } catch (ModelStillHasComponents) {
                $errors[] = "{$category->name} still has components";
            } catch (ModelStillHasConsumables) {
                $errors[] = "{$category->name} still has consumables";
            } catch (ModelStillHasLicenses) {
                $errors[] = "{$category->name} still has licenses";
            } catch (\Exception $e) {
                report($e);
                $errors[] = 'Something went wrong';
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('categories.index')->with('error', implode(', ', $errors));
        } else {
            return redirect()->route('categories.index')->with('success', trans('admin/categories/message.delete.success'));
        }
    }
}
