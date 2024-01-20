<?php

namespace App\Controller;

use App\Entity\Hash;
use App\Form\HashFormFileType;
use App\Form\HashFormTextType;
use App\Service\HashWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateController extends AbstractController
{
    public function __construct(protected HashWorker $hashWorker)
    {
    }

    /**
     * @Route("/generate", name="generate")
     */
    public function index(Request $request): Response
    {
        $hash = new Hash();
        $formText = $this->createForm(HashFormTextType::class, $hash);
        $formFile = $this->createForm(HashFormFileType::class, $hash);
        $formText->handleRequest($request);
        $formFile->handleRequest($request);

        if ($formFile->isSubmitted() && $formFile->isValid()) {
            $type = 'file';
            $file = $formFile['fileName']->getData();

            if ($file) {
                $extension = $formFile['extension']->getData();
                $hashFile = hash_file($extension, $file);
            } else {
                $this->addFlash('notice', 'Выберите файл!');
            }
        }

        if ($formText->isSubmitted() && $formText->isValid()) {
            $textHash = $formText['text']->getData();
            $extension = $formText['extension']->getData();

            if ($textHash) {
                $hashText = $this->hashWorker->textHashWorker($extension, $textHash);
            }
        }

        return $this->render('generate/hash.html.twig', [
            'path' => $request->getRequestUri(),
            'formText' => $formText->createView(),
            'formFile' => $formFile->createView(),
            'hashText' => $hashText ?? null,
            'hashFile' => $hashFile ?? null,
            'type' => $type ?? 'text',
        ]);
    }

    /**
     * @Route("/api/generate", name="api_generate")
     */
    public function generate(Request $request): Response
    {
        $fileName = $request->get('fileName');
        $extension = $request->get('extension');
        $textHash = $request->get('text');

        if ($fileName) {
            $hashFile = hash_file($extension, $fileName);
        } else {
            $this->addFlash('notice', 'Выберите файл!');
        }


        if ($textHash) {
            $hashText = $this->hashWorker->textHashWorker($extension, $textHash);
        }

        return $this->json([
            'hashText' => $hashText ?? null,
            'hashFile' => $hashFile ?? null,
        ]);
    }
}
