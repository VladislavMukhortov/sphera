<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class City extends Model
{
    use HasFactory;

    /**
     * Локаль
     * @return HasOne
     */
    public function locale(): HasOne
    {
        return $this->hasOne(CityLocale::class, 'city_id', 'id')->whereLang(app()->getLocale());
    }
}
