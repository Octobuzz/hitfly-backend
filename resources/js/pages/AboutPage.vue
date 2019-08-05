<template>
  <TwoColumnLayout>
    <template #left-column="{ itemContainerClass }">
      <div class="about__left-section" style="padding-top: 46px;">
        <span class="h4 about__left-section-header">
          О проекте
        </span>
      </div>
    </template>

    <template #right-column="{ paddingClass }">
      <div :class="paddingClass">
        <PageHeader class="about__page-header">
          О проекте
        </PageHeader>

        Это уникальная площадка, которая даёт возможность начинающим
        артистам по-настоящему быть услышанными, получить действительно
        полезные знания и опыт в музыкальной сфере. Обзавестись новыми,
        а главное полезными знакомствами, получить отзывы от профессионалов
        самого высокого уровня, научиться чему-то новому и как итог –
        стать популярным.

        <span class="h2 about__header-question">
          Чем мы отличаемся от других сервисов и групп в социальных сетях?
        </span>

        <div class="about__info">
          <div class="about__info-entry">
            <span class="h4 about__info-entry-title">
              Мы — площадка для профессионального старта
            </span>
            <span class="about__info-entry-content">
              У нас не просто сайт, на который вы можете загрузить свой трек
              и услышать мнение своих друзей, учителей пения или простых
              пользователей. Мы — площадка, с которой вы действительно можете
              начать свой путь в мир шоу-бизнеса. Ваш успех зависит не от
              случайности или везения, а от вашего таланта.
            </span>
          </div>

          <div class="about__info-entry">
            <span class="h4 about__info-entry-title">
              Ваши песни услышат ваши кумиры
            </span>
            <span class="about__info-entry-content">
              На нашем сайте ваши треки будут комментировать Валерий Леонтьев,
              Ирина Аллегрова, Григорий Лепс, Макс Барских и не только. Вы
              получите ценные советы, увидите свои сильные и слабые стороны и
              поймете, куда двигаться дальше, чтобы добиться такого же успеха.
            </span>
          </div>

          <div class="about__info-entry">
            <span class="h4 about__info-entry-title">
              Мы поможем вам с продвижением
            </span>
            <span class="about__info-entry-content">
              Большинство молодых исполнителей не имеют возможности продвигать свои
              треки. Мы возьмем решение финансовых и организационных вопросов на себя
              и поможем вам попасть на музыкальный рынок: запишем ваш трек на нашей
              студии (у нас записываются Лобода, Ани Лорак, Сергей Лазарев и не только),
              найдем музыкальные лейблы, с которыми вы сможете работать, на время станем
              вашим агентом.
            </span>
          </div>
        </div>

        <span class="about__portal-info">
          Наш портал — реальная возможность получить объективную оценку треков от известных
          артистов, услышать, что думают о вашем творчестве ваши кумиры.
        </span>

        <div
          v-if="!isAuthenticated"
          class="about__reg-block-section"
        >
          <span class="h4 about__reg-suggestion">
            Мы поощряем талант, и всем, кто действительно талантлив, мы поможем сделать
            свои первые шаги в мире шоу–бизнеса.
          </span>

          <div class="about__reg-block">
            <span class="h3 about__reg-block-left">
              Хочу получать комментарии звезд
            </span>
            <FormButton @press="onRegisterClick">
              Зарегистрироваться
            </FormButton>
          </div>
        </div>

        <div class="about__delimeter" />

        <span class="about__consent">
          Загружая файл, вы подтверждаете, что ваши песни соответствуют нашим
          <router-link
            to="/terms-of-use"
            class="about__link about__link_highlighted"
          >
            Условиям использования
          </router-link>,
          и вы не нарушаете авторские права.
        </span>

        <div class="about__footer">
          <span class="about__footer-left-option about__link">
            Пользовательское соглашение
          </span>
          <span class="about__footer-left-option about__link">
            Авторам
          </span>
          <span class="about__footer-left-option about__link">
            Помощь
          </span>

          <span class="about__footer-right-option about__link">
            Как загрузить песню?
          </span>
        </div>
      </div>
    </template>
  </TwoColumnLayout>
</template>

<script>
import { mapGetters } from 'vuex';
import TwoColumnLayout from 'components/TwoColumnLayout.vue';
import PageHeader from 'components/PageHeader.vue';
import FormButton from 'components/FormButton.vue';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    TwoColumnLayout,
    PageHeader,
    FormButton
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },
    ...mapGetters(['isAuthenticated'])
  },
  methods: {
    onRegisterClick() {
      window.location.href = '/login';
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AboutPage.scss"
/>
