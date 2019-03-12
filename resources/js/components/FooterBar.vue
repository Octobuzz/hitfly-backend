<template>
  <footer class="footer">
    <div class="footer__left footer-info">
      <img class="footer-info__img" src="/src/images/test_ava.jpg"/>
      <div class="footer-info__text">
        <a class="footer-info__song">Мальчики Мальчики Мальчики Мальчики Мальчики</a>
        <a class="footer-info__author">АлоэВера АлоэВера АлоэВера АлоэВера АлоэВера – Алимоно – 2018</a>
      </div>
    </div>
    <div class="footer__center">
      <div class="footer-play">
        <button class="button-icon">
          <svg viewBox="0 0 13 16" xmlns="http://www.w3.org/2000/svg"><g transform="matrix(-1 0 0 1 13 0)" fill-rule="evenodd"><rect x="11" width="2" height="16" rx=".5"/><path d="M.81234752.64987802L9.511957 7.6095656c.21563097.17250477.25059166.48715096.07808689.70278192a.49999964.49999964 0 0 1-.07808689.07808688L.81234752 15.350122c-.21563096.1725048-.53027715.1375441-.70278193-.0780869C.03864038 15.1833786 0 15.0732233 0 14.9596876V1.04031242c0-.27614237.22385763-.5.5-.5.11353575 0 .223691.03864038.31234752.1095656z"/></g></svg>
        </button>
        <button class="button-icon footer-play__play">
          <svg viewBox="0 0 14 22" xmlns="http://www.w3.org/2000/svg"><path d="M.80891078.63557275L13.4996156 10.6068408c.2171358.1706067.2548551.4849342.0842484.70207a.50000237.50000237 0 0 1-.0842484.0842484L.80891078 21.3644272c-.21713582.1706068-.53146324.1328875-.70206995-.0842483C.03762507 21.1920861 0 21.0833 0 20.9712681V1.02873192c0-.27614237.22385763-.5.5-.5.11203195 0 .220818.03762508.30891078.10684083z" fill-rule="evenodd"/></svg>
        </button>
        <button class="button-icon">
          <svg viewBox="0 0 13 16" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd"><rect x="11" width="2" height="16" rx=".5"/><path d="M.81234752.64987802L9.511957 7.6095656c.21563097.17250477.25059166.48715096.07808689.70278192a.49999964.49999964 0 0 1-.07808689.07808688L.81234752 15.350122c-.21563096.1725048-.53027715.1375441-.70278193-.0780869C.03864038 15.1833786 0 15.0732233 0 14.9596876V1.04031242c0-.27614237.22385763-.5.5-.5.11353575 0 .223691.03864038.31234752.1095656z"/></g></svg>
        </button>
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
  export default{
    data(){
      return{

      }
    }
  }
</script>
