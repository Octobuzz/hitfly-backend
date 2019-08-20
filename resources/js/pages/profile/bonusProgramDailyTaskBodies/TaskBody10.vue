<template>
  <div class="bonus-program-task-body">
    <p class="bonus-program-task-body__p">
      На протяжении:
    </p>

    <ul class="bonus-program-task-body__ul">
      <li
        v-for="(record, index) in periodsWithPoints"
        :key="index"
        class="bonus-program-task-body__li"
      >
        {{ record }}
      </li>
    </ul>

    <p class="bonus-program-task-body__p">
      В профиле пользователя дополнительно выводится
      значок: «Лидер недели», «Лидер месяца».
    </p>
  </div>
</template>

<script>
export default {
  name: 'TaskBody10',
  data() {
    return {
      periodsWithPoints: [
        'недели — 5000 баллов',
        'месяца — 10000 баллов'
      ]
    };
  }
};
</script>
