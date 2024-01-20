<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageFormType;
use App\Service\FileWorker;
use App\Service\ImageWorker;
use ImagickException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        protected ImageWorker $imageWorker,
        protected FileWorker  $fileWorker
    ) {
    }

    /**
     * @Route("/", name="index")
     *
     * @throws ImagickException
     */
    public function index(Request $request): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileData = $form['name']->getData();
            $extension = $form['extension']->getData();
            $range = (int)$form['range']->getData();

            $fileNameRaw = $this->fileWorker->upload($fileData);
            $pathInfo = pathinfo($fileNameRaw);
            $fileNameOut = $pathInfo['filename'];

            $imageWorker = $this->imageWorker->workWithImages($fileData, $fileNameRaw, $extension, $range);
            $image->setName($fileNameOut);

            return $this->file($this->fileWorker->getFile($imageWorker));
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
            'image' => $image,
            'path' => $request->getRequestUri(),
        ]);
    }

    /**
     * @Route("/api/convert", name="api_convert")
     *
     * @throws ImagickException
     */
    public function apiConvert(Request $request): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageFormType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileData = $form['name']->getData();
            $extension = $form['extension']->getData();
            $range = (int)$form['range']->getData();

            $fileNameRaw = $this->fileWorker->upload($fileData);
            $pathInfo = pathinfo($fileNameRaw);
            $fileNameOut = $pathInfo['filename'];

            $imageWorker = $this->imageWorker->workWithImages($fileData, $fileNameRaw, $extension, $range);
            $image->setName($fileNameOut);

            return $this->file($this->fileWorker->getFile($imageWorker));
        }
    }
}
