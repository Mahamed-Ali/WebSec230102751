<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model  {

    protected $fillable = [
        'code',
        'name',
        'price',
        'model',
        'description',
        'photo'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'product_user');
    }
    public function buyers()
    {
        return $this->belongsToMany(User::class, 'product_user')->withTimestamps();
    }

}
