<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\Album;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlbumValidator extends Validator
{
    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $user = Auth::guard('json')->user();

        $album = Album::query()->find($data['albumId']);

        if (null === $album) {
            throw new \Exception('Такого альбома не существует.');
        }

        if ($user->id === $album->user_id) {
            return true;
        }

        return false;
    }

    public function removeTrackFromAlbum(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $user = Auth::guard('json')->user();

        $album = Album::query()->find($data['albumId']);
        $track = Track::query()->find($data['trackId']);

        if (null === $album) {
            throw new \Exception('Такого альбома не существует.');
        }
        if (null === $track) {
            throw new \Exception('Такого трека не существует.');
        }
        /** @var Track $track */
        if ($user->id === $album->user_id && $user->id === $track->user_id && $track->album_id === $album->id) {
            return true;
        }

        return false;
    }
}
