<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'address_id',
        'status',
        'shipping_method',
        'shipping_description',
        'is_gift',
        'total_item_count',
        'total_qty_ordered',
        'grand_total',
        'user_id',
        'invoice',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
