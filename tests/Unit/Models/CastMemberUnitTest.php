<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CastMemberUnitTest extends TestCase
{
    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'type'];
        $this->assertEquals($fillable,$this->castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, \App\Models\Traits\Uuid::class
        ];
        $castMemeberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $castMemeberTraits);
    }

    public function testDatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach($dates as $date){
            $this->assertContains($date, $this->castMember->getDates());
        }
        $this->assertCount(count($dates),  $this->castMember->getDates());

    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string',
            'type' => 'integer'
        ];
        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->castMember->incrementing);
    }
}
