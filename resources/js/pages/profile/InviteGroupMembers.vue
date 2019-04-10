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
          class="invited-members__button-close"
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
import BaseInput from '../../sharedComponents/BaseInput.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import IconButton from '../../sharedComponents/IconButton.vue';
import CrossIcon from '../../sharedComponents/icons/CrossIcon.vue';

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
>
@import '../../../sass/variables';

.invited-members {
  &__user-card {
    font-size: 14px;
    display: inline-flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    min-width: 130px;
    height: 50px;
    padding-left: 16px;
    margin: {
      right: 16px;
      bottom: 16px;
    };
    border: 1px solid $borderColor;
    border-radius: $border-radius;
    background: $color_2;
  }

  &__label {
    display: block;
    font-size: 14px;
    color: $color_3;
    position: absolute;
    top: 16px;
    left: 17px;
    user-select: none;
    cursor: pointer;
    transition: $trns;
    transform: translate(-13%, -10px) scale(.75);
  }

  &__username {
    transform: translateY(7px);
  }

  &__input-row {
    display: flex;
  }

  &__user-input {
    flex-grow: 1;
  }

  &__save-button {
    max-width: 180px;
    margin-left: 16px;
  }

  &__button-close {
    opacity: 1;
    transform: scale(.75);
    background: transparent !important;

    & svg {
      opacity: 1 !important;
      fill: #a6a6a6;
      transform: translateY(3px);

      &:hover {
        fill: black;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .invited-members {
    &__input-row {
      flex-wrap: wrap;
    }

    &__user-input {
      width: 100%;
    }

    &__save-button {
      margin: {
        top: 16px;
        left: auto;
      }
    }
  }
}
</style>
