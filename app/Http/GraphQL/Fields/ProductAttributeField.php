<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class ProductAttributeField extends Field
{
    private $model;
    protected $attributes = [
        'description' => 'Атрибуты товара',
        'selectable' => false,
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('AttributeResult'));
    }

    /***
     *
     * @param $args
     *
     * @return array
     * @throws \Exception
     */
    protected function resolve($root, $args)
    {
        $attributes = [];
        foreach ($root->attributes as $attr) {
            if (!empty($attr->model)) {
                switch ($attr->model) {
                    case Track::class:
                        $model = Track::class;
                        break;
                    case User::class:
                        $model = User::class;
                        break;
                    default:
                        new \Exception('Нет атрибута с таким классом');
                }
                $attributes[] = $model::query()->find($attr->pivot->value);
            } else {
                throw  new \Exception('Немогу установить модель атрибута');
            }
        }

        return $attributes;
    }
}
