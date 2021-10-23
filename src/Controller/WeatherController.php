<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Measurement;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{

    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $city=new City();
        $city->setName('Szczecin');
        $city->setLatitude(53.428);
        $city->setLongitude(14.552);
        $entityManager->persist($city);

        $product = new Measurement();
        $product->setCityId($city);
        $product->setTemperature(1.2);
        $product->setHumidity(12);
        $product->setWindStrength(14);
        $product->setDescription("Brzydko");
        $product->setDate(new \DateTime);
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }
    public function cityAction(City $city, MeasurementRepository $measurementRepository): Response
    {
        $measurements = $measurementRepository->findByLocation($city);

        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'measurements' => $measurements,
        ]);
    }
}
