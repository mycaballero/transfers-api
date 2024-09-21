<?php

namespace App\Services\Notes;

use App\DTO\Notes\GetAllData;
use App\DTO\Notes\SaveData;

interface NoteService
{
    public function getAll(GetAllData $getAllData);

    public function create(SaveData $saveData);

    public function update(SaveData $saveData, int $id);

    public function delete(int $id);
}
