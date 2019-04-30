<template>
  <div class="social-links">
    <div
      v-for="link in updatedLinks"
      :key="link.id"
      class="social-links__row"
    >
      <BaseDropdown
        v-model="link.network"
        class="social-links__network-select"
        title="Соц. сеть"
        :options="networkList"
        :searchable="false"
        :max-height="500"
        @input="onInput"
      />
      <BaseInput
        v-model="link.username"
        class="social-links__username-input"
        label="Имя"
        @input="onInput"
      />
      <button
        class="social-links__close-button_desktop"
        @click="removeLink(link.id)"
      >
        <CrossIcon/>
      </button>

      <div class="social-links__close-button-row">
        <button
          class="social-links__close-button_mobile"
          @click="removeLink(link.id)"
        >
          Удалить ссылку
        </button>
      </div>
    </div>

    <FormButton
      class="social-links__add-button"
      modifier="secondary"
      @press="addLink"
    >
      Добавить ссылку
    </FormButton>
  </div>
</template>

<script>
import BaseInput from 'components/BaseInput.vue';
import BaseDropdown from 'components/BaseDropdown.vue';
import FormButton from 'components/FormButton.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';

export default {
  components: {
    BaseInput,
    BaseDropdown,
    FormButton,
    CrossIcon
  },

  props: {
    links: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      updatedLinks: [],

      // TODO: make fetch
      networkList: [
        'instagram',
        'facebook',
        'vkontakte',
        'odnoklassniki'
      ]
    };
  },

  computed: {
    computedLinks() {
      return this.updatedLinks.map(link => ({
        network: link.network,
        username: link.username
      }));
    }
  },

  watch: {
    links: {
      handler(linksList) {
        this.onUpdate(linksList);
      },
      immediate: true
    }
  },

  methods: {
    genId() {
      return Math.random().toString(36);
    },

    addLink() {
      this.updatedLinks.push({
        id: this.genId(),
        network: '',
        username: ''
      });
    },

    removeLink(obsoleteId) {
      this.updatedLinks = this.updatedLinks
        .filter(({ id }) => id !== obsoleteId);

      this.onInput();
    },

    onInput() {
      this.$emit('update:links', this.computedLinks);
    },

    onUpdate(links) {
      const { updatedLinks, genId } = this;
      const newLinks = [];

      const areNotLinksEqual = (link1, link2) => (
        (link1.username !== link2.username)
          || (link1.network !== link2.network)
      );

      // TODO: remove stale links

      links.forEach((link) => {
        const isNew = updatedLinks.every(
          updatedLink => areNotLinksEqual(updatedLink, link)
        );

        if (isNew) {
          newLinks.push(link);
        }
      });

      if (newLinks.length === 0 && updatedLinks.length !== 0) {
        return;
      }

      newLinks.forEach(newLink => updatedLinks.push({
        ...newLink,
        id: genId(),
      }));

      if (updatedLinks.length === 0) {
        updatedLinks.push({
          id: genId(),
          network: '',
          username: ''
        });
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./SocialMediaLinks.scss"
/>
