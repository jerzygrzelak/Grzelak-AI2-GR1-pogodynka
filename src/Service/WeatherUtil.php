<?php

namespace App\Service;

use App\Repository\CityRepository;
use App\Repository\MeasurementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RespoDnse;

class WeatherUtil
{

    public function getWeatherForCountryAndCity(string $country, string $city,
                                                MeasurementRepository $measurementRepository,CityRepository $cityRepository): ?array
    {

        $cityObject=$cityRepository->findOneBy(['name'=>$city, 'country'=>$country]);
        if($cityObject==null){
            return [];
        }
        return ['city'=>$cityObject,
                'measurements'=>$this->getWeatherByLocation($cityObject,$measurementRepository)];
    }

    public function getWeatherByLocation($location, MeasurementRepository $measurementRepository){
        return $measurementRepository->findByLocation($location);
    }
}