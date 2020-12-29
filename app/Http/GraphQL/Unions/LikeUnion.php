<?php

namespace App\Http\GraphQL\Unions;

use App\Models\Lifehack;
use Exception;
use GraphQL;
use Rebing\GraphQL\Support\UnionType;

class LikeUnion extends UnionType
{
    protected $attributes = [
        'name' => 'LikeResult',
    ];

    public function types()
    {
        return [
            GraphQL::type('LifehackType'),
        ];
    }

    /**
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function resolveType($value)
    {
        $className = get_class($value);
        switch ($className) {
            case Lifehack::class:
                return GraphQL::type('LifehackType');
                break;
            default:
                throw new Exception('Unsupport type ' . $className);
        }
    }
}
