<?php

namespace App\Actions\Categories;

use App\Exceptions\ModelIsNotDeletable;
use App\Exceptions\ModelStillHasAccessories;
use App\Exceptions\ModelStillHasAssetModels;
use App\Exceptions\ModelStillHasAssets;
use App\Exceptions\ModelStillHasComponents;
use App\Exceptions\ModelStillHasConsumables;
use App\Exceptions\ModelStillHasLicenses;
use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class DestroyCategoryAction
{
    /**
     * @throws ModelStillHasAssets
     * @throws ModelStillHasAssetModels
     * @throws ModelStillHasComponents
     * @throws ModelStillHasAccessories
     * @throws ModelStillHasLicenses
     * @throws ModelStillHasConsumables
     */
    static function run(Category $category): bool
    {
        $category->loadCount([
            'assets as assets_count',
            'accessories as accessories_count',
            'consumables as consumables_count',
            'components as components_count',
            'licenses as licenses_count',
            'models as models_count'
        ]);

        if ($category->assets_count > 0) {
            throw new ModelStillHasAssets($category);
        }
        if ($category->accessories_count > 0) {
            throw new ModelStillHasAccessories($category);
        }
        if ($category->consumables_count > 0) {
            throw new ModelStillHasConsumables($category);
        }
        if ($category->components_count > 0) {
            throw new ModelStillHasComponents($category);
        }
        if ($category->licenses_count > 0) {
            throw new ModelStillHasLicenses($category);
        }
        if ($category->models_count > 0) {
            throw new ModelStillHasAssetModels($category);
        }

        Storage::disk('public')->delete('categories'.'/'.$category->image);
        $category->delete();

        return true;
    }
}