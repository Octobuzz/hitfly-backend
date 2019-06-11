<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class CommentsTrackField extends Field
{
    /**
     * @var Album
     */
    private $model;
    protected $attributes = [
        'description' => 'Получение комментов к трекам',
        'selectable' => false,
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('CommentTrack'));
    }

    public function args()
    {
        return [
            'commentPeriod' => [
                'type' => Type::nonNull(\GraphQL::type('CommentPeriodEnum')),
                'description' => 'Ограничение по дате коммента',
            ],
        ];
    }

    /***
     *
     * @param $args
     *
     * @return array
     */
    protected function resolve($root, $args)
    {
        $this->model = $root;
        $now = \Carbon\Carbon::now();

        switch ($args['commentPeriod']) {
            case 'week':
                $date = $now->subWeek()->format('Y-m-d');
                break;
            case 'month':
                $date = $now->subMonth()->format('Y-m-d');
                break;
            case 'year':
                $date = $now->subYear()->format('Y-m-d');
                break;
            default:
                $date = $now->subWeek()->format('Y-m-d');
        }

        return $this->model->comments()->where('created_at', '>=', $date)->limit(4)->get();
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $picturePath
     * @param string $savePath
     * @param string $nameFile
     *
     * @return bool
     */
    private function resize(int $width, int $height, string $picturePath, string $savePath, string $nameFile): bool
    {
        $image_resize = Image::make(Storage::disk('public')->path($picturePath))
            ->fit($width, $height);
        Storage::disk('public')->makeDirectory($this->model->getPath());

        $image_resize->save($savePath.$nameFile, 100);

        return true;
    }
}
