<?php

return array(

    'does_not_exist' => 'Category does not exist.',
    'assoc_models'	 => 'This category is currently associated with at least one model and cannot be deleted. Please update your models to no longer reference this category and try again. ',
    'assoc_items'	 => 'This category is currently associated with at least one :asset_type and cannot be deleted. Please update your :asset_type  to no longer reference this category and try again. ',

    'create' => array(
        'error'   => 'Category was not created, please try again.',
        'success' => 'Category created successfully.'
    ),

    'update' => array(
        'error'   => 'Category was not updated, please try again',
        'success' => 'Category updated successfully.',
        'cannot_change_category_type'   => 'You cannot change the category type once it has been created',
    ),

    'delete' => array(
        'not_found'              => 'Category not found.',
        'confirm'                => 'Are you sure you wish to delete this category?',
        'error'                  => 'There was an issue deleting the category. Please try again.',
        'success'                => 'The category was deleted successfully.',
        'bulk_success'           => 'The Categories were deleted successfully.',
        'bulk_assoc_assets'      => ':category_name still has associated assets and cannot be deleted. Please update your assets to no longer reference this supplier and try again.',
        'bulk_assoc_accessories' => ':category_name still has associated accessories and cannot be deleted. Please update your accessories to no longer reference this supplier and try again.',
        'bulk_assoc_consumables' => ':category_name still has associated consumables and cannot be deleted. Please update your consumables to no longer reference this supplier and try again.',
        'bulk_assoc_components'  => ':category_name still has associated components and cannot be deleted. Please update your components to no longer reference this supplier and try again.',
        'bulk_assoc_licenses'    => ':category_name still has associated licenses and cannot be deleted. Please update your licenses to no longer reference this supplier and try again.',
        'bulk_assoc_models'      => ':category_name still has associated asset models and cannot be deleted. Please update your asset models to no longer reference this supplier and try again.',
    )

);
