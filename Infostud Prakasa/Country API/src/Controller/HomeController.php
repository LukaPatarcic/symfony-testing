<?php

namespace App\Controller;

use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    /**
     * @Route("/country/all", name="country_all", methods={"GET"})
     */
    public function getCountryAllAction(CountryRepository $repository)
    {
        $countries = $repository->findAll();
        if(!$countries)
            return $this->json(['error' => 'There are no countries in the database'],Response::HTTP_NO_CONTENT);
        return $this->json($countries,Response::HTTP_OK);
    }

    /**
     * @Route("/country/{name}", name="country_name", methods={"GET"})
     */
    public function getCountryByNameAction(string $name, CountryRepository $repository)
    {
        $country = $repository->findOneBy(['name' => $name]);
        if(!$country)
            return $this->json([],Response::HTTP_NO_CONTENT);
        return $this->json($country,Response::HTTP_OK);
    }

    /**
     * @Route("/country/add", name="country_add", methods={"POST"})
     */
    public function addCountryAction(Request $request, EntityManagerInterface $em, CountryRepository $repository)
    {
        $data = json_decode($request->getContent(),true);
        $country = new Country();
        $availableKeys = ['name','size','currency','population'];

        foreach ($availableKeys as $availableKey) {
            if(!in_array($availableKey,array_keys($data)))
                return $this->json(["error" => 'Bad Request'],Response::HTTP_BAD_REQUEST);
        }
        if($repository->findOneBy(['name' => $data['name']]))
            return $this->json(["error" => 'Country already in database'],Response::HTTP_BAD_REQUEST);
        foreach ($data as $key => $value) {
            $country->{'set'.ucfirst($key)}($value);
        }
        $em->persist($country);
        $em->flush();

        return $this->json([],Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/city/all", name="city_all", methods={"GET"})
     */
    public function getAllCities(CityRepository $repository)
    {
        $cities = $repository->findAll();
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $cities = $serializer->serialize($cities,'json',['ignored_attributes' => ['country']]);

        return $this->json($cities,Response::HTTP_OK);
    }
}
