<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 5/26/2019
 * Time: 2:11 PM
 */

namespace App\Services;


use App\Entity\City;
use App\Entity\Country;

class CustomEncoders
{

    public function encodeCountry(Country $country)
    {
        $cities = $country->getCities()->toArray();
        foreach ($cities as $city) {
            $cityData[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'population' => $city->getPopulation(),
                'zipCode' => $city->getZipCode(),
            ];
        }
        $data  = [
            'id' => $country->getId(),
            'name' => $country->getName(),
            'size' => $country->getSize(),
            'population' => $country->getPopulation(),
            'currency' => $country->getCurrency(),
            'cities' => $cityData,
        ];
        return $data;

    }

    public function encodeCity(City $city)
    {
        $country = $city->getCountry();
        $data  = [
            'id' => $city->getId(),
            'name' => $city->getName(),
            'population' => $city->getPopulation(),
            'zipCode' => $city->getZipCode(),
            'country' => [
                'id' => $country->getId(),
                'name' => $country->getName(),
                'size' => $country->getSize(),
                'population' => $country->getPopulation(),
                'currency' => $country->getCurrency(),
            ],
        ];
        return $data;

    }

}