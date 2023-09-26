<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries'; // Specify the table name
    
    protected $fillable = [
        'tittle', 'description', 'category_id', 'file'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
