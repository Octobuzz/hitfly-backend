<template>
  <div class="create-group">
    <div class="group-avatar">
      <button class="group-avatar-download">
        Загрузить обложку
      </button>
    </div>

    <div class="group-description">
      <BaseTag
        tag="base tag"
        @press="callMethod"
      />

      <BaseInput
        v-model="inputVal"
        label="input-label"
        :show-error="true"
        error-message="error message"
      >
        <template v-slot:icon>
          <PlugIcon/>
        </template>
      </BaseInput>

      <BaseDropdown
        v-model="ddMultiple.value"
        :header="ddMultiple.header"
        :options="ddMultiple.options"
        :multiple="true"
        :close-on-select="false"
        :searchable="false"
        :max-height="500"
      />

      <div :style="{ padding: '100px' }"></div>

      <BaseDropdown
        v-model="ddSingle.value"
        :header="ddSingle.header"
        :options="ddSingle.options"
        :searchable="false"
        :max-height="500"
      />
    </div>

    <div class="group-footer">
      <!--save button-->
    </div>
  </div>
</template>

<script>
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';
import PlugIcon from '../../sharedComponents/icons/PlugIcon.vue';

export default {
  components: {
    BaseInput,
    BaseDropdown,
    BaseTag,
    PencilIcon,
    PlugIcon
  },
  data() {
    return {
      inputVal: 'default',
      ddMultiple: {
        header: 'dd-multiple',
        value: [],
        options: ['Select option-mMMMMMMMM MMMMMMM MMMMMMMMMM', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']
      },
      ddSingle: {
        header: 'dd-single',
        value: '',
        options: ['Select option-m', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']
      }
    };
  },
  methods: {
    callMethod(e) {
      console.log(e);
    }
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./CreateGroup.sass"
/>
