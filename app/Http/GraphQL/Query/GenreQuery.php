<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 16:59.
 */

namespace App\Http\GraphQL\Query;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class GenreQuery extends Query
{
    protected $attributes = [
        'name' => 'Genre Query',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('Genre'));
    }

    public function resolve($root, $args)
    {
        return Genre::all();
    }
}
