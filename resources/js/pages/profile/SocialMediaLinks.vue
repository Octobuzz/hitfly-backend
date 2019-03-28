<template>
  <div class="social-links">
    <div
      v-for="link in updatedLinks"
      :key="link.id"
      class="social-links__link"
    >
      <BaseDropdown
        v-model="link.network"
        header="Выберите соц. сеть"
        :options="networkList"
        :searchable="false"
        :max-height="500"
      />
      <BaseInput
        v-model="link.contact"
        label="Имя пользователя"
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

    this.updatedLinks.push({
      id: uuid(),
      network: '',
      contact: ''
    });
  },

  methods: {
    addLink() {
      this.updatedLinks.push({
        id: uuid(),
        network: '',
        contact: ''
      });
    },
    removeLink(obsoleteId) {
      this.updatedLinks = this.updatedLinks
        .filter(({ id }) => id !== obsoleteId);
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
  width: 500px;
  height: 500px;

  &__link {

  }
}
</style>
