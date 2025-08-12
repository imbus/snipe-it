<?php

return array(

    'support_url_help' => 'Variables <code>{LOCALE}</code>, <code>{SERIAL}</code>, <code>{MODEL_NUMBER}</code>, and <code>{MODEL_NAME}</code> may be used in your URL to have those values auto-populate when viewing assets - for example https://checkcoverage.apple.com/{LOCALE}/{SERIAL}.',
    'does_not_exist' => 'Manufacturer does not exist.',
    'assoc_users'	 => 'This manufacturer is currently associated with at least one model and cannot be deleted. Please update your models to no longer reference this manufacturer and try again. ',

    'create' => array(
        'error'   => 'Manufacturer was not created, please try again.',
        'success' => 'Manufacturer created successfully.'
    ),

    'update' => array(
        'error'   => 'Manufacturer was not updated, please try again',
        'success' => 'Manufacturer updated successfully.'
    ),

    'restore' => array(
        'error'   => 'Manufacturer was not restored, please try again',
        'success' => 'Manufacturer restored successfully.'
    ),

    'delete' => array(
        'not_found'              => 'Manufacturer not found.',
        'confirm' => 'Are you sure you wish to delete this manufacturer?',
        'error'   => 'There was an issue deleting the manufacturer. Please try again.',
        'success'                => 'The Manufacturer was deleted successfully.',
        'bulk_success'           => 'The Manufacturers were deleted successfully.',
        'bulk_assoc_assets'      => ':manufacturer_name still has associated assets and cannot be deleted. Please update your assets to no longer reference this supplier and try again.',
        'bulk_assoc_accessories' => ':manufacturer_name still has associated accessories and cannot be deleted. Please update your accessories to no longer reference this supplier and try again.',
        'bulk_assoc_consumables' => ':manufacturer_name still has associated consumables and cannot be deleted. Please update your consumables to no longer reference this supplier and try again.',
        'bulk_assoc_components'  => ':manufacturer_name still has associated components and cannot be deleted. Please update your components to no longer reference this supplier and try again.',
        'bulk_assoc_licenses'    => ':manufacturer_name still has associated licenses and cannot be deleted. Please update your licenses to no longer reference this supplier and try again.',
    )

);
