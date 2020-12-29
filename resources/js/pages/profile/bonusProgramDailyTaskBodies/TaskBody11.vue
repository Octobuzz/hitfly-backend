<template>
  <div class="bonus-program-task-body">
    <ul class="bonus-program-task-body__ul">
      <li
        v-for="(record, index) in placesWithPoints"
        :key="index"
        class="bonus-program-task-body__li"
      >
        {{ record }}
      </li>
    </ul>

    <p class="bonus-program-task-body__p">
      В профиле пользователя дополнительно выводится
      значок «ТОП-50» в период нахождения в тройке.
    </p>
  </div>
</template>

<script>
export default {
  name: 'TaskBody11',
  data() {
    return {
      placesWithPoints: [
        'третье место — 3000 баллов',
        'второе место — 4000 баллов',
        'первое место — 5000 баллов'
      ]
    };
  }
};
</script>
