<template>
  <div class="edit-profile-external-auth">
    <a
      v-for="network in auth"
      :key="network.social_type"
      :href="network.link"
      :class="[
        styles[network.social_type].class,
        {
          [styles[network.social_type].class + '_active']: network.connected
        }
      ]"
      @click="onClick($event, network)"
    >
      <VkIcon v-if="network.social_type === 'vkontakte'" />
      <FbIcon v-if="network.social_type === 'facebook'" />
      <InstIcon
        v-if="network.social_type === 'instagram'"
        :white="network.connected"
      />
      <OkIcon v-if="network.social_type === 'odnoklassniki'" />

      <span class="edit-profile-external-auth__link">
        {{ styles[network.social_type].caption }}
      </span>
    </a>
  </div>
</template>

<script>
import VkIcon from 'components/icons/socialNetworks/VkontakteIcon.vue';
import FbIcon from 'components/icons/socialNetworks/FacebookIcon.vue';
import InstIcon from 'components/icons/socialNetworks/InstagramIcon.vue';
import OkIcon from 'components/icons/socialNetworks/OdnoklassnikiIcon.vue';
import gql from './gql';

const styles = {
  vkontakte: {
    class: 'edit-profile-external-auth__vk',
    caption: 'Вконтакте'
  },
  facebook: {
    class: 'edit-profile-external-auth__fb',
    caption: 'Facebook'
  },
  instagram: {
    class: 'edit-profile-external-auth__inst',
    caption: 'Instagram'
  },
  odnoklassniki: {
    class: 'edit-profile-external-auth__ok',
    caption: 'Одноклассники'
  }
};

export default {
  components: {
    VkIcon,
    FbIcon,
    InstIcon,
    OkIcon
  },

  data() {
    return {
      styles,
      auth: [],
    };
  },


  methods: {
    onClick(e, network) {
      if (!network.connected) return;

      e.preventDefault();

      this.$apollo.mutate({
        mutation: gql.mutation.CEASE_SOCIAL_NETWORK,
        variables: {
          network: network.social_type
        }
      })
        .then(() => {
          const networkInQuestion = this.auth
            .filter(nw => nw.social_type === network.social_type)[0];

          networkInQuestion.connected = false;

          this.$message(
            `Профиль ${network.social_type} отвязан`,
            'info',
            { timeout: 2000 }
          );
        })
        .catch((err) => {
          this.$message(
            'Произошла непредвиденная ошибка. Профиль не отвязан',
            'info',
            { timeout: 2000 }
          );

          console.dir(err);
        });
    }
  },

  apollo: {
    auth: {
      query: gql.query.SOCIAL_NETWORKS_AUTH,
      update({ SocialConnectQuery: auth }) {
        return auth;
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./EditProfileExternalAuth.scss"
/>
