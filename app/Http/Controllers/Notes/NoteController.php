<?php

namespace App\Http\Controllers\Notes;

use App\DTO\Notes\GetAllData;
use App\DTO\Notes\SaveData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notes\NoteResource;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct(
        protected NoteService $noteService
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(NoteResource::collection($this->noteService->getAll(GetAllData::from($request))));
    }

    public function create(Request $request): JsonResponse
    {
        return response()->json($this->noteService->create(SaveData::from($request)));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json($this->noteService->update(SaveData::from($request),$id));
    }
    public function delete(int $id): JsonResponse
    {
        $this->noteService->delete($id);
        return response()->json(null,204);
    }
}
