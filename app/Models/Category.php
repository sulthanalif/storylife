<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use UUIDGenerator;

    protected $table = 'categories'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name', 'description'
    ];

    

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
