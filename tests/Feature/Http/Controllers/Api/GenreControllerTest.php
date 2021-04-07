<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.show', [ 'genre' => $genre->id ]));

        $response
            ->assertStatus(200)
             ->assertJson($genre->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('genres.store', []));


        $this->asssertInvalidationRequired($response);

        $response = $this->json('POST', route('genres.store', [
            'name' => str_repeat('x', 256)
        ]));

        $this->asssertInvalidationMax($response);


        $response = $this->json('POST', route('genres.store', [
            'is_active' => 'oi'
        ]));
        $this->asssertInvalidationIsActive($response);



        $genre = factory(Genre::class)->create();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id]), []);

        $this->asssertInvalidationRequired($response);


        $response = $this->json('POST', route('genres.store', [
            'name' => str_repeat('x', 256)
        ]));

        $this->asssertInvalidationMax($response);


        $response = $this->json('POST', route('genres.store', [
            'is_active' => 'oi'
        ]));
        $this->asssertInvalidationIsActive($response);
    }

    public function testStore(){
        $response = $this->json('POST', route('genres.store', [
            'name' => 'test'
        ]));

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genre->toArray());

        $this->assertTrue($response->json('is_active'));

        $response = $this->json('POST', route('genres.store', [
            'name' => 'test 2',
            'is_active' => false
        ]));

        $response
            ->assertJsonFragment([
                'name' => 'test 2',
                'is_active' => false
            ]);
    }


    public function testUpdate (){
        $genre =  factory(Genre::class)->create([
            'is_active' => false
        ]);
        $response = $this->json('PUT',
            route('genres.update', ['genre' => $genre->id]),
            [
                'name' => 'test',
                'is_active' => true
            ]
        );

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'name' => 'test',
                'is_active' => true
            ]);
    }

    protected function asssertInvalidationRequired(TestResponse $response){
        $response
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function asssertInvalidationMax(TestResponse $response){
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function asssertInvalidationIsActive(TestResponse $response){
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }
}
