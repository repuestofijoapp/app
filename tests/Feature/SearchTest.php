<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Livewire\SearchComponents\MainSearch;
use Livewire\Livewire;
use App\Models\Vehicle;
use App\Models\OemProduct;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic categories
        $category = \App\Models\Category::create(['name' => 'Frenos', 'slug' => 'frenos']);
        $sub = \App\Models\Category::create(['name' => 'Pastillas de freno', 'slug' => 'pastillas', 'parent_id' => $category->id]);

        Vehicle::create([
            'plate' => 'ABC123',
            'brand' => 'HONDA',
            'model' => 'PILOT',
            'year' => 2011,
            'engine_code' => 'J35Z'
        ]);

        OemProduct::create([
            'oem_code' => '90915-YZZF1',
            'name' => 'Filtro de Aceite Toyota',
            'category_id' => $sub->id,
            'description' => 'Test product',
            'common_brands' => ['Toyota']
        ]);
    }
    /** @test */
    public function it_can_search_by_plate()
    {
        Livewire::test(MainSearch::class)
            ->set('plateSearch', 'ABC123')
            ->call('performSearch', 'plate')
            ->assertSet('viewState', 'vehicle_found')
            ->assertSet('vehicle.plate', 'ABC123');
    }

    /** @test */
    public function it_can_search_by_oem()
    {
        Livewire::test(MainSearch::class)
            ->set('oemSearch', '90915-YZZF1')
            ->call('performSearch', 'oem')
            ->assertSet('viewState', 'oem_found')
            ->assertSee('Filtro de Aceite Toyota');
    }

    /** @test */
    public function it_shows_error_when_plate_not_found()
    {
        Livewire::test(MainSearch::class)
            ->set('plateSearch', 'NOTEXIST')
            ->call('performSearch', 'plate')
            ->assertDispatched('notify');
    }
}
