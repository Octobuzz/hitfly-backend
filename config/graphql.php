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
                'genre' => \App\Http\GraphQL\Query\GenreQuery::class,
                'musicGroup' => \App\Http\GraphQL\Query\MusicGroupQuery::class,
                'musicGroups' => \App\Http\GraphQL\Query\MusicGroupsQuery::class,
                'albums' => \App\Http\GraphQL\Query\AlbumsQuery::class,
                'album' => \App\Http\GraphQL\Query\AlbumQuery::class,
                'commentsTrack' => \App\Http\GraphQL\Query\CommentsTrackQuery::class,
                'commentsAlbum' => \App\Http\GraphQL\Query\CommentsAlbumQuery::class,
                'tracks' => \App\Http\GraphQL\Query\TracksQuery::class,
                'track' => \App\Http\GraphQL\Query\TrackQuery::class,
                'collections' => \App\Http\GraphQL\Query\CollectionsQuery::class,
                'collection' => \App\Http\GraphQL\Query\CollectionQuery::class,
                'locations' => \App\Http\GraphQL\Query\LocationsQuery::class,
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
                'albums' => \App\Http\GraphQL\Query\AlbumsQuery::class,
                'album' => \App\Http\GraphQL\Query\AlbumQuery::class,
                'commentsTrack' => \App\Http\GraphQL\Query\CommentsTrackQuery::class,
                'commentsAlbum' => \App\Http\GraphQL\Query\CommentsAlbumQuery::class,
                'tracks' => \App\Http\GraphQL\Query\TracksQuery::class,
                'track' => \App\Http\GraphQL\Query\TrackQuery::class,
                'collections' => \App\Http\GraphQL\Query\CollectionsQuery::class,
                'collection' => \App\Http\GraphQL\Query\CollectionQuery::class,
                'favouriteAlbum' => \App\Http\GraphQL\Query\FavouriteAlbumQuery::class,
                'favouriteTrack' => \App\Http\GraphQL\Query\FavouriteTrackQuery::class,
                'favouriteGenre' => \App\Http\GraphQL\Query\FavouriteGenreQuery::class,
                'favouriteCollection' => \App\Http\GraphQL\Query\FavouriteCollectionQuery::class,
                'favouriteSet' => \App\Http\GraphQL\Query\FavouriteSetQuery::class,
                'myProfile' => \App\Http\GraphQL\Query\MyProfileQuery::class,
                'locations' => \App\Http\GraphQL\Query\LocationsQuery::class,
            ],
            'mutation' => [
                'uploadTrack' => \App\Http\GraphQL\Mutations\Track\UploadTrackMutation::class,
                'updateTrack' => \App\Http\GraphQL\Mutations\Track\UpdateTrackMutation::class,

                'createMusicGroup' => \App\Http\GraphQL\Mutations\CreateMusicGroupMutation::class,
                'updateMusicGroup' => \App\Http\GraphQL\Mutations\UpdateMusicGroupMutation::class,
                'deletedMusicGroup' => \App\Http\GraphQL\Mutations\DeleteMusicGroupMutation::class,
                'createComment' => \App\Http\GraphQL\Mutations\CreateCommentMutation::class,
                'updateComment' => \App\Http\GraphQL\Mutations\UpdateCommentMutation::class,
                'rateComment' => \App\Http\GraphQL\Mutations\RateCommentMutation::class,

                'createCollection' => \App\Http\GraphQL\Mutations\Collection\CreateCollectionMutation::class,
                'updateCollection' => \App\Http\GraphQL\Mutations\Collection\UpdateCollectionMutation::class,

                'updateMyProfile' => \App\Http\GraphQL\Mutations\User\UpdateMyProfileMutation::class,
                'updatePassword' => \App\Http\GraphQL\Mutations\User\UpdatePasswordMutation::class,
                'updateEmail' => \App\Http\GraphQL\Mutations\User\UpdateEmailMutation::class,

                'addInCollection' => \App\Http\GraphQL\Mutations\Collection\AddInCollectionMutation::class,

                'addToFavourites' => \App\Http\GraphQL\Mutations\AddToFavouriteMutation::class,
                'deleteFromFavourite' => \App\Http\GraphQL\Mutations\DeleteFromFavouriteMutation::class,

                'deleteTrackMutation' => \App\Http\GraphQL\Mutations\Track\DeleteTrackMutation::class,

                'createAlbum' => \App\Http\GraphQL\Mutations\CreateAlbumMutation::class,
                'updateAlbum' => \App\Http\GraphQL\Mutations\UpdateAlbumMutation::class,
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
        'MyProfile' => \App\Http\GraphQL\Type\MyProfileType::class,
        'MusicGroup' => \App\Http\GraphQL\Type\MusicGroupType::class,
        'Album' => \App\Http\GraphQL\Type\AlbumType::class,
        'Track' => \App\Http\GraphQL\Type\TrackType::class,
        'Genre' => \App\Http\GraphQL\Type\GenreType::class,
        'CommentAlbum' => \App\Http\GraphQL\Type\CommentAlbumType::class,
        'CommentTrack' => \App\Http\GraphQL\Type\CommentTrackType::class,
        'Collection' => \App\Http\GraphQL\Type\CollectionType::class,
        'CityType' => \App\Http\GraphQL\Type\CityType::class,
        'FavouriteTrack' => \App\Http\GraphQL\Type\FavouriteTrackType::class,
        'FavouriteAlbum' => \App\Http\GraphQL\Type\FavouriteAlbumType::class,
        'FavouriteGenre' => \App\Http\GraphQL\Type\FavouriteGenreType::class,
        'FavouriteCollection' => \App\Http\GraphQL\Type\FavouriteCollectionType::class,
        'SocialLinks' => \App\Http\GraphQL\Type\SocialLinksType::class,
        'GroupMembersType' => \App\Http\GraphQL\Type\GroupMembersType::class,
        'ImageSizesType' => \App\Http\GraphQL\Type\ImageSizesType::class,
        'RoleType' => \App\Http\GraphQL\Type\RoleType::class,

        'MusicGroupInput' => \App\Http\GraphQL\InputObject\MusicGroupInput::class,
        'TrackInput' => \App\Http\GraphQL\InputObject\TrackInput::class,
        'UserInput' => \App\Http\GraphQL\InputObject\UserInput::class,
        'CommentInput' => \App\Http\GraphQL\InputObject\CommentInput::class,
        'RateCommentInput' => \App\Http\GraphQL\InputObject\RateCommentInput::class,
        'FavouriteInput' => \App\Http\GraphQL\InputObject\FavouriteInput::class,
        'SocialLinksInput' => \App\Http\GraphQL\InputObject\SocialLinksInput::class,
        'GroupMembersInput' => \App\Http\GraphQL\InputObject\GroupMembersInput::class,
        'MyProfileInput' => \App\Http\GraphQL\InputObject\MyProfileInput::class,
        'ArtistProfileInput' => \App\Http\GraphQL\InputObject\ArtistProfileInput::class,
        'MusicGroupUpdateInput' => \App\Http\GraphQL\InputObject\MusicGroupUpdateInput::class,
        'AlbumInput' => \App\Http\GraphQL\InputObject\AlbumInput::class,
        'PasswordInput' => \App\Http\GraphQL\InputObject\PasswordInput::class,

        'CommentTypeEnum' => \App\Http\GraphQL\Enums\CommentTypeEnum::class,
        'FavouriteTypeEnum' => \App\Http\GraphQL\Enums\FavouriteTypeEnum::class,
        'GenderType' => \App\Http\GraphQL\Enums\GenderTypeEnum::class,
        'SocialLinksTypeEnum' => \App\Http\GraphQL\Enums\SocialLinksTypeEnum::class,
        'AvatarSizeEnum' => \App\Http\GraphQL\Enums\AvatarSizeEnum::class,
        'AlbumTypeEnum' => \App\Http\GraphQL\Enums\AlbumTypeEnum::class,
        'PictureSizeEnum' => \App\Http\GraphQL\Enums\PictureSizeEnum::class,
        'CommentPeriodEnum' => \App\Http\GraphQL\Enums\CommentPeriodEnum::class,

        'CommentResult' => \App\Http\GraphQL\Unions\CommentUnion::class,
        'FavouriteResult' => \App\Http\GraphQL\Unions\FavouriteUnion::class,

        'UserInterface' => \App\Http\GraphQL\Interfaces\UserInterface::class,
    ],

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['App\Support\GraphQL\Error', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['App\Support\GraphQL\Error', 'handleErrors'],

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
        'display' => env('ENABLE_GRAPHIQL', false),
    ],
];
