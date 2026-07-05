<script setup>
import AppSnackbar from './components/AppSnackbar.vue';
</script>

<template>
  <v-app>
    <v-app-bar color="primary" density="comfortable">
      <v-app-bar-title>Solicitudes Técnicas</v-app-bar-title>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>

    <AppSnackbar />
  </v-app>
</template>
