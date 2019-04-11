<template>
  <main class="main">
    <div class="main__profile">
      <div class="profileNav">
        <ul>
          <li :class="{active: activeTab == 1}" @click="setActiveTab(1)">Моя музыка</li>
          <li :class="{active: activeTab == 2}" @click="setActiveTab(2)">Мне нравится</li>
          <li :class="{active: activeTab == 3}" @click="setActiveTab(3)">Статистика</li>
          <li :class="{active: activeTab == 4}" @click="setActiveTab(4)">Отзывы</li>
        </ul>
      </div>
      <transition-group name="fade" :duration="300">
        <div class="profileContent" v-show="activeTab === 1" key="1">
          <div class="profileContentSection">
            <div class="profileContentSection__heading">
              <h3>Песни</h3>
              <button class="button">Все песни</button>
            </div>
            <div class="profileContentSection__body">
              <div class="songList">
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">1</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Драмы больше нет</p>
                  </div>
                  <div class="songItem__artist">
                    <p>Полина Гагарина</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">2</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Мальчики</p>
                  </div>
                  <div class="songItem__artist">
                    <p>АлоэВера – Алимоно – 2018</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">3</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Драмы больше нет</p>
                  </div>
                  <div class="songItem__artist">
                    <p>Полина Гагарина</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="profileContentSection">
            <div class="profileContentSection__heading">
              <h3></h3>
            </div>
            <div class="profileContentSection__body"></div>
          </div>
          <div class="profileContentSection">
            <div class="profileContentSection__heading">
              <h3>Плейлисты</h3>
            </div>
            <div class="profileContentSection__body">
              <div class="playlists">
                <div class="playlist" style="background-image: url(/images/playlist1.png)">
                  <div class="playlist__header">
                    <p>Плейлист дня</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
                <div class="playlist" style="background-image: url(/images/playlist2.png)">
                  <div class="playlist__header">
                    <p>Спорт</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
                <div class="playlist" style="background-image: url(/images/playlist3.png)">
                  <div class="playlist__header">
                    <p>Треки, которые точно западут в душу!</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="profileContent" v-show="activeTab === 2" key="2">
          <div class="profileContentSection">
            <div class="profileContentSection__heading">
              <h3>Любимые песни</h3>
              <button class="button">Все песни</button>
            </div>
            <div class="profileContentSection__body">
              <div class="songList">
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">1</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Драмы больше нет</p>
                  </div>
                  <div class="songItem__artist">
                    <p>Полина Гагарина</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">2</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Мальчики</p>
                  </div>
                  <div class="songItem__artist">
                    <p>АлоэВера – Алимоно – 2018</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
                <div class="songItem">
                  <div class="songItem__info">
                    <p class="songItem__number">3</p>
                    <img src="/images/track.jpg" alt="" class="songItem__img">
                    <div class="like-icon button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.7 6.38028169C17.9 5.49295775 16.7 5 15.5 5c-.9 0-1.8.29577465-2.5.88732394-.4.29577465-.7.5915493-1 1.08450705-.3-.3943662-.6-.7887324-1-1.08450705C10.3 5.29577465 9.4 5 8.5 5c-1.2 0-2.4.49295775-3.2 1.38028169C4.5 7.26760563 4 8.45070423 4 9.73239437c0 1.38028173.5 2.56338023 1.6 3.84507043 1 1.1830986 2.4 2.3661972 4 3.6478873.6.4929578 1.2.9859155 1.8 1.5774648.2.0985916.4.1971831.6.1971831.2 0 .4-.0985915.6-.1971831.6-.5915493 1.3-1.084507 1.8-1.5774648 1.6-1.3802817 3-2.5633803 4-3.6478873 1.1-1.2816902 1.6-2.4647887 1.6-3.84507043 0-1.28169014-.4-2.56338029-1.3-3.35211268z" fill="#343434" fill-rule="nonzero"/></svg>
                    </div>
                    <p class="songItem__trackName">Драмы больше нет</p>
                  </div>
                  <div class="songItem__artist">
                    <p>Полина Гагарина</p>
                  </div>
                  <div class="songItem__tools">
                    <button class="addToPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(90 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                    <button class="showMore button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><circle cx="5.5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18.5" cy="12" r="1.5"/></g></svg>
                    </button>
                    <p class="songItem__timestamp">4:05</p>
                    <button class="removeFromPlaylist button-icon">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#313131" fill-rule="evenodd"><rect transform="rotate(135 12 12)" x="11" y="4" width="2" height="16" rx="1"/><rect transform="rotate(45 12 12)" x="11" y="4" width="2" height="16" rx="1"/></g></svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="profileContentSection">
            <div class="profileContentSection__heading">
              <h3>Мои подборки</h3>
            </div>
            <div class="profileContentSection__body">
              <div class="playlists">
                <div class="playlist" style="background-image: url(/images/playlist1.png)">
                  <div class="playlist__header">
                    <p>Плейлист дня</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
                <div class="playlist" style="background-image: url(/images/playlist2.png)">
                  <div class="playlist__header">
                    <p>Спорт</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
                <div class="playlist" style="background-image: url(/images/playlist3.png)">
                  <div class="playlist__header">
                    <p>Треки, которые точно западут в душу!</p>
                    <span>7 декабря</span>
                  </div>
                  <p class="playlist__number">254 трека</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="profileContent" v-show="activeTab === 3" key="3">
          <div class="profileContentSection">
            <div class="profileGraph">
              <div class="profileGraph__header">
                <div class="profileGraph__colors">
                  <p>Понравилось</p>
                  <p>Слушатели</p>
                  <p>Комментарии</p>
                </div>
                <div class="profileGraph__period">
                  <div class="input-text">
                    <input type="date" placeholder="Поиск по названию песни">
                    <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <g id="icon-input-calendar" stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g id="Group">
                          <path d="M2,4 L2,14 L14,14 L14,4 L2,4 Z M2,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,14 C16,15.1045695 15.1045695,16 14,16 L2,16 C0.8954305,16 0,15.1045695 0,14 L0,4 C0,2.8954305 0.8954305,2 2,2 Z" id="Rectangle" fill-rule="nonzero"></path>
                          <rect id="Rectangle" x="3" y="0" width="2" height="3" rx="0.5"></rect>
                          <rect id="Rectangle" x="11" y="0" width="2" height="3" rx="0.5"></rect>
                        </g>
                      </g>
                    </svg>
                  </div>
                  <div class="input-text">
                    <input type="date" placeholder="Поиск по названию песни">
                    <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <g id="icon-input-calendar" stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g id="Group">
                          <path d="M2,4 L2,14 L14,14 L14,4 L2,4 Z M2,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,14 C16,15.1045695 15.1045695,16 14,16 L2,16 C0.8954305,16 0,15.1045695 0,14 L0,4 C0,2.8954305 0.8954305,2 2,2 Z" id="Rectangle" fill-rule="nonzero"></path>
                          <rect id="Rectangle" x="3" y="0" width="2" height="3" rx="0.5"></rect>
                          <rect id="Rectangle" x="11" y="0" width="2" height="3" rx="0.5"></rect>
                        </g>
                      </g>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="profileGraphBody"><img src="/images/graph.png" alt=""></div>
            </div>
          </div>
          <div class="profileContentSection">
            <div class="profileMainStats">
              <div class="profileStat">
                <div class="profileStat__header">
                  <p class="goesUp">Слушатели</p>
                  <div class="arrows">
                    <div class="arrows__prev disabled"></div>
                    <div class="arrows__next"></div>
                  </div>
                </div>
                <div class="profileStat__body">
                  <div class="profileStat__bodyStatistics">
                    <p>123</p>
                    <span>слушателя добавили песни в свой плейлист</span>
                    <button class="button">Посмотреть слушателей</button>
                  </div>
                </div>
              </div>
              <div class="profileStat">
                <div class="profileStat__header">
                  <p>Любимая песня</p>
                </div>
                <div class="profileStat__body">
                  <div class="profileStat__bodyPlayer">
                    <img src="/images/track.jpg" alt="">
                    <div class="feedbackPlayer__infoButtons">
                      <button class="addToPlaylist button-icon">
                        <svg viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <g id="edd" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect id="Rectangle" fill="#313131" x="18" y="0" width="4" height="40" rx="2"></rect>
                            <rect id="Rectangle" fill="#313131" transform="translate(20.000000, 20.000000) rotate(-270.000000) translate(-20.000000, -20.000000) " x="18" y="0" width="4" height="40" rx="2"></rect>
                          </g>
                        </svg>
                      </button>
                      <div class="feedbackPlayer__buttonGroup">
                        <div class="wtext button-icon active">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510">
                            <path d="M255 489.6l-35.7-35.7C86.7 336.6 0 257.55 0 160.65 0 81.6 61.2 20.4 140.25 20.4c43.35 0 86.7 20.4 114.75 53.55C283.05 40.8 326.4 20.4 369.75 20.4 448.8 20.4 510 81.6 510 160.65c0 96.9-86.7 175.95-219.3 293.25L255 489.6z"/>
                          </svg>
                          <p>23&nbsp;000</p>
                        </div>
                      </div>
                      <button class="showMore button-icon">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                          <g fill="#313131" fill-rule="evenodd">
                            <circle cx="5.5" cy="12" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="18.5" cy="12" r="1.5"/>
                          </g>
                        </svg>
                      </button>
                    </div>
                    <div class="feedbackPlayer__spec">
                      <div class="feedbackPlayer__specTrack">Мальчики</div>
                      <div class="feedbackPlayer__specArtist">АлоэВера – Алимоно – 2018</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="profileStat">
                <div class="profileStat__header">
                  <p>Песня–лидер по отзывам</p>
                </div>
                <div class="profileStat__body">
                  <div class="profileStat__bodyPlayer">
                    <img src="/images/track.jpg" alt="">
                    <div class="feedbackPlayer__infoButtons">
                      <button class="addToPlaylist button-icon">
                        <svg viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <g id="edd" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect id="Rectangle" fill="#313131" x="18" y="0" width="4" height="40" rx="2"></rect>
                            <rect id="Rectangle" fill="#313131" transform="translate(20.000000, 20.000000) rotate(-270.000000) translate(-20.000000, -20.000000) " x="18" y="0" width="4" height="40" rx="2"></rect>
                          </g>
                        </svg>
                      </button>
                      <div class="feedbackPlayer__buttonGroup">
                        <div class="wtext button-icon active">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510">
                            <path d="M255 489.6l-35.7-35.7C86.7 336.6 0 257.55 0 160.65 0 81.6 61.2 20.4 140.25 20.4c43.35 0 86.7 20.4 114.75 53.55C283.05 40.8 326.4 20.4 369.75 20.4 448.8 20.4 510 81.6 510 160.65c0 96.9-86.7 175.95-219.3 293.25L255 489.6z"/>
                          </svg>
                          <p>23&nbsp;000</p>
                        </div>
                      </div>
                      <button class="showMore button-icon">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                          <g fill="#313131" fill-rule="evenodd">
                            <circle cx="5.5" cy="12" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="18.5" cy="12" r="1.5"/>
                          </g>
                        </svg>
                      </button>
                    </div>
                    <div class="feedbackPlayer__spec">
                      <div class="feedbackPlayer__specTrack">Мальчики</div>
                      <div class="feedbackPlayer__specArtist">АлоэВера – Алимоно – 2018</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="profileContent" v-show="activeTab === 4" key="4">
          <div class="profileContentSection profileContentFeedbacks">
            <div class="feedbackFilters">
              <button class="button">
                <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="icon-input-calendar" stroke="none" stroke-width="1" fill-rule="evenodd">
                    <g id="Group">
                      <path d="M2,4 L2,14 L14,14 L14,4 L2,4 Z M2,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,14 C16,15.1045695 15.1045695,16 14,16 L2,16 C0.8954305,16 0,15.1045695 0,14 L0,4 C0,2.8954305 0.8954305,2 2,2 Z" id="Rectangle" fill-rule="nonzero"></path>
                      <rect id="Rectangle" x="3" y="0" width="2" height="3" rx="0.5"></rect>
                      <rect id="Rectangle" x="11" y="0" width="2" height="3" rx="0.5"></rect>
                    </g>
                  </g>
                </svg>
                За год
              </button>
              <button class="button active">
                <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="icon-input-calendar" stroke="none" stroke-width="1" fill-rule="evenodd">
                    <g id="Group">
                      <path d="M2,4 L2,14 L14,14 L14,4 L2,4 Z M2,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,14 C16,15.1045695 15.1045695,16 14,16 L2,16 C0.8954305,16 0,15.1045695 0,14 L0,4 C0,2.8954305 0.8954305,2 2,2 Z" id="Rectangle" fill-rule="nonzero"></path>
                      <rect id="Rectangle" x="3" y="0" width="2" height="3" rx="0.5"></rect>
                      <rect id="Rectangle" x="11" y="0" width="2" height="3" rx="0.5"></rect>
                    </g>
                  </g>
                </svg>
                За неделю
              </button>
              <button class="button">
                <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="icon-input-calendar" stroke="none" stroke-width="1" fill-rule="evenodd">
                    <g id="Group">
                      <path d="M2,4 L2,14 L14,14 L14,4 L2,4 Z M2,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,14 C16,15.1045695 15.1045695,16 14,16 L2,16 C0.8954305,16 0,15.1045695 0,14 L0,4 C0,2.8954305 0.8954305,2 2,2 Z" id="Rectangle" fill-rule="nonzero"></path>
                      <rect id="Rectangle" x="3" y="0" width="2" height="3" rx="0.5"></rect>
                      <rect id="Rectangle" x="11" y="0" width="2" height="3" rx="0.5"></rect>
                    </g>
                  </g>
                </svg>
                За месяц
              </button>
            </div>
            <div class="feedbackFilters__search">
              <div class="input-text">
                <input type="text" placeholder="Поиск по названию песни">
                <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="icon-input-search" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M7,10.5 C8.93299662,10.5 10.5,8.93299662 10.5,7 C10.5,5.06700338 8.93299662,3.5 7,3.5 C5.06700338,3.5 3.5,5.06700338 3.5,7 C3.5,8.93299662 5.06700338,10.5 7,10.5 Z M7,12.5 C3.96243388,12.5 1.5,10.0375661 1.5,7 C1.5,3.96243388 3.96243388,1.5 7,1.5 C10.0375661,1.5 12.5,3.96243388 12.5,7 C12.5,10.0375661 10.0375661,12.5 7,12.5 Z" id="Oval" fill="#313131" fill-rule="nonzero"></path>
                    <path d="M10.2665678,11.6797626 C9.89114528,11.2746992 9.91517397,10.6419903 10.3202374,10.2665678 C10.7253008,9.89114528 11.3580097,9.91517397 11.7334322,10.3202374 L14.7334322,13.5570977 C15.1088547,13.9621611 15.084826,14.59487 14.6797626,14.9702925 C14.2746992,15.345715 13.6419903,15.3216863 13.2665678,14.9166229 L10.2665678,11.6797626 Z" id="Line" fill="#313131" fill-rule="nonzero"></path>
                  </g>
                </svg>
              </div>
            </div>
          </div>
          <div class="profileContentSection">
            <div class="feedbackTracks">
              <div class="feedbackTrack">
                <div class="feedbackPlayer">
                  <div class="feedbackPlayer__info">
                    <img src="images/track.jpg" alt="">
                    <div class="feedbackPlayer__infoButtons">
                      <button class="addToPlaylist button-icon">
                        <svg viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <g id="edd" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect id="Rectangle" fill="#313131" x="18" y="0" width="4" height="40" rx="2"></rect>
                            <rect id="Rectangle" fill="#313131" transform="translate(20.000000, 20.000000) rotate(-270.000000) translate(-20.000000, -20.000000) " x="18" y="0" width="4" height="40" rx="2"></rect>
                          </g>
                        </svg>
                      </button>
                      <div class="feedbackPlayer__buttonGroup">
                        <div class="wtext button-icon active">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510">
                            <path d="M255 489.6l-35.7-35.7C86.7 336.6 0 257.55 0 160.65 0 81.6 61.2 20.4 140.25 20.4c43.35 0 86.7 20.4 114.75 53.55C283.05 40.8 326.4 20.4 369.75 20.4 448.8 20.4 510 81.6 510 160.65c0 96.9-86.7 175.95-219.3 293.25L255 489.6z"/>
                          </svg>
                          <p>23&nbsp;000</p>
                        </div>
                      </div>
                      <button class="showMore button-icon">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                          <g fill="#313131" fill-rule="evenodd">
                            <circle cx="5.5" cy="12" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="18.5" cy="12" r="1.5"/>
                          </g>
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="feedbackPlayer__spec">
                    <div class="feedbackPlayer__specTrack">Мальчики</div>
                    <div class="feedbackPlayer__specArtist">АлоэВера – Алимоно – 2018</div>
                    <div class="feedbackPlayer__specOsc">
                      <img src="/images/spec.png" alt="">
                    </div>
                  </div>
                </div>
                <div class="feedbackPosts">
                  <div class="feedbackPost">
                    <img src="/images/acc.jpg" alt="">
                    <div class="feedbackPostBody">
                      <div class="feedbackPostBody__header">
                        <p>Полина Гагарина</p>
                        <span>вчера</span>
                      </div>
                      <p class="feedbackPostBody__post">Песня для истинных поклонников рока!! Хоть альбом был выпущен в 1980 он и поныне есть очень актуальным. Всегда с друзьями его слушаем и наслаждаемся этим истинным источником музыки. Сама группа AC/DC есть очень популярной. Поэтому ней никогда не рано интересоваться.</p>
                    </div>
                  </div>
                  <div class="feedbackTrack__showAll">
                    <button class="button">Все отзывы по этой песне</button>
                  </div>
                </div>
              </div>
              <div class="feedbackTrack">
                <div class="feedbackPlayer">
                  <div class="feedbackPlayer__info">
                    <img src="images/track.jpg" alt="">
                    <div class="feedbackPlayer__infoButtons">
                      <button class="addToPlaylist button-icon">
                        <svg viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <g id="edd" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect id="Rectangle" fill="#313131" x="18" y="0" width="4" height="40" rx="2"></rect>
                            <rect id="Rectangle" fill="#313131" transform="translate(20.000000, 20.000000) rotate(-270.000000) translate(-20.000000, -20.000000) " x="18" y="0" width="4" height="40" rx="2"></rect>
                          </g>
                        </svg>
                      </button>
                      <div class="feedbackPlayer__buttonGroup">
                        <div class="wtext button-icon active">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510">
                            <path d="M255 489.6l-35.7-35.7C86.7 336.6 0 257.55 0 160.65 0 81.6 61.2 20.4 140.25 20.4c43.35 0 86.7 20.4 114.75 53.55C283.05 40.8 326.4 20.4 369.75 20.4 448.8 20.4 510 81.6 510 160.65c0 96.9-86.7 175.95-219.3 293.25L255 489.6z"/>
                          </svg>
                          <p>23&nbsp;000</p>
                        </div>
                      </div>
                      <button class="showMore button-icon">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                          <g fill="#313131" fill-rule="evenodd">
                            <circle cx="5.5" cy="12" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="18.5" cy="12" r="1.5"/>
                          </g>
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="feedbackPlayer__spec">
                    <div class="feedbackPlayer__specTrack">Мальчики</div>
                    <div class="feedbackPlayer__specArtist">АлоэВера – Алимоно – 2018</div>
                    <div class="feedbackPlayer__specOsc">
                      <img src="/images/spec.png" alt="">
                    </div>
                  </div>
                </div>
                <div class="feedbackPosts">
                  <div class="feedbackPost">
                    <img src="/images/acc.jpg" alt="">
                    <div class="feedbackPostBody">
                      <div class="feedbackPostBody__header">
                        <p>Полина Гагарина</p>
                        <span>вчера</span>
                      </div>
                      <p class="feedbackPostBody__post">Песня для истинных поклонников рока!! Хоть альбом был выпущен в 1980 он и поныне есть очень актуальным. Всегда с друзьями его слушаем и наслаждаемся этим истинным источником музыки. Сама группа AC/DC есть очень популярной. Поэтому ней никогда не рано интересоваться.</p>
                    </div>
                  </div>
                  <div class="feedbackPost">
                    <img src="/images/track.jpg" alt="">
                    <div class="feedbackPostBody">
                      <div class="feedbackPostBody__header">
                        <p>Antonio Rodrigues Jr</p>
                        <span>вчера</span>
                      </div>
                      <p class="feedbackPostBody__post">Не представляю как эта песня может оставить равнодушным. Первые секунды заряжают энергией и чувством позитива, хочется качать головой в такт и нестись по бескрайним просторам. Классические AC\DC, песня, которая символизирует эту группу и рок в целом.</p>
                    </div>
                  </div>
                  <div class="feedbackTrack__showAll">
                    <button class="button">Все отзывы по этой песне</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition-group>
    </div>
  </main>
</template>
<script>
  export default{
    data(){
      return{
        activeTab: 1,
      }
    },
    methods:{
      setActiveTab(tab){
        this.activeTab = tab;
      }
    }
  }
</script>
<style scoped lang="sass">
  .main
    padding-top: 0
  .fade-enter-active, .fade-leave-active
    transition: opacity .5s
    .fade-enter, .fade-leave-to
      opacity: 0
</style>
