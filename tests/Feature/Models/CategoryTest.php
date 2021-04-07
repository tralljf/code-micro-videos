<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();

        $categories = Category::all();
        $this->assertCount(1, $categories);

        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ], $categoryKey);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'Teste 1'
        ]);
        $category->refresh();

        $this->assertEquals('Teste 1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);


        $category = Category::create([
            'name' => 'Teste 1',
            'description' => null
        ]);

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'Teste 1',
            'description' => 'description'
        ]);

        $this->assertEquals('description', $category->description);


        $category = Category::create([
            'name' => 'Teste 1',
            'description' => 'description',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);

    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'teste description',
            'is_active' => false
        ]);

        $data = [
            'name' => 'test_name_update',
            'description' => 'teste_description_update',
            'is_active' => true
        ];

        $category->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value, $category->{$key});
        }
        $this->assertTrue($category->is_active);

    }


    public function testDelete()
    {
        $category = Category::create([
            'name' => 'teste 1'
        ]);

        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));

    }

    public function testUuid()
    {
        $category = Category::create([
            'name' => 'teste 1'
        ]);

        $regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
        $this->assertTrue((bool) preg_match($regex, $category->id));

    }
}
