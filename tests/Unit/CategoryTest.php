<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $category = new Category();
        $this->assertEquals($fillable,$category->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, \App\Models\Traits\Uuid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testDatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category = new Category();
        foreach($dates as $date){
            $this->assertContains($date, $category->getDates());
        }
        $this->assertCount(count($dates),  $category->getDates());

    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string'
        ];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIncrementing()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }


}
