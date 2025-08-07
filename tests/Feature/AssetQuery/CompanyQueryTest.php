<?php
namespace Tests\Unit;

use App\Models\Company;
use App\Models\Asset;
use App\Models\AssetModel;
use Tests\TestCase;


class CompanyQueryTest extends TestCase
{
    public function testFilterAssetCompanyEmptyString()
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        // Assets mit direkter company_id
        $assetA = Asset::factory()->create([
            'company_id' => $companyA->id,
        ]);
        $assetB = Asset::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $filter = ['company' => ''];
        dump(Asset::query()->get()->all());
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertTrue($results->contains($assetB));
    }


    public function testFilterAssetCompanyStringComplete()
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        // Assets mit direkter company_id
        $assetA = Asset::factory()->create([
            'company_id' => $companyA->id,
        ]);
        $assetB = Asset::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $filter = ['company' => $companyA->name];
        dump(Asset::query()->get()->all());
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));
    }

    public function testFilterAssetCompanyStringPartial()
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        // Assets mit direkter company_id
        $assetA = Asset::factory()->create([
            'company_id' => $companyA->id,
        ]);
        $assetB = Asset::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $queryString = substr($companyA->name, 0, floor(strlen($companyA->name) / 2));
        $filter = ['company' => $queryString];
        dump(Asset::query()->get()->all());
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));
    }

    public function testFilterAssetCompanyArraySingle()
    {

        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();


        // Assets mit direkter company_id
        $assetA = Asset::factory()->create([
            'company_id' => $companyA->id,
        ]);
        $assetB = Asset::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $filter = ['company' => [$companyA->name]];
        dump(Asset::query()->get()->all());
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($assetA));
        $this->assertFalse($results->contains($assetB));

    }

    public function testFilterAssetCompanyArrayMultiple()
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();
        $companyC = Company::factory()->create();
        $companyD = Company::factory()->create();
        $companyE = Company::factory()->create();

        // Assets mit direkter company_id
        $assetA = Asset::factory()->create([
            'company_id' => $companyA->id,
        ]);
        $assetB = Asset::factory()->create([
            'company_id' => $companyB->id,
        ]);
        $assetC = Asset::factory()->create([
            'company_id' => $companyC->id,
        ]);
        $assetD = Asset::factory()->create([
            'company_id' => $companyD->id,
        ]);
        $assetE = Asset::factory()->create([
            'company_id' => $companyE->id,
        ]);

        $filter = ['company' => [$companyB->name, $companyE->name]];
        dump(Asset::query()->get()->all());
        $results = Asset::query()->byFilter($filter)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($assetB));
        $this->assertTrue($results->contains($assetE));
        $this->assertFalse($results->contains($assetA));
        $this->assertFalse($results->contains($assetC));
        $this->assertFalse($results->contains($assetD));

    }
}