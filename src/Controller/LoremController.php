<?php

namespace App\Controller;

use App\Service\LoremWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoremController extends AbstractController
{
    /**
     * @Route("/api/lorem", name="api_lorem")
     */
    public function loremGenerate(Request $request): Response
    {
        $length = $request->get('length');

        if ($length) {
            $ipsum = LoremWorker::ipsum($length);

            return new JsonResponse($ipsum, 200);
        }

        return new JsonResponse(400);
    }
}
