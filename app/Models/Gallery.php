<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'galleries'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'tittle', 'description', 'category_id', 'image'
    ];


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
