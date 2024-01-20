<?php

namespace App\Controller;

use App\Form\BaseFormFileType;
use App\Form\BaseFormTextType;
use App\Service\FileWorker;
use App\Service\ImageWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseGenerateController extends AbstractController
{
    public function __construct(
        protected ImageWorker $imageWorker,
        protected FileWorker  $fileWorker
    ) {
    }

    /**
     * @Route("/base64", name="base64")
     */
    public function form(Request $request): Response
    {
        $formFile = $this->createForm(BaseFormFileType::class);
        $formText = $this->createForm(BaseFormTextType::class);

        return $this->render('base64/index.html.twig', [
            'path' => $request->getRequestUri(),
            'formFile' => $formFile->createView(),
            'formText' => $formText->createView(),
        ]);
    }

    /**
     * @Route("/base64/fileGenerate", name="base64_file")
     */
    public function fileGenerate(Request $request): Response
    {
        $form = $this->createForm(BaseFormFileType::class);
        $form->handleRequest($request);
        $base64 = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $form['fileName']->getData();
            $image = $this->imageWorker->getFileData($fileName);

            if ($image) {
                $base64 = base64_encode($image);
            }
        }

        return new JsonResponse($base64, 200);
    }

    /**
     * @Route("/base64/textGenerate", name="base64_text")
     */
    public function textGenerate(Request $request): Response
    {
        $form = $this->createForm(BaseFormTextType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form['text']->getData();

            if ($text) {
                $base64 = base64_decode($text);
                $image = $this->imageWorker->setFileData($base64);

                return $this->file($this->fileWorker->getFile($image));
            }
        }

        return new JsonResponse(400);
    }
}
