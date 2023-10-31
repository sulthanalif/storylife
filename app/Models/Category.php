<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'categories'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name', 'description'
    ];

    

    public function galleries()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function reviews()
    {
        return $this->belongsTo(Review::class);
    }
}
