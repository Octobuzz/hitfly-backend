import gql from './gql';

// varInQuestion should represent variable with entity fetched by apollo.
// The type of the entity should be specified as second argument.
// It is taken into account that the entity could be undefined on null.
// followMixinCallback is expected on the instance to be called after mutation (if exists)

function refetchWatchedUsers($apollo) {
  $apollo.query({
    query: gql.query.WATCHED_USERS,
    fetchPolicy: 'network-only',
    error(err) {
      console.dir(err);
    }
  });
}

function refetchWatchedGroups($apollo) {
  $apollo.query({
    query: gql.query.WATCHED_GROUPS,
    fetchPolicy: 'network-only',
    error(err) {
      console.dir(err);
    }
  });
}

function handleClientState(args) {
  const {
    $apollo,
    mutation,
    variables,
    ownerIsGroup,
    ownerIsWatched,
    ownerName,
    followMixinCallback,
    $message
  } = args;

  return $apollo.mutate({
    mutation,
    variables,
    update(store, { data: { deleteFollow } }) {
      if (!ownerIsGroup) {
        refetchWatchedUsers($apollo);
      } else {
        refetchWatchedGroups($apollo);
      }

      if (deleteFollow !== null) return;

      try {
        if (!ownerIsGroup) {
          const userData = store.readQuery({
            query: gql.query.USER,
            variables
          });

          const update = {
            followersCount: userData.user.followersCount - 1,
            iWatch: false
          };

          store.writeQuery({
            query: gql.query.USER,
            variables,
            data: {
              ...userData,
              user: {
                ...userData.user,
                ...update
              }
            }
          });
        } else {
          const musicGroupData = store.readQuery({
            query: gql.query.MUSIC_GROUP,
            variables
          });

          const update = {
            followersCount: musicGroupData.musicGroup.followersCount - 1,
            iWatch: false
          };

          store.writeQuery({
            query: gql.query.MUSIC_GROUP,
            variables,
            data: {
              ...musicGroupData,
              musicGroup: {
                ...musicGroupData.musicGroup,
                ...update
              }
            }
          });
        }
      } catch (err) {
        // no data was found
      }
    }
  })
    .then(() => {
      $message(
        `Теперь вы ${ownerIsWatched ? 'не ' : ''}следите за ${ownerName}`,
        'info',
        { timeout: 2000 }
      );
    })
    .catch((err) => {
      console.dir(err);
    })
    .then(() => {
      if (typeof followMixinCallback === 'function') {
        followMixinCallback();
      }
    });
}

const trackOrAlbumMixinFactory = varInQuestion => ({
  computed: {
    ownerIsGroup() {
      const entity = this[varInQuestion];

      return entity && entity.musicGroup;
    },

    ownerIsWatched() {
      const entity = this[varInQuestion];

      if (!entity) return false;

      return this.ownerIsGroup
        ? entity.musicGroup.iWatch
        : entity.user.iWatch;
    }
  },

  methods: {
    onWatchOwnerPress() {
      const { ownerIsGroup, ownerIsWatched } = this;
      const entity = this[varInQuestion];
      let mutation;

      if (ownerIsGroup && ownerIsWatched) {
        mutation = gql.mutation.STOP_WATCHING_MUSIC_GROUP;
      }

      if (ownerIsGroup && !ownerIsWatched) {
        mutation = gql.mutation.START_WATCHING_MUSIC_GROUP;
      }

      if (!ownerIsGroup && ownerIsWatched) {
        mutation = gql.mutation.STOP_WATCHING_USER;
      }

      if (!ownerIsGroup && !ownerIsWatched) {
        mutation = gql.mutation.START_WATCHING_USER;
      }

      let owner;
      let ownerName;

      if (ownerIsGroup) {
        owner = entity.musicGroup;
        ownerName = entity.musicGroup.name;
      } else {
        owner = entity.user;
        ownerName = entity.user.username;
      }

      const variables = { id: +owner.id };

      let followMixinCallback;

      if (typeof this.followMixinCallback === 'function') {
        followMixinCallback = this.followMixinCallback.bind(this);
      }

      handleClientState({
        $apollo: this.$apollo,
        mutation,
        variables,
        ownerIsGroup,
        ownerIsWatched,
        ownerName,
        followMixinCallback,
        $message: this.$message.bind(this)
      });
    }
  }
});

const userMixinFactory = varInQuestion => ({
  computed: {
    ownerIsGroup() {
      return false;
    },

    ownerIsWatched() {
      const user = this[varInQuestion];

      if (!user) return false;

      return user.iWatch;
    }
  },

  methods: {
    onWatchOwnerPress() {
      const { ownerIsWatched } = this;
      const user = this[varInQuestion];
      let mutation;

      if (ownerIsWatched) {
        mutation = gql.mutation.STOP_WATCHING_USER;
      } else {
        mutation = gql.mutation.START_WATCHING_USER;
      }

      let followMixinCallback;

      if (typeof this.followMixinCallback === 'function') {
        followMixinCallback = this.followMixinCallback.bind(this);
      }

      handleClientState({
        vm: this,
        $apollo: this.$apollo,
        variables: { id: +user.id },
        ownerName: user.username,
        ownerIsGroup: false,
        ownerIsWatched,
        mutation,
        followMixinCallback,
        $message: this.$message.bind(this)
      });
    }
  }
});

const blankMixinFactory = () => ({
  methods: {
    onWatchOwnerPress() {
      if (typeof this.followMixinCallback === 'function') {
        this.followMixinCallback();
      }
    }
  }
});

export default function mixinFactory(varInQuestion, gqlType) {
  const typename = gqlType.toLowerCase();
  let mixin;

  switch (typename) {
    case 'track':
    case 'album':
      mixin = trackOrAlbumMixinFactory(varInQuestion);
      break;

    case 'user':
      mixin = userMixinFactory(varInQuestion);
      break;

    default:
      if (process.env.NODE_ENV === 'development') {
        throw new Error('Specified gql type for followMixin is not valid');
      }
      mixin = blankMixinFactory();
  }

  return mixin;
}
