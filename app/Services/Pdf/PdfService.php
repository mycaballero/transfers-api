<?php

namespace App\Services\Pdf;

interface PdfService
{
    public function createDeliveryNotePdf(array $info);
}
