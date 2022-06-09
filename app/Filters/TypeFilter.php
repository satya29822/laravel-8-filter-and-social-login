<?php

// TypeFilter.php

namespace App\Filters;

class TypeFilter
{
    public function filter($builder, $value,$request)
    {
        if($request->min_price){
            return $builder->orWhere('price','<=', $value);
        }
        if($request->max_price){
            return $builder->orWhere('price','>=', $value);
        }
        if($request->type){
            return $builder->orWhere('type',$value);
        }
        if($request->slug){
            return $builder->orWhere('slug','like', '%' . $value . '%');
        }
    }
}
