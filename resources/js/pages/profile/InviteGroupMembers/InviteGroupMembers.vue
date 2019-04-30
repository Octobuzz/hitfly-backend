<template>
  <div>
    <div class="invited-members">
      <div
        v-for="member in invitedMembers"
        :key="member"
        class="invited-members__user-card"
      >
        <span class="invited-members__label">
          E-mail участника
        </span>
        <span class="invited-members__username">
          {{ member }}
        </span>
        <IconButton
          class="invited-members__close-button"
          @press="removeMember(member)"
        >
          <CrossIcon/>
        </IconButton>
      </div>
    </div>

    <div class="invited-members__input-row">
      <BaseInput
        v-model="userInput"
        class="invited-members__user-input"
        label="E-mail участника"
        @press:enter="addMember"
      />
      <FormButton
        class="invited-members__save-button"
        modifier="secondary"
        @press="addMember"
      >
        Пригласить
      </FormButton>
    </div>
  </div>
</template>

<script>
import BaseInput from 'components/BaseInput.vue';
import FormButton from 'components/FormButton.vue';
import IconButton from 'components/IconButton.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';

export default {
  components: {
    BaseInput,
    FormButton,
    IconButton,
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
  src="./InviteGroupMembers.scss"
/>
