<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Http\Response;


/**
 * API Жанров музыки
 *
 * Class GenreController
 */
class GenreController extends Controller
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
        $genres = Genre::all();
        return $this->sendSuccessResponse(array($genres, Response::HTTP_OK));
    }

    /**
     * Получить жанр музыки по id
     *
     * @param Genre $genre
     * @api /api/v1/genre/{genre}
     *
     * @return JsonResponse
     */
    public function genre(Genre $genre): JsonResponse
    {
        return $this->sendSuccessResponse($genre, Response::HTTP_OK);
    }

}
