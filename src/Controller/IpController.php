<?php

namespace App\Controller;

use App\Form\QrFormFileType;
use App\Form\QrFormTextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IpController extends AbstractController
{
    protected $imageWorker;

    /**
     * @Route("/ip", name="ip")
     */
    public function form(Request $request): Response
    {
        return $this->render('ip/index.html.twig', [
            'path' => $request->getRequestUri(),
        ]);
    }

    /**
     * @Route("/qr/fileGenerate", name="qr_file")
     */
    public function fileGenerate(Request $request): Response
    {
        $form = $this->createForm(QrFormFileType::class);
        $form->handleRequest($request);
        $text = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $fileData = $form['fileName']->getData();
            $fileNameRaw = $this->fileWorker->upload($fileData);
            $pathinfo = pathinfo($fileNameRaw);
            $text = $this->imageWorker->getQrText($pathinfo['basename']);
        }

        return new JsonResponse($text, 200);
    }

    /**
     * @Route("/qr/textGenerate", name="qr_text")
     */
    public function textGenerate(Request $request): Response
    {
        $form = $this->createForm(QrFormTextType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form['text']->getData();

            if ($text) {
                $urlQrImage = $this->qrService->getImage($text, $request->getSchemeAndHttpHost());

                return new JsonResponse($urlQrImage, 200);
            }
        }

        return new JsonResponse(400);
    }
}
