<?php

namespace App\Service;

use Imagick;
use ImagickException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zxing\QrReader;

class ImageWorker
{
    public function __construct(
        private string $targetDirOut,
        private string $targetDir
    ) {
    }

    /**
     * @throws ImagickException
     */
    public function workWithImages(
        $fileData, $fileName, $extension, $range
    ): ?string {
        $dw = 1600;
        $dh = 1600;
        $rawDir = $this->getTargetDir();
        $outDir = $this->getTargetDirOut();

        if (!$this->mkdir($outDir)) {
            return false;
        }

        $sourceFile = $rawDir . $fileName;
        $sourceFile = str_replace('//', '/', $sourceFile);

        if (is_readable($sourceFile)) {
            list($sw, $sh, $type, $attr) = getimagesize($sourceFile);
            list($dw, $dh) = $this->resizeImages($sw, $sh, $dw, $dh);
        }

        $pathinfo = pathinfo($sourceFile);
        $quality = $range;
        $outDirFileName = $outDir . $pathinfo['filename'] . '.' . $extension;

        switch ($extension) {
            case 'webp':
                $_type = 'webp';
                break;
            case 'jpg':
                $_type = 'jpg';
                break;
            case 'jpeg':
                $_type = 'jpeg';
                break;
            case 'png':
                $_type = 'png';
                break;
            case 'gif':
                $_type = 'GIF';
                break;
            case 'hdr':
                $_type = 'HDR';
                break;
            case 'png8':
                $_type = 'PNG8';
                break;
            case 'png00':
                $_type = 'PNG00';
                break;
            case 'png24':
                $_type = 'PNG24';
                break;
            case 'png32':
                $_type = 'PNG3';
                break;
            case 'png48':
                $_type = 'PNG48';
                break;
            case 'png64':
                $_type = 'PNG64';
                break;
            case 'ppm':
                $_type = 'PPM';
                break;
            case 'pnm':
                $_type = 'PNM';
                break;
            case 'raf':
                $_type = 'RAF';
                break;
            case 'rgb':
                $_type = 'RGB';
                break;
            case 'wpg':
                $_type = 'WPG';
                break;
            case 'aai':
                $_type = 'AAI';
                break;
            case 'art':
                $_type = 'ART';
                break;
            case 'avi':
                $_type = 'AVI';
                break;
            case 'avs':
                $_type = 'AVS';
                break;
            case 'bmp':
                $_type = 'BMP';
                break;
            case 'brf':
                $_type = 'BRF';
                break;
            case 'cip':
                $_type = 'CIP';
                break;
            case 'crw':
                $_type = 'CRW';
                break;
            case 'dcr':
                $_type = 'DCR';
                break;
            case 'dds':
                $_type = 'DDS';
                break;
            case 'dng':
                $_type = 'DNG';
                break;
            case 'epi':
                $_type = 'EPI';
                break;
            case 'isobrl':
                $_type = 'ISOBRL';
                break;
            case 'jbig':
                $_type = 'JBIG';
                break;
            case 'jng':
                $_type = 'JNG';
                break;
        }

        $file = new IMagick($sourceFile);
        $file->resizeImage($dw, $dh, Imagick::FILTER_CATROM, 1, true);
        $_type = $_type ?? 'png';
        $file->setImageFormat($_type);

        $file->setCompressionQuality($quality);
        $file->writeImage($outDirFileName);
        $file->destroy();

        return $outDirFileName;
    }

    public function getTargetDirOut(): ?string
    {
        return $this->targetDirOut;
    }

    public function getQrText($fileName): ?string
    {
        $rawDir = $this->getTargetDir();
        $sourceFile = $rawDir . $fileName;

        return (new QrReader($sourceFile))->text();
    }

    public function getTargetDir(): ?string
    {
        return $this->targetDir;
    }

    public function getFileData(string $fileName): ?string
    {
        return file_get_contents($fileName);
    }

    public function setFileData(string $contents): ?string
    {
        $path = $this->targetDirOut . substr(md5(uniqid('', true)), 0, 4) . '.png';
        file_put_contents($path, $contents);

        return $path;
    }

    public function mkdir($dirPath): bool
    {
        if (file_exists($dirPath)) {
            if (is_dir($dirPath)) {
                return true;
            }

            return false;
        }
        $shellCommand = 'mkdir ' . $dirPath . ' -p';
        exec($shellCommand, $output, $return_var);

        if (file_exists($dirPath)) {
            $shellCommand = 'chmod 775 ' . $dirPath;
            exec($shellCommand, $output, $return_var);

            return true;
        }

        return false;
    }

    public function resizeImages(
        $sourceWidth,
        $sourceHeigth,
        $destinationWidth,
        $destinationHeight
    ): array {
        $ratio = $sourceWidth / $destinationWidth;

        if ($sourceHeigth / $ratio > $destinationHeight) {
            $ratio = $sourceHeigth / $destinationHeight;
        }

        $destinationWidth = $sourceWidth / $ratio;
        $destinationHeight = $sourceHeigth / $ratio;

        return [$destinationWidth, $destinationHeight];
    }

    public function cwebp(
        $sourceFile,
        $outDirFileName,
        $quality = null,
        $resize = []
    ): bool {
        $textFile = ' ' . $sourceFile . ' -o ' . $outDirFileName;

        if ($quality) {
            $textQuality = ' -q ' . $quality;
        }

        $textQuality = $textQuality ?? '';

        if (!empty($resize)) {
            $textResize = ' -resize ' . $resize['width'] . ' ' . $resize['height'];
        }

        $textResize = $textResize ?? '';

        $shellCommand = 'cwebp' . $textQuality . $textFile . $textResize;
        exec($shellCommand);

        return is_readable($outDirFileName);
    }
}
