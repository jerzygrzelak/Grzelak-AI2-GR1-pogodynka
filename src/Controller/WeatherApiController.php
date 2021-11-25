<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\MeasurementRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class WeatherApiController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('weather_api/index.html.twig', [
            'controller_name' => 'WeatherApiController',
        ]);
    }

    public function weatherJsonAction(WeatherUtil $weatherUtil, CityRepository $cityRepository,
                                      MeasurementRepository $measurementRepository, Request $request, SerializerInterface $serializer):Response{
        $payload=$request->getContent();
        $payload=json_decode($payload,true);
        $country=$payload['country'];
        $city=$payload['city'];
        $response=new Response();
        $measurements=$weatherUtil->getWeatherForCountryAndCity($country,$city,$measurementRepository, $cityRepository);
        $json=$serializer->serialize($measurements['measurements'],'json');
        dd($json);
//        $response->setContent(json_encode($measurements));
//        $response->headers->set('Content-Type','application/json');
//        $response->setContent(json_encode($measurements['city']->getName()));
//        $response->setContent(json_encode($measurements['city']->getLatitude()));
        return $response;
    }
}
