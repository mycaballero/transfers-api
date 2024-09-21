<?php

namespace App\Http\Resources\Notes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user' => $this->user->name,
            'date' => $this->created_at,
        ];
    }
}
