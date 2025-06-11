<?php

namespace App\Actions\Categories;

use App\Exceptions\ModelIsNotDeletable;
use App\Helpers\Helper;
use App\Models\Category;

class DestroyCategoryAction
{
    static function run(Category $category): bool
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

        // this should give better errors, do we throw down in the model for this one, or move that logic up here?
        // one of those fat-model vs action things...
        if (!$category->isDeletable()) {
            throw new ModelIsNotDeletable($category);
        }
        $category->delete();

        return true;
    }
}