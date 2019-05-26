<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Services\CustomEncoders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/country/all", name="country_all", methods={"GET"})
     */
    public function getCountryAllAction(CountryRepository $repository, CustomEncoders $encoders)
    {
        $countries = $repository->findAll();
        if(!$countries)
            return $this->json(['error' => 'There are no countries in the database'],Response::HTTP_NO_CONTENT);
        foreach ($countries as $country) {
            $results[] = $encoders->encodeCountry($country);
        }
        return new JsonResponse($results,Response::HTTP_OK);
    }

    /**
     * @Route(path="/country/{name}", name="country_name", methods={"GET"})
     */
    public function getCountryByNameAction(string $name, CountryRepository $repository, CustomEncoders $encoders)
    {
        $country = $repository->findOneBy(['name' => $name]);

        if(!$country)
            return $this->json([],Response::HTTP_NO_CONTENT);

        $result = $encoders->encodeCountry($country);

        return new JsonResponse($result,Response::HTTP_OK);
    }

    /**
     * @Route(path="/country/add", name="country_add", methods={"POST"})
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

        return $this->json($country,Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/city/all", name="city_all", methods={"GET"})
     */
    public function getAllCities(CityRepository $repository, CustomEncoders $encoders)
    {
        $cities = $repository->findAll();
        foreach ($cities as $city) {
            $results[] = $encoders->encodeCity($city);
        }
        return new JsonResponse($results,Response::HTTP_OK);
    }

    /**
     * @Route(path="/city/{name}", name="city_name", methods={"GET"})
     */
    public function getCityByNameAction(string $name, CityRepository $repository, CustomEncoders $encoders)
    {
        $city = $repository->findOneBy(['name' => $name]);
        if(!$city)
            return $this->json([],Response::HTTP_NO_CONTENT);

        $result = $encoders->encodeCity($city);

        return new JsonResponse($result,Response::HTTP_OK);
    }

    /**
     * @Route(path="/city/add", name="city_add", methods={"POST"})
     */
    public function addCityAction(EntityManagerInterface $em, CountryRepository $countryRepository, CityRepository $cityRepository, Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $city = new City();
        $availableKeys = ['name','zipCode','country','population'];

        foreach ($availableKeys as $availableKey) {
            if(!in_array($availableKey,array_keys($data)))
                return $this->json(["error" => 'Bad Request'],Response::HTTP_BAD_REQUEST);
        }
        if($cityRepository->findOneBy(['name' => $data['name']]))
            return $this->json(["error" => 'City already in database'],Response::HTTP_BAD_REQUEST);

        if(!$country = $countryRepository->findOneBy(['name' => $data['country']]))
            return $this->json(["error" => 'Country does not exist'],Response::HTTP_BAD_REQUEST);

        foreach ($data as $key => $value) {
            if($key == 'country') {
                $city->setCountry($country);
                continue;
            }
            $city->{'set'.ucfirst($key)}($value);
        }

        $em->persist($city);
        $em->flush();

        return $this->json($city,Response::HTTP_CREATED);

    }

}
