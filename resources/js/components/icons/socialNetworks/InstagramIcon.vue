<template>
  <!--eslint-disable-->
  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.94768 0H14.0173C17.2968 0 19.965 2.66812 19.965 5.94768V14.0173C19.965 17.2969 17.2969 19.965 14.0173 19.965H5.94768C2.66812 19.965 0 17.2969 0 14.0173V5.94768C0 2.66812 2.66812 0 5.94768 0ZM14.0177 17.9565C16.1933 17.9565 17.9569 16.1928 17.9569 14.0173V5.94768C17.9569 3.7721 16.1933 2.00848 14.0177 2.00848H5.94807C3.77253 2.00848 2.00887 3.7721 2.00887 5.94768V14.0173C2.00887 16.1928 3.77253 17.9565 5.94807 17.9565H14.0177Z" :fill="white ? 'white' : 'url(#paint0_linear)'" />
    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81885 9.98198C4.81885 7.13477 7.13526 4.81836 9.98251 4.81836C12.8298 4.81836 15.1462 7.13474 15.1462 9.98198C15.1462 12.8292 12.8298 15.1456 9.98251 15.1456C7.13526 15.1456 4.81885 12.8292 4.81885 9.98198ZM6.82772 9.98202C6.82772 11.7246 8.24033 13.1372 9.9829 13.1372C11.7254 13.1372 13.1381 11.7246 13.1381 9.98202C13.1381 8.23945 11.7255 6.82685 9.9829 6.82685C8.24036 6.82685 6.82772 8.23945 6.82772 9.98202Z" :fill="white ? 'white' : 'url(#paint1_linear)'" />
    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.156 6.09476C15.8394 6.09476 16.3933 5.54079 16.3933 4.85744C16.3933 4.17408 15.8394 3.62012 15.156 3.62012C14.4727 3.62012 13.9187 4.17408 13.9187 4.85744C13.9187 5.54079 14.4727 6.09476 15.156 6.09476Z" :fill="white ? 'white' : 'url(#paint2_linear)'" />
    <defs>
      <linearGradient id="paint0_linear" x1="19.8584" y1="19.9069" x2="19.8584" y2="0.155072" gradientUnits="userSpaceOnUse">
        <stop stop-color="#E09B3D"/>
        <stop offset="0.3" stop-color="#C74C4D"/>
        <stop offset="0.6" stop-color="#C21975"/>
        <stop offset="1" stop-color="#7024C4"/>
      </linearGradient>
      <linearGradient id="paint1_linear" x1="19.8584" y1="19.9064" x2="19.8584" y2="0.154592" gradientUnits="userSpaceOnUse">
        <stop stop-color="#E09B3D"/>
        <stop offset="0.3" stop-color="#C74C4D"/>
        <stop offset="0.6" stop-color="#C21975"/>
        <stop offset="1" stop-color="#7024C4"/>
      </linearGradient>
      <linearGradient id="paint2_linear" x1="25.0319" y1="19.9067" x2="25.0319" y2="0.1549" gradientUnits="userSpaceOnUse">
        <stop stop-color="#E09B3D"/>
        <stop offset="0.3" stop-color="#C74C4D"/>
        <stop offset="0.6" stop-color="#C21975"/>
        <stop offset="1" stop-color="#7024C4"/>
      </linearGradient>
    </defs>
  </svg>
</template>

<script>
export default {
  props: {
    white: {
      type: Boolean,
      default: false
    }
  }
};
</script>
