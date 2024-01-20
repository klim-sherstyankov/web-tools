<?php

namespace App\Service;

class HashWorker
{
    public function textHashWorker(
        string $algo,
        string $data,
        bool   $binary = false
    ): ?string {
        return hash($algo, $data, $binary);
    }
}
