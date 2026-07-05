<script setup>
import { useSolicitudesTecnicasStore } from '../stores/solicitudesTecnicas';
import { PRIORIDAD_COLOR, labelPrioridad } from '../enums/prioridad';
import { ESTADO_COLOR, labelEstado } from '../enums/estadoSolicitud';

const store = useSolicitudesTecnicasStore();

const headers = [
    { title: 'Cliente', key: 'cliente' },
    { title: 'Título', key: 'titulo' },
    { title: 'Prioridad', key: 'prioridad', sortable: false },
    { title: 'Estado', key: 'estado', sortable: false },
    { title: 'Creada', key: 'fecha_creacion' },
];

function formatearFecha(fechaIso) {
    if (!fechaIso) return '—';

    return new Date(fechaIso).toLocaleString('es-SV', {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
}

/**
 * Vuetify invoca este evento con la pagina/tamaño de pagina actuales cada
 * vez que el usuario cambia de pagina o de items por pagina.
 */
async function alCambiarOpciones({ page, itemsPerPage }) {
    if (itemsPerPage !== store.meta.per_page) {
        store.meta.per_page = itemsPerPage;
    }

    await store.cargar(page);
}
</script>

<template>
  <v-container fluid>
    <div class="d-flex align-center justify-space-between mb-4">
      <h1 class="text-h5">Solicitudes técnicas</h1>
    </div>

    <v-alert v-if="store.error" type="error" variant="tonal" class="mb-4">
      Ocurrió un error al cargar las solicitudes. Intenta de nuevo.
    </v-alert>

    <v-card>
      <v-data-table-server
        :headers="headers"
        :items="store.items"
        :items-length="store.meta.total"
        :items-per-page="store.meta.per_page"
        :loading="store.cargando"
        item-value="id"
        @update:options="alCambiarOpciones"
      >
        <template #item.prioridad="{ item }">
          <v-chip :color="PRIORIDAD_COLOR[item.prioridad]" size="small" variant="flat">
            {{ labelPrioridad(item.prioridad) }}
          </v-chip>
        </template>

        <template #item.estado="{ item }">
          <v-chip :color="ESTADO_COLOR[item.estado]" size="small" variant="flat">
            {{ labelEstado(item.estado) }}
          </v-chip>
        </template>

        <template #item.fecha_creacion="{ item }">
          {{ formatearFecha(item.fecha_creacion) }}
        </template>

        <template #no-data>
          <div class="py-8 text-center text-medium-emphasis">
            No hay solicitudes registradas.
          </div>
        </template>
      </v-data-table-server>
    </v-card>
  </v-container>
</template>
