<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse(['data'=> [
            "locations" => [
                ["name" => "Eiffel Tower",
                "coordinates" => [
                   "lat" => 21.12,
                   "long" => 19.56
                ]],
                ["name" => "House",
                "coordinates" => [
                   "lat" => 0.12,
                   "long" => 0.56
                ]],
            ],
        ], 'status' => true]);
    }
    
    /**
     * @Route("/error", name="error")
     */
    public function errrorAction(Request $request)
    {
        return new JsonResponse(['data'=> [
            'message' => 'No future',
            'code' => 100
        ], 'status' => false]);
    }
    
    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {
        $mapper = $this->container->get("yaroslav_json_mapper_test1");
        return new \Symfony\Component\HttpFoundation\Response(print_r($mapper->findAll(), 
                true));
    }
}
