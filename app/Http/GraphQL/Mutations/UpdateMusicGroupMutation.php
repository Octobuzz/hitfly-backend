<?php

namespace App\Http\GraphQL\Mutations;

use App\Helpers\DBHelpers;
use App\Models\GroupLinks;
use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class UpdateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
    ];

    public function type()
    {
        return \GraphQL::type('MusicGroup');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the human.',
            ],
            'musicGroup' => [
                'type' => \GraphQL::type('MusicGroupInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $musicGroup = MusicGroup::find($args['id']);

        if (null === $musicGroup) {
            return null;
        }

        //if ($user = \Auth::user()->can('update', MusicGroup::class)) {
            //throw new OperationNotPermitedException('not persmision'); todo: неработает
        //}

        $musicGroup->update(DBHelpers::arrayKeysToSnakeCase($args['musicGroup']));


        foreach ($args['musicGroup']['socialLinks'] as $social){
            /** @var GroupLinks $socialLinks */
            $socialLinks = GroupLinks::query()->where('music_group_id',$args['id'])
                                        ->where('social_type',$social['socialType'])->first();
            if (null === $socialLinks) {
                $socialLinks = new GroupLinks();
                $socialLinks->social_type = $social['socialType'];
                $socialLinks->link = $social['link'];
                $socialLinks->music_group_id = $args['id'];
                $socialLinks->save();
            }else {
                $socialLinks->setRawAttributes(DBHelpers::arrayKeysToSnakeCase($social));
                $socialLinks->save();
            }
        }

        return $musicGroup;
    }
}
