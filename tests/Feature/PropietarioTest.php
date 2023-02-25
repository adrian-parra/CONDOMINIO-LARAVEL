<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropietarioTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_propiedad()
    {
        $response = $this->get('/api/v1/propietario');
        $data = $response->json()['data'];

        $this->assertNotEmpty($data);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/propietario/1');
        $data = $response->json()['data'];

        $this->assertNotEmpty($data);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/propietario/1?incluirPropiedades=true');
        $data = $response->json()['data'];

        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['propiedad']);
        $response->assertStatus(200);
    }
}
