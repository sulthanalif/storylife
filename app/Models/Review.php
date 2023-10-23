<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use UUIDGenerator;

    protected $table = 'reviews'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'user_id', 'category_id', 'rating', 'comment'
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
