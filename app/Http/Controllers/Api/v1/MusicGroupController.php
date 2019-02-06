<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Genre;
use App\Models\MusicGroup;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Http\Response;


/**
 * API Жанров музыки
 *
 * Class GenreController
 */
class MusicGroupController extends Controller
{
	use ApiResponseTrait;

    /**
     * Получить все жанры музыки
     *
     * @api /api/v1/genre
     *
     * @return JsonResponse
     */
	public function index(): JsonResponse
    {
        $genres = MusicGroup::all();
        return $this->sendSuccessResponse(array($genres, Response::HTTP_OK));
    }

    /**
     * Получить жанр музыки по id
     *
     * @param MusicGroup $musicGroup
     * @api /api/v1/genre/{genre}
     *
     * @return JsonResponse
     */
    public function byId(MusicGroup $musicGroup): JsonResponse
    {
        return $this->sendSuccessResponse($musicGroup, Response::HTTP_OK);
    }

}
