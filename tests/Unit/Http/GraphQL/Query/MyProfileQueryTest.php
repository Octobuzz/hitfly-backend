<?php

namespace App\Http\GraphQL\Query;

use App\Models\City;
use App\Models\Favourite;
use App\Models\Role;
use App\Models\UserToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MarvinRabe\LaravelGraphQLTest\TestGraphQL;
use Tests\TestCase;

class MyProfileQueryTest extends TestCase
{
    use TestGraphQL;
    use RefreshDatabase;

    public function testMyProfileQuery(): void
    {
        factory(City::class)->create();
        factory(Role::class, 4)->create();
        $token = factory(UserToken::class)->create();
        factory(Favourite::class)->state('life_hack')->create([
            'user_id' => $token->user->id,
        ]);
        $attr = [
            'id',
            'favouritesLifehackCount',
        ];

        $query = $this
            ->query('myProfile')
            ->setSelectionSet($attr)
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
                'myProfile' => $attr,
            ],
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'favouritesLifehackCount' => 1,
        ]);
    }
}
