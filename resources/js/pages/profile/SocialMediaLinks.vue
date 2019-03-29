<template>
  <div class="social-links">
    <div
      v-for="link in updatedLinks"
      :key="link.id"
      class="social-links__row"
    >
      <BaseDropdown
        v-model="link.network"
        header="Выберите соц. сеть"
        :options="networkList"
        :searchable="false"
        :max-height="500"
        @input="onInput"
      />
      <BaseInput
        v-model="link.username"
        label="Имя пользователя"
        @input="onInput"
      />
      <button
        class="social-links__button-close"
        @click="removeLink(link.id)"
      >
        <CrossIcon/>
      </button>
    </div>

    <BaseButtonFormSecondary @press="addLink()">
      Добавить ссылку
    </BaseButtonFormSecondary>
  </div>
</template>

<script>
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseButtonFormSecondary from '../../sharedComponents/BaseButtonFormSecondary.vue';
import CrossIcon from '../../sharedComponents/icons/CrossIcon.vue';

export default {
  components: {
    BaseInput,
    BaseDropdown,
    BaseButtonFormSecondary,
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
        'vk'
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
      console.log(this.computedLinks);

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
>
@import '../../../sass/variables';

.social-links {
  &__row {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-bottom: 16px;

    & > :not(:last-child) {
      flex-grow: 1;
      width: 0;
    }

    & > :first-child {
      margin-right: 18px;
    }
  }

  & > :last-child {
    display: block;
    margin-left: auto;
    max-width: 180px;
  }

  &__button-close {
    box-sizing: border-box;
    position: absolute;
    width: auto;
    height: 21px;
    right: -29px;
    top: 50%;
    padding: 5px;
    margin: {
      top: auto;
      bottom: auto;
    };
    transform: translateY(-50%)
  }
}
</style>
