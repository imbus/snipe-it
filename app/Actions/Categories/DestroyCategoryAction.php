<?php

namespace App\Actions\Categories;

use App\Helpers\Helper;
use App\Models\Category;

class DestroyCategoryAction
{
    static function run(Category $category)
    {
        // why do we need to do this?
        // hm,
        $category->loadCount([
            'assets as assets_count',
            'accessories as accessories_count',
            'consumables as consumables_count',
            'components as components_count',
            'licenses as licenses_count',
            'models as models_count'
        ]);

        if (!$category->isDeletable()) {
            return response()->json(
                Helper::formatStandardApiResponse('error', null, trans('admin/categories/message.assoc_items', ['asset_type' => $category->category_type]))
            );
        }
        $category->delete();
    }
}