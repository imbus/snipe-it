<?php

namespace App\Http\Controllers;

use App\Actions\Categories\DestroyCategoryAction;
use App\Exceptions\ModelStillHasAccessories;
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
        $this->authorize('delete', Category::class);

        $errors = [];
        foreach ($request->ids as $id) {
            $category = Category::find($id);
            if (is_null($category)) {
                $errors[] = trans('admin/categories/message.delete.not_found');
                continue;
            }
            try {
                DestroyCategoryAction::run(category: $category);
            } catch (ModelStillHasAccessories $e) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_accessories', ['category_name' => $category->name]);
            } catch (ModelStillHasAssetModels) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_models', ['category_name' => $category->name]);
            } catch (ModelStillHasAssets) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_assets', ['category_name' => $category->name]);
            } catch (ModelStillHasComponents) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_components', ['category_name' => $category->name]);
            } catch (ModelStillHasConsumables) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_consumables', ['category_name' => $category->name]);
            } catch (ModelStillHasLicenses) {
                $errors[] = trans('admin/categories/message.delete.bulk_assoc_licenses', ['category_name' => $category->name]);
            } catch (\Exception $e) {
                report($e);
                $errors[] = trans('general.something_went_wrong');
            }
        }
        if (count($errors) > 0) {
            return redirect()->route('categories.index')->with('multi_error_messages', $errors);
        } else {
            return redirect()->route('categories.index')->with('success', trans('admin/categories/message.delete.bulk_success'));
        }
    }
}
