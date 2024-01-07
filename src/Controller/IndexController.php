<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageFormType;
use App\Service\FileWorker;
use App\Service\ImageWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        protected    ImageWorker $imageWorker,
        protected    FileWorker $fileWorker
    ) {
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request)
    {

        $image = new Image();
        $form  = $this->createForm(ImageFormType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $fileData  = $form['name']->getData();
            $extension = $form['extension']->getData();
            $range     = (int) $form['range']->getData();

            $fileNameRaw = $this->fileWorker->upload($fileData);

            $pathinfo    = pathinfo($fileNameRaw);
            $fileNameOut = $pathinfo['filename'];

            $imageWorker = $this->imageWorker->workWithImages($fileData, $fileNameRaw, $extension, $range);

            $image->setName($fileNameOut);

//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($image);
//            $entityManager->flush();

            return $this->file($this->fileWorker->getFile($imageWorker));
        }

        return $this->render('index/index.html.twig', [
            'form'  => $form->createView(),
            'image' => $image,
            'path'  => $request->getRequestUri(),
        ]);
    }
}
