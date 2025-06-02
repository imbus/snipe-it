<?php

namespace App\Importer;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

/**
 * When we are importing users via an Asset/etc import, we use createOrFetchUser() in
 * Importer\Importer.php. [ALG]
 *
 * Class CategoryImporter
 */
class CategoryImporter extends ItemImporter
{
    protected $categories;

    public function __construct($filename)
    {
        parent::__construct($filename);
    }

    protected function handle($row)
    {
        parent::handle($row);
        $this->createCategoryIfNotExists($row);
    }

    /**
     * Create a supplier if a duplicate does not exist.
     * @todo Investigate how this should interact with Importer::createCategoryIfNotExists
     *
     * @author A. Gianotto
     * @since 6.1.0
     * @param array $row
     */
    public function createCategoryIfNotExists(array $row)
    {

        $editingCategory = false;

        $supplier = Category::where('name', '=', $this->findCsvMatch($row, 'name'))->first();

        if ($this->findCsvMatch($row, 'id')!='') {
            // Override supplier if an ID was given
            \Log::debug('Finding supplier by ID: '.$this->findCsvMatch($row, 'id'));
            $supplier = Category::find($this->findCsvMatch($row, 'id'));
        }


        if ($supplier) {
            if (! $this->updating) {
                $this->log('A matching Category '.$this->item['name'].' already exists');
                return;
            }

            $this->log('Updating Category');
            $editingCategory = true;
        } else {
            $this->log('No Matching Category, Create a new one');
            $supplier = new Category;
            $supplier->created_by = auth()->id();
        }

        // Pull the records from the CSV to determine their values
        $this->item['name'] = trim($this->findCsvMatch($row, 'name'));
        $this->item['notes'] = trim($this->findCsvMatch($row, 'notes'));
        $this->item['eula_text'] = trim($this->findCsvMatch($row, 'eula_text'));
        $this->item['category_type'] = trim($this->findCsvMatch($row, 'category_type'));
        $this->item['use_default_eula'] = trim(($this->fetchHumanBoolean($this->findCsvMatch($row, 'use_default_eula'))) == 1) ? 1 : 0;
        $this->item['require_acceptance'] = trim(($this->fetchHumanBoolean($this->findCsvMatch($row, 'require_acceptance'))) == 1) ? 1 : 0;
        $this->item['checkin_email'] = trim(($this->fetchHumanBoolean($this->findCsvMatch($row, 'checkin_email'))) == 1) ? 1 : 0;


        Log::debug('Item array is: ');
        Log::debug(print_r($this->item, true));


        if ($editingCategory) {
            Log::debug('Updating existing supplier');
            $supplier->update($this->sanitizeItemForUpdating($supplier));
        } else {
            Log::debug('Creating supplier');
            $supplier->fill($this->sanitizeItemForStoring($supplier));
        }

        if ($supplier->save()) {
            $this->log('Category '.$supplier->name.' created or updated from CSV import');
            return $supplier;

        } else {
            Log::debug($supplier->getErrors());
            $this->logError($supplier, 'Category "'.$this->item['name'].'"');
            return $supplier->errors;
        }


    }
}