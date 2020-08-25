<?php

namespace App\Controller;

use App\Requests\BaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    public function response($data, $status = 200, $headers = [])
    {
        $response = new JsonResponse ($data, $status, $headers);
//        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        return $response;
    }

    public function parseJson(BaseRequest $request) {
        if (0 === strpos($request->getHttpRequest()->headers->get('Content-Type'), 'application/json')) {
            return json_decode($request->getHttpRequest()->getContent(), true);
        }

        return [];
    }

}