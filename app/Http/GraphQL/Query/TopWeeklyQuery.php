<?php

namespace App\Http\GraphQL\Query;

use App\Interfaces\Top\TopWeeklyInterface;
use App\Models\Track;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class TopWeeklyQuery extends Query
{
    private $topWeekly;

    public function __construct($attributes = [], TopWeeklyInterface $topWeekly)
    {
        parent::__construct($attributes);
        $this->topWeekly = $topWeekly;
    }

    protected $attributes = [
        'name' => 'TopWeeklyQuery',
        'description' => 'ТОП Недели',
    ];

    public function type()
    {
        return \GraphQL::paginate('Track');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Track::with($fields->getRelations());
        $query->whereIn('id', $this->topWeekly->get());

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
