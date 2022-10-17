<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetCitiesRequest;
use App\Http\Resources\LocationsCollection;
use App\Models\{City, Country};
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Получить список стран
     *
     * @param Request $request
     *
     * @return LocationsCollection
     */
    public function getCountryList(Request $request): LocationsCollection
    {
        return new LocationsCollection(Country::all()->load('locale'));
    }

    /**
     * Получить список городов по id страны
     *
     * @param GetCitiesRequest $request
     *
     * @return LocationsCollection
     */
    public function getCityList(GetCitiesRequest $request): LocationsCollection
    {
        return new LocationsCollection(City::whereCountryId($request->country_id)->with('locale')->get());
    }
}
