<template>
  <div class="contacts">
    <span class="h2 contacts__header">
      Контакты
    </span>
    <ul class="contacts__info-block contacts__communication">
      <li>Tel: +7 499 322 81 43</li>
      <li>Whatsupp/Viber/Telegram: <span class="contacts__phone">+7 985 773 86 71</span></li>
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
    </ul>

    <ul class="contacts__info-block contacts__address">
      <li>Наш адрес:</li>
      <li>Космодамианская набережная, дом 38 строение 1,</li>
      <li>(Метро Таганская/Павелецкая)</li>
      <li>Пн — Вс: 10:00 — 22:00</li>
    </ul>

    <span>
      Если у вас есть вопросы, направляйте их к нам на почту
      <a
        class="contacts__link"
        href="mailto:info@myhitfly.ru"
      >
        info@myhitfly.ru
      </a>.
    </span>
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
    line-height: 24px;
    list-style-type: none;
    padding-left: 0;
    margin: {
      top: 0;
      bottom: 48px;
    }
  }

  &__header {
    padding-bottom: 0;
  }

  &__communication {
    margin-bottom: 24px;
  }

  &__address {
    margin-bottom: 24px;
  }
}

@media (max-width: 700px) {
  .contacts {
    padding: 16px;
    font-size: 14px;

    &__header {
      font-size: 20px;
    }
  }
}

@media (max-width: 345px) {
  .contacts__phone {
    display: block;
  }
}
</style>
