<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 19.03.2019
 * Time: 18:56
 */

namespace App\BuisnessLogic\Recommendation;


use App\Contracts\Playlist\RecommendationList;

class Recommendation implements RecommendationList
{

    public function getNewUserPlayList(int $count)
    {
        // TODO: Implement getRecommendPlaylist() method. получить реальные рекомендации
        return [
            [
                'name' =>'Название плейлиста',
                'date' =>'7 декабря',
                'count_tracks' =>'256',
                'list_img' =>'/url',
                'link' =>'/url',
            ],
            [
                'name' =>'Плейлист 2',
                'date' =>'7 декабря',
                'count_tracks' =>'100',
                'list_img' =>'/url',
                'link' =>'/url',
            ]

        ];

    }
}