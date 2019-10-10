<template>
  <div class="contacts">
    <span class="h2">
      Контакты
    </span>
    <span>
      Если у вас есть вопросы, направляйте их к нам на почту
      <a
        class="contacts__link"
        href="mailto:info@myhitfly.ru"
      >
        info@myhitfly.ru
      </a>.
    </span>

    <ul class="contacts__info-block">
      <li>Космодамианская набережная, дом 38 строение 1,</li>
      <li>(Метро Таганская/Павелецкая)</li>
    </ul>

    <ul class="contacts__info-block">
      <li>Tel: +7 499 322 81 43</li>
      <li>Whatsupp/Viber/Telegram: +7 985 773 86 71</li>
      <li>
        E-mail:
        <a
          class="contacts__link"
          href="mailto:info@myhitfly.ru"
        >
          info@myhitfly.ru
        </a>
        |
        <a
          class="contacts__link"
          href="mailto:info@myhitfly.ru"
        >
          studio@2rockmusic.com
        </a>
      </li>
      <li>Пн — Вс: 10:00 — 22:00</li>
    </ul>
  </div>
</template>

<style
  scoped
  lang="scss"
>
@import "~scss/_variables.scss";

.contacts {
  padding: 32px;

  &__link {
    color: $link_color_active;
  }

  &__info-block {
    line-height: 32px;
    list-style-type: none;
    padding-left: 0;
    margin: {
      top: 48px;
      bottom: 48px;
    }
  }

  .info-entry {

  }
}
</style>
