<?php

namespace App\Services\Notes\Impl;

use App\DTO\Notes\GetAllData;
use App\DTO\Notes\SaveData;
use App\Services\Notes\NoteService;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteServiceImpl implements NoteService
{

    public function getAll(GetAllData $getAllData)
    {

        return Note::query()->with(['user'])
            ->where('picking_id','=', $getAllData->pickingId)
            ->orderByDesc('created_at')
            ->get();

    }

    public function create(SaveData $saveData): Note
    {
        $user = Auth::user();
        $payload = [
            'user_id' => $user->id,
            'picking_id' => $saveData->picking_id,
            'text' => $saveData->text
        ];
        return Note::create($payload);
    }

    public function update(SaveData $saveData, int $id): Note
    {
        $note = Note::findOrFail($id);
        $note->text = $saveData->text;
        $note->save();
        return $note;
    }

    public function delete(int $id)
    {
        return Note::findOrfail($id)->delete();
    }
}
