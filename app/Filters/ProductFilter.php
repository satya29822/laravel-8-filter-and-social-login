<?php

// ProductFilter.php

namespace App\Filters;

use App\Filters\TypeFilter;
use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    protected $filters = [
        'min_price' => TypeFilter::class,
        'max_price' => TypeFilter::class,
        'slug' => TypeFilter::class,
        'type' => TypeFilter::class,
    ];
}
