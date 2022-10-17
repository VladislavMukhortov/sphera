<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    /**
     * Локаль
     * @return HasOne
     */
    public function locale(): HasOne
    {
        return $this->hasOne(CountryLocale::class, 'country_id', 'id')->whereLang(app()->getLocale());
    }
}
