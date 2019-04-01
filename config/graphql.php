<?php

return [
    // The prefix for routes
    'prefix' => 'graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class.'@query',

    // Any middleware for the graphql route group
    'middleware' => [],

    // Additional route group attributes
    //
    // Example:
    //
    // 'route_group_attributes' => ['guard' => 'api']
    //
    'route_group_attributes' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                'user' => \App\Http\GraphQL\Query\UserQuery::class,
                'users' => \App\Http\GraphQL\Query\UsersQuery::class,
            ],
            'mutation' => [
                'register' => \App\Http\GraphQL\Mutations\RegisterMutation::class,
            ],
            'middleware' => [],
            'method' => ['get', 'post'],
        ],
        'user' => [
            'query' => [
                'genre' => \App\Http\GraphQL\Query\GenreQuery::class,
                'user' => \App\Http\GraphQL\Query\UserQuery::class,
                'users' => \App\Http\GraphQL\Query\UsersQuery::class,
                'musicGroup' => \App\Http\GraphQL\Query\MusicGroupQuery::class,
                'musicGroups' => \App\Http\GraphQL\Query\MusicGroupsQuery::class,
                'album' => \App\Http\GraphQL\Query\AlbumQuery::class,
                'albums' => \App\Http\GraphQL\Query\AlbumsQuery::class,
                'commentsTrack' => \App\Http\GraphQL\Query\CommentsTrackQuery::class,
                'commentsAlbum' => \App\Http\GraphQL\Query\CommentsAlbumQuery::class,
                'track' => \App\Http\GraphQL\Query\TrackQuery::class,
                'tracks' => \App\Http\GraphQL\Query\TracksQuery::class,
                'collection' => \App\Http\GraphQL\Query\CollectionQuery::class,
                'collections' => \App\Http\GraphQL\Query\CollectionsQuery::class,
            ],
            'mutation' => [
                'trackUpload' => \App\Http\GraphQL\Mutations\TrackUploadMutation::class,
                'createMusicGroup' => \App\Http\GraphQL\Mutations\CreateMusicGroupMutation::class,
                'updateMusicGroup' => \App\Http\GraphQL\Mutations\UpdateMusicGroupMutation::class,
                'deletedMusicGroup' => \App\Http\GraphQL\Mutations\DeleteMusicGroupMutation::class,
                'createComment' => \App\Http\GraphQL\Mutations\CreateCommentMutation::class,
                'rateComment' => \App\Http\GraphQL\Mutations\RateCommentMutation::class,
                'createCollection' => \App\Http\GraphQL\Mutations\CreateCollectionMutation::class,
                'updateCollection' => \App\Http\GraphQL\Mutations\UpdateCollectionMutation::class,
            ],
            'middleware' => ['auth:json'],
            'method' => ['get', 'post'],
        ],
    ],

    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'User' => \App\Http\GraphQL\Type\UserType::class,
        'MusicGroup' => \App\Http\GraphQL\Type\MusicGroupType::class,
        'Album' => \App\Http\GraphQL\Type\AlbumType::class,
        'Track' => \App\Http\GraphQL\Type\TrackType::class,
        'Genre' => \App\Http\GraphQL\Type\GenreType::class,
        'CommentAlbum' => \App\Http\GraphQL\Type\CommentAlbumType::class,
        'CommentTrack' => \App\Http\GraphQL\Type\CommentTrackType::class,
        'Collection' => \App\Http\GraphQL\Type\CollectionType::class,
        'CityType' => \App\Http\GraphQL\Type\CityType::class,

        'MusicGroupInput' => \App\Http\GraphQL\InputObject\MusicGroupInput::class,
        'TrackInput' => \App\Http\GraphQL\InputObject\TrackInput::class,
        'UserInput' => \App\Http\GraphQL\InputObject\UserInput::class,
        'CommentInput' => \App\Http\GraphQL\InputObject\CommentInput::class,
        'RateCommentInput' => \App\Http\GraphQL\InputObject\RateCommentInput::class,
        'CollectionInput' => \App\Http\GraphQL\InputObject\CollectionInput::class,

        'CommentType' => \App\Http\GraphQL\Enums\CommentTypeEnum::class,
        'GenderType' => \App\Http\GraphQL\Enums\GenderTypeEnum::class,

        'CommentResult' => \App\Http\GraphQL\Unions\CommentUnion::class,
    ],

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key' => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://github.com/webonyx/graphql-php#security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix' => '/graphiql/{graphql_schema?}',
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],
];
