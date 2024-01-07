<?php

namespace App\Service;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrWorker
{
    public function __construct(private string $filePath)
    {
    }

    public function getImage(string $url, string $absolutUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(150),
            new ImagickImageBackEnd()
        );
        $name = md5(mt_rand());
        //путь к файлу в письме
        $fileUrl = $absolutUrl . '/img/out/qr/' . $name . '.png';

        //кладем файл
        $filePath = $this->filePath . '/qr/' . $name . '.png';

        $writer = new Writer($renderer);
        $writer->writeFile($url, $filePath);

        return $fileUrl;
    }
}
