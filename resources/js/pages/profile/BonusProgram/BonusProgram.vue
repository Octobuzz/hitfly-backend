<template>
  <div class="bonus-program">
    <div :class="['bonus-program__top-section', containerPaddingClass]">
      <ReturnHeader />

      <span class="h2 bonus-program__header">
        Бонусная программа
      </span>
      <p class="bonus-program__p">
        Бонусная программа HITFLY — это реальный шанс заработать баллы и заявить о себе:
        увидеть свою композицию на главной странице нашего сайта, сразиться в настоящем
        музыкальном баттле, записать свой трек в нашей студии, попить чай со звездой или
        выступить у нее на разогреве. Мы предоставляем множество возможностей и всегда
        готовы поддержать молодых и талантливых исполнителей!
      </p>
      <p class="bonus-program__p">
        Получайте баллы и продвигайтесь на вершины музыкального Олимпа с HITFLY!
      </p>
    </div>

    <div class="bonus-program__status-list">
      <UniversalScrollHorizontal
        :header-class="`h2 ${containerPaddingClass}`"
        :item-list="statusList"
        :desktop-item-width="320"
        :mobile-item-width="272"
        :desktop-space-between="24"
        :mobile-space-between="24"
      >
        <template #default="{ item: user }">
          <div class="bonus-program__status-card">
            <img
              :src="user.image"
              alt="Status image"
              class="bonus-program__status-image"
            >
            <span class="h4 bonus-program__status-name">
              {{ user.name }}
            </span>
            <span class="h4 bonus-program__status-description">
              {{ user.description }}
            </span>
          </div>
        </template>
        <template #title>
          Статусы
        </template>
      </UniversalScrollHorizontal>
    </div>

    <span :class="['h2 bonus-program__header', containerPaddingClass]">
      Уровни
    </span>

    <div class="bonus-program__middle-section">
      <div :class="['bonus-program__level-container', containerPaddingClass]">
        <div class="bonus-program__level-images-container">
          <div class="bonus-program__level-image">
            <img
              :src="levelList.novice.image"
              alt="Novice level"
            >
          </div>

          <div class="bonus-program__level-image">
            <img
              :src="levelList.fan.image"
              alt="Fan level"
            >
          </div>

          <div class="bonus-program__level-image">
            <img
              :src="levelList.connoisseur.image"
              alt="Connoisseur level"
            >
          </div>

          <div class="bonus-program__level-image">
            <img
              :src="levelList.musicLover.image"
              alt="Music lover level"
            >
          </div>
        </div>

        <div
          :class="[
            'bonus-program__level-nicks-container',
            'bonus-program__level-nicks-container_top'
          ]"
        >
          <div class="bonus-program__progress-background" />
          <div
            class="bonus-program__progress"
            :style="{ width: '75%' }"
          />

          <div
            v-for="(_, idx) in 4"
            :key="idx"
            class="bonus-program__level-nick-container"
          >
            <div class="bonus-program__level-nick" />
          </div>
        </div>

        <div
          :class="[
            'bonus-program__level-nicks-container',
            'bonus-program__level-nicks-container_bottom'
          ]"
        >
          <div class="bonus-program__progress-background" />
          <div
            class="bonus-program__progress"
            :style="{ width: '58%' }"
          />

          <div
            v-for="(_, idx) in 4"
            :key="idx"
            class="bonus-program__level-nick-container"
          >
            <div class="bonus-program__level-nick" />
          </div>
        </div>

        <div class="bonus-program__level-descriptions-container">
          <div
            v-for="level in levelList"
            :key="level.title"
            class="bonus-program__level-description"
          >
            <span class="h3 bonus-program__level-description-title">
              {{ level.title }}
            </span>
            <span class="bonus-program__level-description-text">
              {{ level.description }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="bonus-program__paid-options">
      <UniversalScrollHorizontal
        :header-class="`h2 ${containerPaddingClass}`"
        :item-list="paidOptions"
        :desktop-item-width="290"
        :mobile-item-width="272"
        :desktop-space-between="24"
        :mobile-space-between="24"
      >
        <template #default="{ item: option }">
          <!--TODO: use url for image-->
          <div
            class="bonus-program__paid-card"
            :style="{ background: '#28c18c' || `url(${option.image})` }"
          >
            <span class="h2 bonus-program__paid-card-title">
              {{ option.title }}
            </span>
            <span class="bonus-program__paid-card-description">
              {{ option.description }}
            </span>
            <span class="h4 bonus-program__paid-card-cost">
              {{ option.cost }}
            </span>

<!--            Commented button should be available in next release-->
<!--            <button class="bonus-program__paid-card-button">-->
<!--              {{ option.button }}-->
<!--            </button>-->

          </div>
        </template>
        <template #title>
          Бонусы
        </template>
      </UniversalScrollHorizontal>
    </div>

    <div :class="['bonus-program__bottom-section', containerPaddingClass]">
      <span class="h2 bonus-program__header">
        Понижение уровня и бан
      </span>

      <p class="bonus-program__paragraph">
        Статус пользователя может меняться как на более высокий, так и на более низкий.
      </p>
      <ul>
        <li class="bonus-program__li">
          Слушатель — самый низкий статус.
        </li>
        <li class="bonus-program__li">
          Музыкант — администратор может Музыканту доступ на загрузку треков, если
          Музыкант нарушил авторские права.
        </li>
        <li class="bonus-program__li">
          Критик (эксперт) — если более 30% отзывов Критика не оцениваются другими
          пользователями как полезные, или если в течение 2 месяцев Критик не оставил ни
          одного отзыва, то он понижается до Музыканта, если загружал треки, и до
          Слушателя, если нет.
        </li>
        <li class="bonus-program__li">
          Звезда / Профессиональный критик — если более 30% отзывов не оцениваются
          другими пользователями как полезные, или если в течение 2 месяцев пользователь
          не оставил ни одного отзыва, то он понижается до Музыканта, если загружал треки,
          и до Слушателя, если нет.
        </li>
      </ul>

      <p class="bonus-program__paragraph">
        Независимо от статуса пользователь может быть полностью заблокирован в случае
        некорректного поведения в рамках сервиса: накрутки баллов, нарушения правил
        использования сервиса и т. д.
      </p>
    </div>
  </div>
</template>

<script>
import listenerImage from 'images/listener-image.svg';
import rockImage from 'images/rock.svg';
import expertImage from 'images/copyright.svg';
import expertPlusImage from 'images/copyright-gradient.svg';
import starImage from 'images/star.svg';
import levelNoviceImg from 'images/level-novice.svg';
import levelFanImg from 'images/level-fan.svg';
import levelConnoisseurImg from 'images/level-connoisseur.svg';
import levelMusicLoverImg from 'images/level-music-lover.svg';
import UniversalScrollHorizontal from 'components/UniversalScrollHorizontal';
import ReturnHeader from '../ReturnHeader.vue';

export default {
  components: {
    ReturnHeader,
    UniversalScrollHorizontal
  },

  data() {
    return {
      progressPct: 20,

      statusList: [
        {
          id: 1,
          name: 'Слушатель',
          description: 'Все зарегистрированные и указавшие любимые жанры '
            + 'музыки пользователи. Слушатель может слушать и оценивать музыкальные '
            + 'треки, составлять плейлисты.',
          image: listenerImage
        },
        {
          id: 2,
          name: 'Слушатель',
          description: 'Пользователи, загрузившие свой первый трек. Все загруженные '
            + 'композиции доступны остальным посетителям. Музыкант может получать отзывы, '
            + 'лайки, участвовать в баттлах. Лучшие треки попадают в ТОП-20 и ТОП-5.',
          image: rockImage
        },
        {
          id: 3,
          name: 'Эксперт',
          description: 'Статус присваивает администратор HITFLY за отзывы на другие композиции. '
            + 'Критик оценивает творчество пользователей, словно член жюри на шоу талантов, ставит '
            + 'лайки и пишет рецензии, чем помогает другим сориентироваться в многообразии '
            + 'загруженных композиций.',
          image: expertImage
        },
        {
          id: 4,
          name: 'Эксперт +',
          description: 'Знаменитая личность, которая оценивает творчество других пользователей с '
            + 'точки зрения профессионала и делится ценными советами. Профессиональный критик и '
            + 'Звезда могут ставить лайки и писать отзывы, оставлять свое мнение и находить '
            + 'талантливых исполнителей. Статус присваивает администратор DIGICO.',
          image: expertPlusImage
        },
        {
          id: 5,
          name: 'Звезда',
          description: 'Знаменитая личность, которая оценивает творчество других пользователей '
            + 'с точки зрения профессионала и делится ценными советами. Профессиональный критик '
            + 'и Звезда могут ставить лайки и писать отзывы, оставлять свое мнение и находить '
            + 'талантливых исполнителей. Статус присваивает администратор DIGICO.',
          image: starImage
        }
      ],

      levelList: {
        novice: {
          image: levelNoviceImg,
          title: 'Новичок',
          description: 'Зарегистрируйтесь и получите уровень Новичка и свои '
            + 'первые 100 баллов.'
        },
        fan: {
          image: levelFanImg,
          title: 'Любитель',
          description: 'Заполните профиль: получите до 400 баллов и уровень Любителя. '
            + 'А удобная шкала покажет, сколько баллов вы уже накопили и сколько осталось '
            + 'до уровня Любителя.'
        },
        connoisseur: {
          image: levelConnoisseurImg,
          title: 'Знаток жанра',
          description: 'Стать Знатоком жанра может участник, прослушавший 2 500 треков '
            + 'одного или схожих жанров. Отследить их количество поможет удобный счетчик. '
            + 'Дополнительно в вашей «копилке» должно быть 3 000 баллов — при получении '
            + 'уровня баллы не списываются и остаются у пользователя.'
        },
        musicLover: {
          image: levelMusicLoverImg,
          title: 'Супер меломан',
          description: 'Для получения уровня требуется прослушать 10 000 треков в 5 '
            + 'жанрах и заработать 5000 баллов. За получение уровня баллы не списываются. '
            + 'Супер меломанов админ рассматривает на статус «Критик».'
        }
      },

      paidOptions: [
        {
          id: 1,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        },
        {
          id: 2,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        },
        {
          id: 3,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        },
        {
          id: 4,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        },
        {
          id: 5,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        },
        {
          id: 6,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 бонусов',
          button: 'Купить'
        }
      ]
    };
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./BonusProgram.scss"
/>
