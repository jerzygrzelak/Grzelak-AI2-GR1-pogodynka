<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Measurement;
use App\Form\CityType;
use App\Form\MeasurementType;
use App\Repository\CityRepository;
use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    public function homeAction(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $city=new City();
        $cityForm=$this->createForm(CityType::class,$city);
        $cityForm->handleRequest($request);
        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city = $cityForm->getData();
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('city-success','Success!');
        }

        $measurement=new Measurement();
        $measurementForm=$this->createForm(MeasurementType::class,$measurement);
        $measurementForm->handleRequest($request);
        if ($measurementForm->isSubmitted() && $measurementForm->isValid()) {
            $measurement = $measurementForm->getData();
            $city=$this->getDoctrine()->getRepository(City::class)->findOneBy(array('name'=>$measurementForm->get('cityName')->getData()));
            if($city==null){
                $city=new City();
                $city->setName($measurementForm->get('cityName')->getData());
                $city->setCountry('Nigeria');
                $city->setLatitude(53.428);
                $city->setLongitude(14.552);
                $entityManager->persist($city);
                $entityManager->flush();
            }
            $measurement->setCityId($city);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($measurement);
            $entityManager->flush();
            $this->addFlash('weather-success','Success!');
        }
        return $this->render('weather/home.html.twig',[
            'cityForm'=>$cityForm->createView(),
            'measurementForm'=>$measurementForm->createView()
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
            'measurements' => $measurements,n
        ]);
    }
    public function  citiesListAction(Request $request):Response{

        return $this->render('weather/cities.html.twig', [
        ]);
    }
    public function  measurementsListAction(MeasurementRepository $measurementRepository,CityRepository $cityRepository):Response{
        $measurements = $measurementRepository->findAll();
        $cities=[];
        foreach($measurements as $m){
            if(!in_array($m->getCityId()->getName(),$cities)){
                array_push($cities,$m->getCityId()->getName());
            }
        }
        dd($cities);
        //$cities=$cityRepository->findBy($measurements);
        return $this->render('weather/measurements.html.twig', [
            'measurements' => $measurements,
        ]);
    }
}
