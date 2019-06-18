<template>
  <footer class="footer">
    <div class="footer__left footer-info">
      <img class="footer-info__img" src="/images/test_ava.jpg"/>
      <div class="footer-info__text">
        <a class="footer-info__song">Мальчики Мальчики Мальчики Мальчики Мальчики</a>
        <a class="footer-info__author">АлоэВера АлоэВера АлоэВера АлоэВера АлоэВера – Алимоно – 2018</a>
      </div>
    </div>

    <div class="footer__center">
      <div class="footer-play">
        <span>
          <IconButton>
            <PlayPreviousIcon />
          </IconButton>
        </span>

        <span class="footer-play__play">
          <IconButton>
            <PlayIcon />
          </IconButton>
        </span>

        <span>
          <IconButton>
            <PlayNextIcon />
          </IconButton>
        </span>
      </div>

      <div class="progress">
        <div class="progress__bar progress__text">
          <div class="progress__time">0:09</div>
          <div class="progress__time">3:39</div>
        </div>
        <div class="progress__bar progress__progress">
          <div class="progress__line" style="width: 28.25119%;">
            <div class="progress__head"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer__right">FOOTER</div>
  </footer>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PlayNextIcon from 'components/icons/PlayNextIcon.vue';
import PlayPreviousIcon from 'components/icons/PlayPreviousIcon.vue';

export default {
  components: {
    IconButton,
    PlayIcon,
    PlayNextIcon,
    PlayPreviousIcon
  }
};
</script>
