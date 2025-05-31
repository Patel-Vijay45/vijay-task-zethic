<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_no',
        'alternative_phone_no',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'is_default',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
