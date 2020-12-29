<template>
  <div class="bonus-program-task-body">
    <p class="bonus-program-task-body__p">
      Запускается недельный счетчик:
    </p>

    <ul class="bonus-program-task-body__ul">
      <li
        v-for="(record, index) in daysWithPoints"
        :key="index"
        class="bonus-program-task-body__li"
      >
        {{ record }}
      </li>
    </ul>

    <p class="bonus-program-task-body__p">
      Всего — 126 балла за первую неделю.
      За последующие — 350 (при ежедневном входе).
    </p>

    <p class="bonus-program-task-body__p">
      Счетчик сбрасывается только в том случае,
      если пользователь не заходит в приложение.
    </p>
  </div>
</template>

<script>
export default {
  name: 'TaskBody3',
  data() {
    return {
      daysWithPoints: [
        '2 дня подряд — 3 балла',
        '3 дня подряд — 6 баллов',
        '4 дня подряд — 12 баллов',
        '5 дней подряд — 20 баллов',
        '6 дней подряд — 35 баллов',
        '7 дней подряд — 50 баллов и далее по 50 баллов ежедневно'
      ]
    };
  }
};
</script>
