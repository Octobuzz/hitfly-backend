<template>
  <div>
    <div class="invited-members">
      <!--TODO: make as div-->
      <BaseInput
        v-for="member in invitedMembers"
        :key="member"
        :value="member"
        label="E-mail участника"
      >
        <template v-slot:icon>
          <button
            class="invited-members__button-close"
            @click="removeMember(member)"
          >
            <CrossIcon/>
          </button>
        </template>
      </BaseInput>
    </div>

    <BaseInput
      v-model="userInput"
      label="E-mail участника"
    />

    <BaseButtonFormSecondary
      @press="addMember"
    >
      Пригласить
    </BaseButtonFormSecondary>
  </div>
</template>

<script>
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseButtonFormSecondary from '../../sharedComponents/BaseButtonFormSecondary.vue';
import CrossIcon from '../../sharedComponents/icons/CrossIcon.vue';

export default {
  components: {
    BaseInput,
    BaseButtonFormSecondary,
    CrossIcon
  },
  model: {
    prop: 'invitedMembers'
  },
  props: {
    invitedMembers: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      userInput: ''
    };
  },
  methods: {
    addMember() {
      const { invitedMembers, userInput } = this;

      if (userInput === '') return;

      if (invitedMembers.includes(userInput)) return;

      invitedMembers.push(userInput);
      this.userInput = '';
    },
    removeMember(member) {
      const { invitedMembers } = this;

      this.$emit(
        'input',
        invitedMembers.filter(invited => invited !== member)
      );
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
.invited-members {
  & > * {
    display: inline-block;
  }

  & input,
  & .label {
    width: auto !important;
  }
}
</style>
