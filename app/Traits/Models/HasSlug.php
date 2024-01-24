<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function(Model $item){
            $item->slug = $item->slug ?? str($item->{self::slugSource()})
                ->append(rand(1,100000))
                ->slug();
        });

    }
    public static function slugSource(): string{
        return 'title';
    }
}
