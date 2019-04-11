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
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import CrossIcon from '../../sharedComponents/icons/CrossIcon.vue';

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
  }

  &__network-select,
  &__username-input {
    flex-grow: 1;
    width: 0;
  }

  &__network-select {
    margin-right: 18px;
  }

  &__add-button {
    display: block;
    margin-left: auto;
    max-width: 180px;
  }

  &__close-button {
    &-row {
      display: none;
    }

    &_desktop {
      box-sizing: border-box;
      position: absolute;
      width: auto;
      height: 21px;
      right: -43px;
      top: 50%;
      padding: 5px;
      margin: {
        top: auto;
        bottom: auto;
      };
      transform: translateY(-50%);
    }

    &_mobile {
      display: none;
    }
  }
}

@media screen and (max-width: 767px) {
  .social-links {
    &__row {
      flex-wrap: wrap;
    }

    &__network-select {
      margin-right: 9px;
    }

    &__close-button {
      &-row {
        display: block;
        width: 100%;
      }

      &_desktop {
        display: none;
      }

      &_mobile {
        color: $color_pink;
        display: initial;
        padding: {
          top: 12px;
          bottom: 8px;
          left: 16px;
        }
      }
    }
  }
}
</style>
