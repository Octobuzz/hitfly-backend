<?php

namespace App\Http\GraphQL\Query;

use App\Models\City;
use App\Models\Favourite;
use App\Models\Lifehack;
use App\Models\Role;
use App\Models\UserToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MarvinRabe\LaravelGraphQLTest\TestGraphQL;
use Tests\TestCase;

class LifehacksQueryTest extends TestCase
{
    use TestGraphQL;
    use RefreshDatabase;

    public $graphQLEndpoint = 'graphql';

    public function testQueryWithFavorite(): void
    {
        factory(City::class)->create();
        factory(Role::class, 4)->create();
        $token = factory(UserToken::class)->create();

        factory(Favourite::class)->state('life_hack')->create([
            'user_id' => $token->user->id,
        ]);
        $attr = [
            'id',
            'title',
            'hasFavorite',
        ];
        $query = $this
            ->query('lifehack')
            ->setSelectionSet(
                [
                    'data' => $attr,
                    'total',
                ])
            ->setArguments([
                'limit' => 1,
                'page' => 1,
            ])
            ->getGql();

        $response = $this->postJson('/graphql',
            [
                'query' => $query,
            ],
            [
                'x-token-auth' => $token->access_token,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'lifehack' => [
                    'data' => [
                        $attr,
                    ],
                    'total',
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'hasFavorite' => true,
        ]);
    }

    public function testQueryWithOutFavorite(): void
    {
        factory(Lifehack::class)->create();
        $attr = [
            'id',
            'title',
            'hasFavorite',
        ];
        $query = $this
            ->query('lifehack')
            ->setSelectionSet(
                [
                    'data' => $attr,
                    'total',
                ])
            ->setArguments([
                'limit' => 1,
                'page' => 1,
            ])
            ->getGql();

        $response = $this->postJson('/graphql',
            [
                'query' => $query,
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'lifehack' => [
                    'data' => [
                        $attr,
                    ],
                    'total',
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'hasFavorite' => false,
        ]);
    }
}
