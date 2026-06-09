<?php

namespace Tests\Feature\Frontend;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class PublicPropertyTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedLanguages();
    }

    // ─── Property List ────────────────────────────────────────────────────────

    /** @test */
    public function public_can_view_property_listing(): void
    {
        Property::factory()->count(3)->create(['status' => 'active']);

        $response = $this->get('/properties');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Public/Properties/Index')
            ->has('properties')
        );
    }

    /** @test */
    public function inactive_properties_are_not_listed(): void
    {
        Property::factory()->count(2)->create(['status' => 'active']);
        Property::factory()->count(3)->create(['status' => 'inactive']);

        $response = $this->get('/properties');

        $response->assertInertia(fn ($p) => $p->where('properties.total', 2));
    }

    /** @test */
    public function properties_can_be_filtered_by_type(): void
    {
        Property::factory()->count(3)->create(['type' => 'sale', 'status' => 'active']);
        Property::factory()->count(2)->create(['type' => 'rent', 'status' => 'active']);

        $response = $this->get('/properties?type=sale');

        $response->assertInertia(fn ($p) => $p->where('properties.total', 3));
    }

    /** @test */
    public function properties_can_be_filtered_by_city(): void
    {
        Property::factory()->count(4)->create(['city' => 'Istanbul', 'status' => 'active']);
        Property::factory()->count(2)->create(['city' => 'Ankara',   'status' => 'active']);

        $response = $this->get('/properties?city=Istanbul');

        $response->assertInertia(fn ($p) => $p->where('properties.total', 4));
    }

    /** @test */
    public function properties_can_be_filtered_by_price_range(): void
    {
        Property::factory()->create(['price' => 50000,  'status' => 'active']);
        Property::factory()->create(['price' => 250000, 'status' => 'active']);
        Property::factory()->create(['price' => 800000, 'status' => 'active']);

        $response = $this->get('/properties?min_price=100000&max_price=500000');

        $response->assertInertia(fn ($p) => $p->where('properties.total', 1));
    }

    // ─── Property Detail ──────────────────────────────────────────────────────

    /** @test */
    public function public_can_view_active_property_detail(): void
    {
        $slug     = 'my-villa-' . uniqid();
        $property = Property::factory()->create(['status' => 'active', 'slug' => $slug]);

        // Factory no longer auto-creates translations — create manually
        $property->translations()->create([
            'locale'      => 'en',
            'title'       => 'My Villa',
            'description' => 'A lovely villa.',
        ]);

        $response = $this->get("/properties/{$slug}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Public/Properties/Show')
            ->has('property')
        );
    }

    /** @test */
    public function viewing_a_property_increments_views_count(): void
    {
        $slug     = 'view-count-' . uniqid();
        $property = Property::factory()->create([
            'status'      => 'active',
            'slug'        => $slug,
            'views_count' => 0,
        ]);
        $property->translations()->create([
            'locale'      => 'en',
            'title'       => 'Test Property',
            'description' => 'Description.',
        ]);

        $this->get("/properties/{$slug}");

        $this->assertDatabaseHas('properties', [
            'id'          => $property->id,
            'views_count' => 1,
        ]);
    }

    /** @test */
    public function nonexistent_property_returns_404(): void
    {
        $response = $this->get('/properties/does-not-exist');

        $response->assertStatus(404);
    }

    /** @test */
    public function property_detail_includes_similar_properties(): void
    {
        $slug     = 'main-prop-' . uniqid();
        $property = Property::factory()->create([
            'status' => 'active',
            'slug'   => $slug,
            'type'   => 'sale',
            'city'   => 'Istanbul',
        ]);
        $property->translations()->create([
            'locale'      => 'en',
            'title'       => 'Main Property',
            'description' => 'Description.',
        ]);

        // Create similar properties (no translations needed for listing)
        Property::factory()->count(3)->create([
            'status' => 'active',
            'type'   => 'sale',
            'city'   => 'Istanbul',
        ]);

        $response = $this->get("/properties/{$slug}");

        $response->assertInertia(fn ($p) => $p->has('similar'));
    }
}
