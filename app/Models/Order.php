<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'orders'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'user_id', 'category_id', 'status_id', 'booked_at', 'note', 'price'
    ];

    public function statusOrder()
    {
        return $this->hasOne(StatusOrder::class, 'id', 'status_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
