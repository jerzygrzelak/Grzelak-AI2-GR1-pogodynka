<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('weather_api/index.html.twig', [
            'controller_name' => 'WeatherApiController',
        ]);
    }

    public function weatherJsonAction(string $country,string $city, string $format, WeatherUtil $weatherUtil):Response{
        $response=new Response();
        return $response;
    }
}
