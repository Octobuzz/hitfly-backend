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
        @input="onChange"
      />
      <BaseInput
        v-model="link.username"
        label="Имя пользователя"
        @input="onChange"
      />
      <button @click="removeLink(link.id)">
        remove link
      </button>
    </div>

    <BaseButtonFormSecondary @press="addLink()">
      Добавить ссылку
    </BaseButtonFormSecondary>
  </div>
</template>

<script>
import uuid from 'uuid/v4';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseButtonFormSecondary from '../../sharedComponents/BaseButtonFormSecondary.vue';

export default {
  components: {
    BaseInput,
    BaseDropdown,
    BaseButtonFormSecondary
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

  created() {
    // avoid parent mutation and generate unique keys
    this.links.forEach(link => this.updatedLinks.push({
      id: uuid(),
      ...link
    }));

    if (this.updatedLinks.length === 0) {
      this.updatedLinks.push({
        id: uuid(),
        network: '',
        username: ''
      });
    }
  },

  methods: {
    addLink() {
      this.updatedLinks.push({
        id: uuid(),
        network: '',
        username: ''
      });
    },
    removeLink(obsoleteId) {
      this.updatedLinks = this.updatedLinks
        .filter(({ id }) => id !== obsoleteId);

      this.onChange();
    },
    onChange() {
      const freshUpdatedLinks = this.updatedLinks.map(link => ({
        network: link.network,
        username: link.username
      }));

      this.$emit('change', freshUpdatedLinks);
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

    & > :not(:last-child) {
      width: 0;
      flex-grow: 1;
      margin-right: 22px;
    }
  }
}
</style>
