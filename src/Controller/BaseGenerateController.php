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
     * @Route("/api/base64/fileGenerate", name="api_base64_file")
     */
    public function fileGenerate(Request $request): Response
    {
        $base64 = null;

        $fileName = $request->files->get('file');
        $image = $this->imageWorker->getFileData($fileName);

        if ($image) {
            $base64 = base64_encode($image);
        }

        return $this->json(['base64' => $base64]);
    }

    /**
     * @Route("/api/base64/textGenerate", name="api_base64_text")
     */
    public function textGenerate(Request $request): Response
    {
        $text = $request->get('text');

        if ($text) {
            $base64 = base64_decode($text);
            $image = $this->imageWorker->setFileData($base64);

            return $this->file($this->fileWorker->getFile($image));
        }

        return new JsonResponse(400);
    }
}
