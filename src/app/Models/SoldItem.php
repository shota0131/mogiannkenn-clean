<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'sending_postcode',
        'sending_address',
        'sending_building',
    ];

    // 商品情報
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 購入者情報
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
