<template>
  <div class="bonus-program">
    <div :class="['bonus-program__top-section', containerPaddingClass]">
      <ReturnHeader />

      <span class="h2 bonus-program__header">
        Бонусная программа
      </span>
      <p class="bonus-program__p">
        Бонусная программа HitFly — это реальный шанс заработать баллы и заявить о себе:
        увидеть свою композицию на главной странице нашего сайта, сразиться в настоящем
        музыкальном баттле, записать свою песню в нашей студии, попить чай со звездой или
        выступить у нее на разогреве. Мы предоставляем множество возможностей и всегда
        готовы поддержать молодых и талантливых исполнителей!
      </p>
      <p class="bonus-program__p">
        Получайте баллы и продвигайтесь на вершины музыкального Олимпа с HitFly!
      </p>
    </div>

    <div class="bonus-program__status-list">
      <UniversalScrollHorizontal
        :header-class="`h2 ${containerPaddingClass}`"
        :item-list="statusList"
        :desktop-item-width="320"
        :mobile-item-width="272"
        :desktop-space-between="32"
        :mobile-space-between="32"
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

        <div class="bonus-program__level-bonus-count">
          5 000 баллов
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

        <div class="bonus-program__level-track-count">
          2 500 песен
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
            :style="{ width: '54%' }"
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

        <p class="bonus-program__level-description-text">
          При получении уровня мы не списываем заработанные Вами баллы.
        </p>
      </div>
    </div>

    <div :class="['bonus-program__task-section', containerPaddingClass]">
      <span class="h2 bonus-program__task-section-title">
        Выполнение заданий
      </span>
      <span class="h3 bonus-program__task-section-subtitle">
        Разовые
      </span>

      <div class="bonus-program__task-oneoff-section-top">
        <span class="bonus-program__task-index">
          1
        </span>
        <span class="h4 bonus-program__task-title">
          Заполнение профиля
        </span>
        <p class="bonus-program__task-p">
          Баллы начисляются за каждый заполненный пункт:
        </p>
        <ul class="bonus-program__task-ul">
          <li
            v-for="item in taskLists.oneoff[1]"
            :key="item"
            class="bonus-program__task-li"
          >
            {{ item }}
          </li>
        </ul>
      </div>

      <div class="bonus-program__task-oneoff-section-bottom">
        <div class="bonus-program__task-oneoff-bottom">
          <span class="bonus-program__task-title-wrapper">
            <span class="bonus-program__task-index">
              2
            </span>
            <span class="h4 bonus-program__task-title">
              Загрузка первой песни
            </span>
          </span>
          <p class="bonus-program__task-p">
            Начисляется один раз - 30 баллов
          </p>
        </div>

        <div class="bonus-program__task-oneoff-bottom">
          <span class="bonus-program__task-title-wrapper">
            <span class="bonus-program__task-index">
              3
            </span>
            <span class="h4 bonus-program__task-title">
              Создание первого альбома
            </span>
          </span>
          <p class="bonus-program__task-p">
            Начисляется один раз - 50 баллов
          </p>
        </div>

        <div class="bonus-program__task-oneoff-bottom">
          <span class="bonus-program__task-title-wrapper">
            <span class="bonus-program__task-index">
              4
            </span>
            <span class="h4 bonus-program__task-title">
              Создание первого плейлиста
            </span>
          </span>
          <p class="bonus-program__task-p">
            Начисляется один раз - 50 баллов
          </p>
        </div>
      </div>

      <hr class="bonus-program__task-section-delimiter">
    </div>

    <div class="bonus-program__task-daily-section">
      <UniversalScrollHorizontal
        :header-class="containerPaddingClass"
        :item-list="dailyTasks"
        :desktop-item-width="272"
        :mobile-item-width="272"
        :desktop-space-between="32"
        :mobile-space-between="32"
      >
        <template #default="{ item: task }">
          <div class="bonus-program__task-daily">
            <span class="bonus-program__task-title-wrapper">
              <span class="bonus-program__task-index">
                {{ task.id }}
              </span>
              <span class="h4 bonus-program__task-title">
                {{ task.title }}
              </span>
            </span>
            <component :is="task.body"/>
          </div>
        </template>

        <template #title>
          <span
            :class="[
              'h3',
              'bonus-program__task-section-subtitle',
              'bonus-program__task-section-subtitle_daily'
            ]"
          >
            Ежедневные
          </span>
        </template>
      </UniversalScrollHorizontal>
    </div>

    <div class="bonus-program__paid-options">
      <UniversalScrollHorizontal
        :header-class="`h2 ${containerPaddingClass}`"
        :item-list="paidOptions"
        :desktop-item-width="290"
        :mobile-item-width="272"
        :desktop-space-between="32"
        :mobile-space-between="32"
      >
        <template #default="{ item: option }">
          <!--TODO: use url for image-->
          <div
            class="bonus-program__paid-card"
            :style="{
              backgroundImage: `url(${option.image})`,
              backgroundSize: 'cover'
            }"
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
          На что потратить баллы
        </template>
      </UniversalScrollHorizontal>
    </div>

    <div :class="['bonus-program__bottom-section', containerPaddingClass]">
      <span class="h2 bonus-program__header">
        Понижение статуса и блокировка
      </span>

      <p class="bonus-program__p">
        Статус пользователя может меняться как на более высокий, так и на более низкий.
      </p>
      <ul class="bonus-program__bottom-section-ul">
        <li class="bonus-program__li">
          Слушатель — начальный статус.
        </li>
        <li class="bonus-program__li">
          Музыкант — Музыканту может быть заблокирован доступ на загрузку музыки, если
          будет выявлено нарушение авторских прав.
        </li>
        <li class="bonus-program__li">
          Критик — Критик, который загружал песни, может понизить уровень до Музыканта,
          если более 30% отзывов не оцениваются другими пользователями как полезные,
          или если в течение 2 месяцев Критик не оставил ни одного отзыва, и может
          понизить до Слушателя, если Критик не загружал песни.
        </li>
        <li class="bonus-program__li">
          Звездный эксперт / Профессиональный критик — Звездный эксперт, который загружал песни, может
          понизить уровень до Музыканта, если более 30% отзывов не оцениваются другими
          пользователями как полезные, или если в течение 2 месяцев Звездный эксперт не оставил
          ни одного отзыва, и может понизить до Слушателя, если Звездный эксперт не загружал песни.
        </li>
      </ul>

      <p class="bonus-program__p">
        Независимо от статуса пользователь может быть полностью заблокирован в случае
        некорректного поведения в рамках сервиса: накрутки баллов, нарушения правил
        использования сервиса и т. д.
      </p>
    </div>
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
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
import * as bonusProgramDailyTaskBodies from '../bonusProgramDailyTaskBodies';
import gql from './gql';

export default {
  components: {
    ReturnHeader,
    UniversalScrollHorizontal,
    ...bonusProgramDailyTaskBodies
  },

  mixins: [containerPaddingClass],

  data() {
    return {
      progressPct: 20,

      statusList: [
        {
          id: 1,
          name: 'Слушатель',
          description: 'Все зарегистрированные и указавшие любимые жанры '
            + 'музыки пользователи. Слушатель может слушать и оценивать музыкальные '
            + 'песни, составлять плейлисты.',
          image: listenerImage
        },
        {
          id: 2,
          name: 'Музыкант',
          description: 'Пользователи, загрузившие свою первую песню. Все загруженные '
            + 'композиции доступны остальным посетителям. Музыкант может получать отзывы, '
            + 'лайки, участвовать в баттлах. Лучшие песни попадают в ТОП-50 и ТОП-5.',
          image: rockImage
        },
        {
          id: 3,
          name: 'Критик',
          description: 'Статус присваивает администратор HitFly за отзывы на другие композиции. '
            + 'Критик оценивает творчество пользователей, словно член жюри на шоу талантов, ставит '
            + 'лайки и пишет рецензии, чем помогает другим сориентироваться в многообразии '
            + 'загруженных композиций.',
          image: expertImage
        },
        {
          id: 4,
          name: 'Эксперт +',
          description: 'Знатоки музыкальной индустрии, которые известны в своей области.'
            + 'Главное их отличие от обычного слушателя в том, что они могут дать профессиональную '
            + 'оценку по существу и без особых эмоций. Наши эксперты имеют солидный багаж знаний и '
            + 'смогут поделиться с музыкантами ценными советами.',
          image: expertPlusImage
        },
        {
          id: 5,
          name: 'Звездный эксперт',
          description: 'Это знаменитости, достигшие успеха в музыкальной индустрии. Они прославились '
            + 'своим талантом и являются профессионалами своего дела. Их рекомендации точно будут полезны '
            + 'для музыканта благодаря тому, что они сами прошли по лестнице ведущей к успеху и не по '
            + 'наслышке знают о музыке.',
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
          description: 'Заполните профиль: получите до 400 баллов и уровень Любителя.'
        },
        connoisseur: {
          image: levelConnoisseurImg,
          title: 'Знаток жанра',
          description: 'Стать Знатоком жанра может участник, прослушавший 2 500 песен '
            + 'одного или схожих жанров. Отследить их количество поможет удобный счетчик. '
            + 'Дополнительно в вашей «копилке» должно быть 3 000 баллов.'
        },
        musicLover: {
          image: levelMusicLoverImg,
          title: 'Супер меломан',
          description: 'Для получения уровня требуется прослушать 10 000 песен в 5 '
            + 'жанрах и заработать 5000 баллов. При получении уровня мы не списываем '
            + 'заработанные вами баллы. Супер меломанов админ рассматривает на статус «Критик».'
        }
      },

      paidOptions: [
        {
          id: 1,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        },
        {
          id: 2,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        },
        {
          id: 3,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        },
        {
          id: 4,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        },
        {
          id: 5,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        },
        {
          id: 6,
          image: '',
          title: 'Услуги в студии',
          description: 'Запись музыкальных инструментов',
          cost: '11500 баллов',
          button: 'Купить'
        }
      ],
      taskLists: {
        oneoff: {
          1: [
            'обложка профиля и аватар',
            'имя пользователя',
            'никнейм',
            'город',
            'описание - год начала карьеры',
            'описание - жанры',
            'описание - деятельность',
            'соц. сети (за первую соц. сеть)'
          ]
        }
      },
      dailyTasks: [
        {
          id: 1,
          title: 'Прослушивние 10 песен',
          body: 'TaskBody1'
        },
        {
          id: 2,
          title: 'Оценка 5 новых песен ',
          body: 'TaskBody2'
        },
        {
          id: 3,
          title: 'Ежедневный вход в приложение',
          body: 'TaskBody3'
        },
        {
          id: 4,
          title: 'Получение 10 лайков от других пользователей на песню',
          body: 'TaskBody4'
        },
        {
          id: 5,
          title: 'Получение 10 лайков от других пользователей на альбом',
          body: 'TaskBody5'
        },
        {
          id: 6,
          title: 'Популярный плейлист',
          body: 'TaskBody6'
        },
        {
          id: 7,
          title: 'Получение баллов за подписчика',
          body: 'TaskBody7'
        },
        {
          id: 8,
          title: 'Победа в музыкальном батле',
          body: 'TaskBody8'
        },
        {
          id: 9,
          title: 'Попадание в плейлист ТОП-50 (обновление ежедневно)',
          body: 'TaskBody9'
        },
        {
          id: 10,
          title: 'Непрерывное нахождение в ТОП-5',
          body: 'TaskBody10'
        },
        {
          id: 11,
          title: 'Рецензия от критика',
          body: 'TaskBody11'
        }
      ]
    };
  },

  apollo: {
    paidOptions: {
      client: 'public',
      query: gql.query.PAID_OPTIONS,
      update({ products: { data } }) {
        const productMapper = (product) => {
          const {
            id,
            description,
            price,
            name: title,
            image: productImage
          } = product;

          const image = productImage.find(
            img => img.size === 'size_290x290'
          ).url;

          const cost = `${price} баллов`;

          return {
            id,
            title,
            description,
            cost,
            image,
            button: 'Купить'
          };
        };

        return data.map(productMapper);
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./BonusProgram.scss"
/>
