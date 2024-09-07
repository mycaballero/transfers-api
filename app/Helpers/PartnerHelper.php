<?php

namespace App\Helpers;

use App\Models\Picking;

class PartnerHelper
{
    /**
     * @return string
     */
    public static function generateOtuString(): string
    {
        $lastPartner = Picking::orderBy('name', 'desc')->first();
        if ($lastPartner) {
            $lastName = $lastPartner->name;
            $lastConsecutive = (int) substr($lastName, 4);
        } else {
            $lastConsecutive = 0;

        }
        $nextConsecutive = $lastConsecutive + 1;
        return 'OTU/' . str_pad($nextConsecutive, 5, '0', STR_PAD_LEFT);
    }
}
