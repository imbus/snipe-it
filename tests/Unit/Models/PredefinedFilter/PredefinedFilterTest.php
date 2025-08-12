<?php
namespace Tests\Unit\Models\PredefinedFilter;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Statuslabel;
use Database\Seeders\AssetSeeder;
use App\Models\Category;
use App\Models\PredefinedFilter;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PredefinedFilterTest extends TestCase {

  use RefreshDatabase;

  protected \Illuminate\Support\Collection $assets;
  protected User $user;

  protected function setUp(): void {
    parent::setUp();

    $user = User::create([
      'first_name' => 'Filter',
      'last_name'=> 'Predefined',
      'username' => 'filter',
      'email'=> 'predefined@filter.com',
      'password'=> Hash::make('filterfilter'),
    ]);

    $category = Category::create([
      'name' => 'category_for_test',
      'category_type' => 'asset',
      'checkin_email' => true,
      'created_by' => $user->id,
    ]);

    $assetModel = AssetModel::create([
      'created_by' => $user,
      'name' => 'asset_model_for_test',
      'category_id' => $category->id,
    ]);

    $statuslabel = Statuslabel::create([
      'name'      => 'statusLabel_for_test',
      'created_at' => '2025-08-12',
      'updated_at' => '2025-08-13',
      'created_by' => $user->id,
      'deleted_at' => null,
      'deployable' => 1,
      'pending' => 0,
      'archived' => 0,
    ]);

    Asset::create([
      "name"=> "asset_for_test01",
      "model_id"=> $assetModel->id,
      "status_id" => $statuslabel->id,
      'asset_tag' => 12312312301,
      "created_at" => "2025-08-10",
    ]);
    Asset::create([
      "name"=> "asset_for_test02",
      "model_id"=> $assetModel->id,
      "status_id" => $statuslabel->id,
      'asset_tag' => 12312312302,
      "created_at" => "2025-08-12",
    ]);
    Asset::create([
      "name"=> "asset_for_test03",
      "model_id"=> $assetModel->id,
      "status_id" => $statuslabel->id,
      'asset_tag' => 12312312303,
      "created_at" => "2025-08-12",
    ]);

    $this->user = $user;
    $this->assets = Asset::all();
  }

  public function testSetUp() {
    $this->assertCount(3, $this->assets);
  }

  public function testCreatedStart() {
    $predefinedFilter = PredefinedFilter::create([
      'name' => 'test_created_start',
      'created_by' => $this->user->id,
      'created_start' => '2025-08-11',
    ]);


    // $this->asserCount(2, TODO);
  }



  public function testFilterCompany() {
    Company::factory()->count(1)->create();
    $companies = Company::all();
    $this->assertCount(1, $companies);
  }

  public function testFilterCategory() {
    Category::factory()->count(1)->create();
    $categories = Category::all();
    $this->assertCount(1, $categories);

    PredefinedFilter::factory()->category()->create();
    $predefinedFilterCategory = PredefinedFilter::all();
    $this->assertCount(1, $predefinedFilterCategory);


  }


}
