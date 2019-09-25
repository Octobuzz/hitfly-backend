<script>
import { mapGetters, mapMutations } from 'vuex';
import MY_PROFILE from './queries/myProfile.graphql';

export default {
  name: 'ProfileDataProvider',

  data() {
    return {};
  },

  computed: {
    ...mapGetters(['isAuthenticated'])
  },

  beforeDestroy() {
    this.setMyId(null);
    this.setRoles([]);
  },

  methods: {
    ...mapMutations('profile', [
      'setMyId',
      'setRoles'
    ])
  },

  render() {
    return this.$scopedSlots.default({});
  },

  apollo: {
    myProfile() {
      return {
        query: MY_PROFILE,
        update({ myProfile: { id, roles } }) {
          this.setMyId(id);
          this.setRoles(roles.map(role => role.slug));
        },
        error(err) {
          console.dir(err);
        },
        skip() {
          return !this.isAuthenticated;
        }
      };
    }
  }
};
</script>
