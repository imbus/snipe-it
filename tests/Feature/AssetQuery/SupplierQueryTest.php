<?php
namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Supplier;
use Tests\TestCase;


class SupplierQueryTest extends TestCase
{
    public function testFilterAssetSupplierEmptyString()
    {
        $supplierA = Supplier::factory()->create();
        $supplierB = Supplier::factory()->create();

        $assetA = Asset::factory()->create([
            'supplier_id' => $supplierA->id,
        ]);
        $assetB = Asset::factory()->create([
            'supplier_id' => $supplierB->id,
        ]);

        $filter = ['supplier' => ''];
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertTrue($results->contains($assetB));
    }


    public function testFilterAssetSupplierStringComplete()
    {
        $supplierA = Supplier::factory()->create();
        $supplierB = Supplier::factory()->create();

        $assetA = Asset::factory()->create([
            'supplier_id' => $supplierA->id,
        ]);
        $assetB = Asset::factory()->create([
            'supplier_id' => $supplierB->id,
        ]);

        $filter = ['supplier' => $supplierA->name];
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));
    }

    public function testFilterAssetSupplierStringPartial()
    {
        $supplierA = Supplier::factory()->create();
        $supplierB = Supplier::factory()->create();

        $assetA = Asset::factory()->create([
            'supplier_id' => $supplierA->id,
        ]);
        $assetB = Asset::factory()->create([
            'supplier_id' => $supplierB->id,
        ]);

        $queryString = substr($supplierA->name, 0, floor(strlen($supplierA->name) / 2));
        $filter = ['supplier' => $queryString];
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));
    }

    public function testFilterAssetSupplierArraySingle()
    {

        $supplierA = Supplier::factory()->create();
        $supplierB = Supplier::factory()->create();


        $assetA = Asset::factory()->create([
            'supplier_id' => $supplierA->id,
        ]);
        $assetB = Asset::factory()->create([
            'supplier_id' => $supplierB->id,
        ]);

        $filter = ['supplier' => [$supplierA->name]];
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));

    }

    public function testFilterAssetSupplierArrayMultiple()
    {
        $supplierA = Supplier::factory()->create();
        $supplierB = Supplier::factory()->create();
        $supplierC = Supplier::factory()->create();
        $supplierD = Supplier::factory()->create();
        $supplierE = Supplier::factory()->create();

        $assetA = Asset::factory()->create([
            'supplier_id' => $supplierA->id,
        ]);
        $assetB = Asset::factory()->create([
            'supplier_id' => $supplierB->id,
        ]);
        $assetC = Asset::factory()->create([
            'supplier_id' => $supplierC->id,
        ]);
        $assetD = Asset::factory()->create([
            'supplier_id' => $supplierD->id,
        ]);
        $assetE = Asset::factory()->create([
            'supplier_id' => $supplierE->id,
        ]);

        $filter = ['supplier' => [$supplierB->name, $supplierE->name]];
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($assetB));
        $this->assertTrue($results->contains($assetE));
        $this->assertFalse($results->contains($assetA));
        $this->assertFalse($results->contains($assetC));
        $this->assertFalse($results->contains($assetD));

    }
}