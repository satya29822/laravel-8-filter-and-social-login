<?php

namespace App\Models;

use App\Filters\ProductFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','price','slug','type','categories'];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ProductFilter($request))->filter($builder,$request);
    }
}
