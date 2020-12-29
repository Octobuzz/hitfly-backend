<template>
  <div class="bonus-program-task-body">
    <ul class="bonus-program-task-body__ul">
      <li
        v-for="(record, index) in followersWithPoints"
        :key="index"
        class="bonus-program-task-body__li"
      >
        {{ record }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'TaskBody7',
  data() {
    return {
      followersWithPoints: [
        'первый подписчик — 30 баллов',
        '10 подписчиков — 50 баллов',
        '50 подписчиков — 120 баллов',
        '100 подписчиков — 150 баллов',
        '500 подписчиков — 1200 баллов, дополнительно на странице выводится значок «500 слушателей»',
        '1000 подписчиков — 1500 баллов, дополнительно на странице выводится значок «1000  слушателей»',
        '5000 подписчиков — 12000 баллов, дополнительно на странице выводится значок «5000 слушателей»'
      ]
    };
  }
};
</script>
