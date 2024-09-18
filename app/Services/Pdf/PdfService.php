<?php

namespace App\Services\Pdf;

use Barryvdh\DomPDF\PDF;

interface PdfService
{
    /**
     * @param array $info
     * @return PDF
     */
    public function createDeliveryNotePdf(array $info): PDF;
}
