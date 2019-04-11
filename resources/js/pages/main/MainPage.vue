<template>
  <div>
    <div
      v-if="$route.fullPath === '/'"
      style="margin-top: 100px;"
    />
    <router-link
      to="/profile"
      style="display: block; margin-left: 20px;"
    >
      profile
    </router-link>
  </div>
</template>
