<?php

namespace App\Controller;

use App\Form\QrFormFileType;
use App\Form\QrFormTextType;
use App\Service\FileWorker;
use App\Service\ImageWorker;
use App\Service\QrWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QrController extends AbstractController
{
    public function __construct(
        protected ImageWorker $imageWorker,
        protected QrWorker    $qrService,
        protected FileWorker  $fileWorker
    ) {
    }

    /**
     * @Route("/qr", name="qr")
     */
    public function form(Request $request): Response
    {
        $formFile = $this->createForm(QrFormFileType::class);
        $formText = $this->createForm(QrFormTextType::class);

        return $this->render('qr/index.html.twig', [
            'path' => $request->getRequestUri(),
            'formFile' => $formFile->createView(),
            'formText' => $formText->createView(),
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
