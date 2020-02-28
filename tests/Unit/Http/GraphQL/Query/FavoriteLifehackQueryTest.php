<?php

namespace App\Http\GraphQL\Query;

use App\Models\City;
use App\Models\Favourite;
use App\Models\Role;
use App\Models\UserToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MarvinRabe\LaravelGraphQLTest\TestGraphQL;
use Tests\TestCase;

class FavoriteLifehackQueryTest extends TestCase
{
    use TestGraphQL;
    use RefreshDatabase;

    public $graphQLEndpoint = 'graphql';

    public function testQuery(): void
    {
        factory(City::class)->create();
        factory(Role::class, 4)->create();
        $token = factory(UserToken::class)->create();

        factory(Favourite::class)->state('life_hack')->create([
            'user_id' => $token->user->id,
        ]);
        $attr = [
            'id',
            'userId',
            'createdAt',
            'lifehack' => [
                'id',
                'title',
            ],
        ];
        $query = $this
            ->query('favoriteLifehackQuery')
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
                'favoriteLifehackQuery' => [
                    'data' => [
                        $attr,
                    ],
                    'total',
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'total' => 1,
        ]);
    }
}
