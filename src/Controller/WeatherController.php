<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Measurement;
use App\Form\CityType;
use App\Form\MeasurementType;
use App\Repository\CityRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }
    public function homeAction(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $city=new City();
        $cityForm=$this->createForm(CityType::class,$city,['validation_groups'=>'city']);
        $cityForm->handleRequest($request);
        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city = $cityForm->getData();
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('city-success','Success!');
        }
        $measurement=new Measurement();
        $measurementForm=$this->createForm(MeasurementType::class,$measurement,['validation_groups'=>'measurement']);
        $measurementForm->handleRequest($request);
        if ($measurementForm->isSubmitted() && $measurementForm->isValid()) {
            $measurement = $measurementForm->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($measurement);
            $entityManager->flush();
            $this->addFlash('weather-success','Success!');
        }
        $searchForm=$this->createFormBuilder()
            ->add('country',TextType::class)
            ->add('city',TextType::class)
            ->add('save',SubmitType::class,[
                'label'=>'Check weather'
            ])
            ->getForm();
        $searchForm->handleRequest($request);
        if($searchForm->isSubmitted() && $searchForm->isValid()){
           $m=$this->getDoctrine()->getRepository(Measurement::class);
           $c=$this->getDoctrine()->getRepository(City::class);
           return $this->cityAction($searchForm->get('country')->getData(),$searchForm->get('city')->getData(),$m,$c);
        }
        return $this->render('weather/home.html.twig',[
            'searchForm'=>$searchForm->createView(),
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
            'measurements' => $measurements,
        ]);
    }

    public function cityEdit(string $country, string $city,CityRepository $cityRepository, Request $request):Response{
        $cityObject=$cityRepository->findOneBy(['name'=>$city, 'country'=>$country]);
        $cityForm=$this->createForm(CityType::class,$cityObject,['validation_groups'=>'city']);
        $cityForm->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city = $cityForm->getData();
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('edit-success','Success!');
        }
        return $this->render('weather/edit-city.html.twig', [
            'cityForm'=>$cityForm->createView(),
            'location' => $cityObject,
        ]);
    }
    public function deleteCity(string $country, string $city,CityRepository $cityRepository, Request $request):Response{
        $cityObject=$cityRepository->findOneBy(['name'=>$city, 'country'=>$country]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cityObject);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
    public function measurementEdit(int $id,MeasurementRepository $measurementRepository, Request $request):Response{
        $measurementObject=$measurementRepository->find($id);
        $measurementForm=$this->createForm(MeasurementType::class,$measurementObject,['validation_groups'=>'measurement']);
        $measurementForm->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($measurementForm->isSubmitted() && $measurementForm->isValid()) {
            $m = $measurementForm->getData();
            $entityManager->persist($m);
            $entityManager->flush();
            $this->addFlash('edit-success','Success!');
        }
        return $this->render('weather/edit-measurement.html.twig', [
            'measurementForm'=>$measurementForm->createView(),
            'measurement' => $measurementObject,
        ]);
    }
    public function deleteMeasurement(int $id ,MeasurementRepository $measurementRepository, Request $request):Response{
        $measurementObject=$measurementRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($measurementObject);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}
