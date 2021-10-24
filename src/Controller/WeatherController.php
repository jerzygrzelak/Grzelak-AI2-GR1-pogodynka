<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Measurement;
use App\Repository\CityRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{

    public function index(): Response
    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $city=new City();
//        $city->setName('Szczecin');
//        $city->setLatitude(53.428);
//        $city->setLongitude(14.552);
//        $entityManager->persist($city);
//        $entityManager->flush();
//        $entityManager = $this->getDoctrine()->getManager();
//        $city=$this->getDoctrine()->getRepository(City::class)->find(2);
//        $product = new Measurement();
//        $product->setCityId($city);
//        $product->setTemperature(8);
//        $product->setHumidity(12);
//        $product->setWindStrength(20);
//        $product->setDescription("Słonecznie");
//        $product->setDate(new \DateTime);
//        $entityManager->persist($product);
//        $entityManager->flush();
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }
    public function cityAction(string $country, string $city, MeasurementRepository $measurementRepository,CityRepository $cityRepository): Response
    {
        $cityObject=$cityRepository->findOneBy(['name'=>$city, 'country'=>$country]);
        if($cityObject==null){
            return $this->render('weather/not-found.html.twig',[
                'country'=>$country,
                'city'=>$city
            ]);
        }
        $measurements = $measurementRepository->findByLocation($cityObject);
        return $this->render('weather/city.html.twig', [
            'location' => $cityObject,
            'measurements' => $measurements,
        ]);
    }
}
