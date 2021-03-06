<?php

use App\Http\GraphQL\Enums\LikeTypeEnum;
use App\Http\GraphQL\InputObject\LikeInput;
use App\Http\GraphQL\Mutations\AddLikeMutation;
use App\Http\GraphQL\Mutations\UnLikeMutation;
use App\Http\GraphQL\Type\LikeLifehack;
use App\Http\GraphQL\Unions\LikeUnion;

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
                'bonusTypes' => \App\Http\GraphQL\Query\BonusTypesQuery::class,
                'watchingUser' => \App\Http\GraphQL\Query\WatchingUserQuery::class,
                'watchingMusicGroup' => \App\Http\GraphQL\Query\WatchingMusicGroupQuery::class,
                'SocialConnectQuery' => \App\Http\GraphQL\Query\SocialConnectQuery::class,
                'GetTopFifty' => \App\Http\GraphQL\Query\GetTopFifty::class,
                'GetListenedNow' => \App\Http\GraphQL\Query\GetListenedNow::class,
                'GetListenedNowUser' => \App\Http\GraphQL\Query\GetListenedNowUser::class,
                'NotificationQuery' => \App\Http\GraphQL\Query\NotificationQuery::class,
                'trackBelongsCollection' => \App\Http\GraphQL\Query\TrackBelongsCollectionQuery::class,
                'TracksByIdsQuery' => \App\Http\GraphQL\Query\TracksByIdsQuery::class,
                'topTrackForUser' => \App\Http\GraphQL\Query\TopTrackForUser::class,
                'news' => \App\Http\GraphQL\Query\NewsQuery::class,
                'newsOne' => \App\Http\GraphQL\Query\NewsOne::class,
                'TopWeeklyQuery' => \App\Http\GraphQL\Query\TopWeeklyQuery::class,
                'myTracksSearch' => \App\Http\GraphQL\Query\MyTracksSearch::class,
                'requestsForComments' => \App\Http\GraphQL\Query\RequestsForCommentsQuery::class,
                'product' => \App\Http\GraphQL\Query\Store\ProductQuery::class,
                'products' => \App\Http\GraphQL\Query\Store\ProductsQuery::class,
                'genres' => \App\Http\GraphQL\Query\Genre\GenresQuery::class,
                'lifehack' => \App\Http\GraphQL\Query\LifehacksQuery::class,
                'tag' => \App\Http\GraphQL\Query\TagQuery::class,
                'search' => \App\Http\GraphQL\Query\Search\SearchQuery::class,
                'searchEssence' => \App\Http\GraphQL\Query\Search\SearchEssenceQuery::class,
                'favoriteLifehackQuery' => \App\Http\GraphQL\Query\FavoriteLifehackQuery::class,
            ],
            'mutation' => [
                'uploadTrack' => \App\Http\GraphQL\Mutations\Track\UploadTrackMutation::class,
                'updateTrack' => \App\Http\GraphQL\Mutations\Track\UpdateTrackMutation::class,
                'listeningTrack' => \App\Http\GraphQL\Mutations\Track\ListeningTrackMutation::class,
                'removeTrackFromMusicGroup' => \App\Http\GraphQL\Mutations\Track\RemoveTrackFromMusicGroupMutation::class,

                'createMusicGroup' => \App\Http\GraphQL\Mutations\CreateMusicGroupMutation::class,
                'updateMusicGroup' => \App\Http\GraphQL\Mutations\UpdateMusicGroupMutation::class,
                'deletedMusicGroup' => \App\Http\GraphQL\Mutations\DeleteMusicGroupMutation::class,

                'createComment' => \App\Http\GraphQL\Mutations\CreateCommentMutation::class,
                'updateComment' => \App\Http\GraphQL\Mutations\UpdateCommentMutation::class,
                'rateComment' => \App\Http\GraphQL\Mutations\RateCommentMutation::class,
                'requestForComment' => \App\Http\GraphQL\Mutations\RequestForCommentMutation::class,

                'createCollection' => \App\Http\GraphQL\Mutations\Collection\CreateCollectionMutation::class,
                'updateCollection' => \App\Http\GraphQL\Mutations\Collection\UpdateCollectionMutation::class,

                'updateMyProfile' => \App\Http\GraphQL\Mutations\User\UpdateMyProfileMutation::class,
                'updatePassword' => \App\Http\GraphQL\Mutations\User\UpdatePasswordMutation::class,
                'updateEmail' => \App\Http\GraphQL\Mutations\User\UpdateEmailMutation::class,
                'removeMeMutation' => \App\Http\GraphQL\Mutations\User\RemoveMeMutation::class,
                'registrationMutation' => \App\Http\GraphQL\Mutations\User\RegistrationMutation::class,
                'loginMutation' => \App\Http\GraphQL\Mutations\User\LoginMutation::class,
                'resetPasswordMutation' => \App\Http\GraphQL\Mutations\User\ResetPasswordMutation::class,
                'logoutMutation' => \App\Http\GraphQL\Mutations\User\LogoutMutation::class,

                'addInCollection' => \App\Http\GraphQL\Mutations\Collection\AddInCollectionMutation::class,
                'deleteCollection' => \App\Http\GraphQL\Mutations\Collection\DeleteCollectionMutation::class,
                'removeTrackFromCollection' => \App\Http\GraphQL\Mutations\RemoveTrackFromCollectionMutation::class,

                'addToFavourites' => \App\Http\GraphQL\Mutations\AddToFavouriteMutation::class,
                'deleteFromFavourite' => \App\Http\GraphQL\Mutations\DeleteFromFavouriteMutation::class,

                'deleteTrackMutation' => \App\Http\GraphQL\Mutations\Track\DeleteTrackMutation::class,
                'createAlbum' => \App\Http\GraphQL\Mutations\CreateAlbumMutation::class,
                'updateAlbum' => \App\Http\GraphQL\Mutations\UpdateAlbumMutation::class,
                'deleteAlbum' => \App\Http\GraphQL\Mutations\DeleteAlbumMutation::class,
                'removeTrackFromAlbum' => \App\Http\GraphQL\Mutations\RemoveTrackFromAlbumMutation::class,
                'addFollow' => \App\Http\GraphQL\Mutations\FollowMutation::class,
                'deleteFollow' => \App\Http\GraphQL\Mutations\DeleteFollowMutation::class,
                'UseBonusesMutation' => \App\Http\GraphQL\Mutations\UseBonusesMutation::class,
                'RemoveSocialConnect' => \App\Http\GraphQL\Mutations\RemoveSocialConnect::class,
                'buyProductMutation' => \App\Http\GraphQL\Mutations\Store\BuyProductMutation::class,

                'addLike' => AddLikeMutation::class,
                'unLikeMutation' => UnLikeMutation::class,
            ],
            'middleware' => ['guest'],
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
                'bonusTypes' => \App\Http\GraphQL\Query\BonusTypesQuery::class,
                'watchingUser' => \App\Http\GraphQL\Query\WatchingUserQuery::class,
                'watchingMusicGroup' => \App\Http\GraphQL\Query\WatchingMusicGroupQuery::class,
                'SocialConnectQuery' => \App\Http\GraphQL\Query\SocialConnectQuery::class,
                'GetTopFifty' => \App\Http\GraphQL\Query\GetTopFifty::class,
                'GetListenedNow' => \App\Http\GraphQL\Query\GetListenedNow::class,
                'GetListenedNowUser' => \App\Http\GraphQL\Query\GetListenedNowUser::class,
                'NotificationQuery' => \App\Http\GraphQL\Query\NotificationQuery::class,
                'trackBelongsCollection' => \App\Http\GraphQL\Query\TrackBelongsCollectionQuery::class,
                'TracksByIdsQuery' => \App\Http\GraphQL\Query\TracksByIdsQuery::class,
                'topTrackForUser' => \App\Http\GraphQL\Query\TopTrackForUser::class,
                'news' => \App\Http\GraphQL\Query\NewsQuery::class,
                'newsOne' => \App\Http\GraphQL\Query\NewsOne::class,
                'TopWeeklyQuery' => \App\Http\GraphQL\Query\TopWeeklyQuery::class,
                'myTracksSearch' => \App\Http\GraphQL\Query\MyTracksSearch::class,
                'requestsForComments' => \App\Http\GraphQL\Query\RequestsForCommentsQuery::class,
                'product' => \App\Http\GraphQL\Query\Store\ProductQuery::class,
                'products' => \App\Http\GraphQL\Query\Store\ProductsQuery::class,
                'genres' => \App\Http\GraphQL\Query\Genre\GenresQuery::class,
                'search' => \App\Http\GraphQL\Query\Search\SearchQuery::class,
                'searchEssence' => \App\Http\GraphQL\Query\Search\SearchEssenceQuery::class,
            ],
            'mutation' => [
                'uploadTrack' => \App\Http\GraphQL\Mutations\Track\UploadTrackMutation::class,
                'updateTrack' => \App\Http\GraphQL\Mutations\Track\UpdateTrackMutation::class,
                'listeningTrack' => \App\Http\GraphQL\Mutations\Track\ListeningTrackMutation::class,
                'removeTrackFromMusicGroup' => \App\Http\GraphQL\Mutations\Track\RemoveTrackFromMusicGroupMutation::class,

                'createMusicGroup' => \App\Http\GraphQL\Mutations\CreateMusicGroupMutation::class,
                'updateMusicGroup' => \App\Http\GraphQL\Mutations\UpdateMusicGroupMutation::class,
                'deletedMusicGroup' => \App\Http\GraphQL\Mutations\DeleteMusicGroupMutation::class,

                'createComment' => \App\Http\GraphQL\Mutations\CreateCommentMutation::class,
                'updateComment' => \App\Http\GraphQL\Mutations\UpdateCommentMutation::class,
                'rateComment' => \App\Http\GraphQL\Mutations\RateCommentMutation::class,
                'requestForComment' => \App\Http\GraphQL\Mutations\RequestForCommentMutation::class,

                'createCollection' => \App\Http\GraphQL\Mutations\Collection\CreateCollectionMutation::class,
                'updateCollection' => \App\Http\GraphQL\Mutations\Collection\UpdateCollectionMutation::class,

                'updateMyProfile' => \App\Http\GraphQL\Mutations\User\UpdateMyProfileMutation::class,
                'updatePassword' => \App\Http\GraphQL\Mutations\User\UpdatePasswordMutation::class,
                'updateEmail' => \App\Http\GraphQL\Mutations\User\UpdateEmailMutation::class,
                'removeMeMutation' => \App\Http\GraphQL\Mutations\User\RemoveMeMutation::class,
                'registrationMutation' => \App\Http\GraphQL\Mutations\User\RegistrationMutation::class,
                'loginMutation' => \App\Http\GraphQL\Mutations\User\LoginMutation::class,

                'addInCollection' => \App\Http\GraphQL\Mutations\Collection\AddInCollectionMutation::class,
                'deleteCollection' => \App\Http\GraphQL\Mutations\Collection\DeleteCollectionMutation::class,
                'removeTrackFromCollection' => \App\Http\GraphQL\Mutations\RemoveTrackFromCollectionMutation::class,

                'addToFavourites' => \App\Http\GraphQL\Mutations\AddToFavouriteMutation::class,
                'deleteFromFavourite' => \App\Http\GraphQL\Mutations\DeleteFromFavouriteMutation::class,

                'addLike' => AddLikeMutation::class,
                'unLikeMutation' => UnLikeMutation::class,

                'deleteTrackMutation' => \App\Http\GraphQL\Mutations\Track\DeleteTrackMutation::class,

                'createAlbum' => \App\Http\GraphQL\Mutations\CreateAlbumMutation::class,
                'updateAlbum' => \App\Http\GraphQL\Mutations\UpdateAlbumMutation::class,
                'deleteAlbum' => \App\Http\GraphQL\Mutations\DeleteAlbumMutation::class,
                'removeTrackFromAlbum' => \App\Http\GraphQL\Mutations\RemoveTrackFromAlbumMutation::class,

                'addFollow' => \App\Http\GraphQL\Mutations\FollowMutation::class,
                'deleteFollow' => \App\Http\GraphQL\Mutations\DeleteFollowMutation::class,

                'UseBonusesMutation' => \App\Http\GraphQL\Mutations\UseBonusesMutation::class,

                'RemoveSocialConnect' => \App\Http\GraphQL\Mutations\RemoveSocialConnect::class,

                'buyProductMutation' => \App\Http\GraphQL\Mutations\Store\BuyProductMutation::class,
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
        'FavouriteLifehack' => \App\Http\GraphQL\Type\FavouriteLifehack::class,
        'SocialLinks' => \App\Http\GraphQL\Type\SocialLinksType::class,
        'GroupMembersType' => \App\Http\GraphQL\Type\GroupMembersType::class,
        'ImageSizesType' => \App\Http\GraphQL\Type\ImageSizesType::class,
        'RoleType' => \App\Http\GraphQL\Type\RoleType::class,
        'FollowUser' => \App\Http\GraphQL\Type\FollowUserType::class,
        'FollowMusicGroup' => \App\Http\GraphQL\Type\FollowMusicGroupType::class,
        'BonusTypes' => \App\Http\GraphQL\Type\BonusTypesType::class,
        'WatchableUserType' => \App\Http\GraphQL\Type\WatchableUserType::class,
        'WatchableMusicGroupType' => \App\Http\GraphQL\Type\WatchableMusicGroupType::class,
        'SocialConnectType' => \App\Http\GraphQL\Type\SocialConnectType::class,
        'NotificationType' => \App\Http\GraphQL\Type\NotificationType::class,
        'TrackBelongsCollection' => \App\Http\GraphQL\Type\TrackBelongsCollectionType::class,
        'NewsType' => \App\Http\GraphQL\Type\NewsType::class,
        'OperationType' => \App\Http\GraphQL\Type\OperationType::class,
        'OrderType' => \App\Http\GraphQL\Type\OrderType::class,
        'ProductType' => \App\Http\GraphQL\Type\ProductType::class,
        'SearchType' => \App\Http\GraphQL\Type\SearchType::class,
        'LifehackType' => \App\Http\GraphQL\Type\LifehackType::class,
        'TagType' => \App\Http\GraphQL\Type\TagType::class,
        'LikeLifehack' => LikeLifehack::class,

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
        'FollowInput' => \App\Http\GraphQL\InputObject\FollowInput::class,
        'TrackFilterInput' => \App\Http\GraphQL\InputObject\TrackFilterInput::class,
        'AlbumFilterInput' => \App\Http\GraphQL\InputObject\AlbumFilterInput::class,
        'CollectionFilterInput' => \App\Http\GraphQL\InputObject\CollectionFilterInput::class,
        'CommentsTrackFilterInput' => \App\Http\GraphQL\InputObject\CommentsTrackFilterInput::class,
        'SocialLinkFilterInput' => \App\Http\GraphQL\InputObject\Filter\SocialLinkFilterInput::class,
        'LifehackFilterInput' => \App\Http\GraphQL\InputObject\Filter\LifehackFilterInput::class,
        'LikeInput' => LikeInput::class,

        'CommentTypeEnum' => \App\Http\GraphQL\Enums\CommentTypeEnum::class,
        'FavouriteTypeEnum' => \App\Http\GraphQL\Enums\FavouriteTypeEnum::class,
        'GenderType' => \App\Http\GraphQL\Enums\GenderTypeEnum::class,
        'SocialLinksTypeEnum' => \App\Http\GraphQL\Enums\SocialLinksTypeEnum::class,
        'AvatarSizeEnum' => \App\Http\GraphQL\Enums\AvatarSizeEnum::class,
        'AlbumTypeEnum' => \App\Http\GraphQL\Enums\AlbumTypeEnum::class,
        'PictureSizeEnum' => \App\Http\GraphQL\Enums\PictureSizeEnum::class,
        'CommentPeriodEnum' => \App\Http\GraphQL\Enums\CommentPeriodEnum::class,
        'FollowTypeEnum' => \App\Http\GraphQL\Enums\FollowTypeEnum::class,
        'BonusProgramUserStatusEnum' => \App\Http\GraphQL\Enums\BonusProgramUserStatusEnum::class,
        'UserRoleEnum' => \App\Http\GraphQL\Enums\UserRoleEnum::class,
        'SearchTypeEnum' => \App\Http\GraphQL\Enums\SearchTypeEnum::class,
        'ProductTypeEnum' => \App\Http\GraphQL\Enums\ProductTypeEnum::class,
        'RedirectEnum' => \App\Http\GraphQL\Enums\RedirectEnum::class,
        'LikeTypeEnum' => LikeTypeEnum::class,

        'CommentResult' => \App\Http\GraphQL\Unions\CommentUnion::class,
        'FavouriteResult' => \App\Http\GraphQL\Unions\FavouriteUnion::class,
        'FollowResult' => \App\Http\GraphQL\Unions\FollowUnion::class,
        'AttributeResult' => \App\Http\GraphQL\Unions\AttributesUnion::class,
        'SearchResult' => \App\Http\GraphQL\Unions\SearchUnion::class,
        'LikeResult' => LikeUnion::class,

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
