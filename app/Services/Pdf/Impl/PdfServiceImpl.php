<?php

namespace App\Services\Pdf\Impl;

use App\Services\Pdf\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfServiceImpl implements PdfService
{

    /**
     * @param array $info
     * @return \Barryvdh\DomPDF\PDF
     */
    public function createDeliveryNotePdf(array $info): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('pdf.deliveryNote', compact('info'));
    }
}
