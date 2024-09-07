<?php

namespace App\DTO\Notes;

use Spatie\LaravelData\Data;

class SaveData extends Data
{
    /**
     * @param int|null $picking_id
     * @param string|null $text
     */
    public function __construct(
        public ?int    $picking_id,
        public ?string $text,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'picking_id' => 'int',
            'text' => 'required|max:500',
        ];
    }
}
