<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use UUIDGenerator;

    protected $table = 'galleries'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'tittle', 'description', 'category_id', 'file'
    ];
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
