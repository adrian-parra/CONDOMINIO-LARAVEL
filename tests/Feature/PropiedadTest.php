<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropiedadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_propiedad()
    {
        $response = $this->get('/api/v1/propiedad');

        $response->assertStatus(200);

        $response = $this->get('/api/v1/propiedad/1');

        $response->assertStatus(200);
    }
}
