<script setup>
import { useNotificacionesStore } from '../stores/notificaciones';

const notificaciones = useNotificacionesStore();
</script>

<template>
  <v-snackbar v-model="notificaciones.visible" :color="notificaciones.tipo" timeout="4000" location="bottom right">
    {{ notificaciones.mensaje }}

    <template #actions>
      <v-btn variant="text" @click="notificaciones.visible = false">Cerrar</v-btn>
    </template>
  </v-snackbar>
</template>
