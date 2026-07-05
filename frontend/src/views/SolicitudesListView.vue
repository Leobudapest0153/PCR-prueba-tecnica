<script setup>
import { ref } from 'vue';
import { useSolicitudesTecnicasStore } from '../stores/solicitudesTecnicas';
import { PRIORIDAD_COLOR, PRIORIDAD_OPCIONES, labelPrioridad } from '../enums/prioridad';
import { ESTADO_COLOR, ESTADO_OPCIONES, labelEstado } from '../enums/estadoSolicitud';
import CrearSolicitudDialog from '../components/CrearSolicitudDialog.vue';
import DetalleSolicitudDialog from '../components/DetalleSolicitudDialog.vue';

const store = useSolicitudesTecnicasStore();
const dialogCrearAbierto = ref(false);
const dialogDetalleAbierto = ref(false);
const solicitudSeleccionadaId = ref(null);

const headers = [
    { title: 'Cliente', key: 'cliente' },
    { title: 'Título', key: 'titulo' },
    { title: 'Prioridad', key: 'prioridad', sortable: false },
    { title: 'Estado', key: 'estado', sortable: false },
    { title: 'Creada', key: 'fecha_creacion' },
    { title: '', key: 'acciones', sortable: false, align: 'end' },
];

function verDetalle(solicitud) {
    solicitudSeleccionadaId.value = solicitud.id;
    dialogDetalleAbierto.value = true;
}

// Controlan la pagina actual de la tabla para poder forzar el regreso a la
// pagina 1 desde afuera cuando el usuario cambia los filtros.
const pagina = ref(1);
const estadoFiltro = ref(null);
const prioridadFiltro = ref(null);

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

async function aplicarFiltros() {
    pagina.value = 1;
    await store.establecerFiltros({
        estado: estadoFiltro.value,
        prioridad: prioridadFiltro.value,
    });
}

async function limpiarFiltros() {
    estadoFiltro.value = null;
    prioridadFiltro.value = null;
    await aplicarFiltros();
}
</script>

<template>
  <v-container fluid>
    <div class="d-flex align-center justify-space-between mb-4">
      <h1 class="text-h5">Solicitudes técnicas</h1>
      <v-btn color="primary" prepend-icon="mdi-plus" @click="dialogCrearAbierto = true">
        Nueva solicitud
      </v-btn>
    </div>

    <CrearSolicitudDialog v-model="dialogCrearAbierto" />
    <DetalleSolicitudDialog v-model="dialogDetalleAbierto" :solicitud-id="solicitudSeleccionadaId" />

    <v-card class="mb-4">
      <v-card-text>
        <v-row align="center">
          <v-col cols="12" sm="4" md="3">
            <v-select
              v-model="estadoFiltro"
              :items="ESTADO_OPCIONES"
              item-title="label"
              item-value="value"
              label="Estado"
              clearable
              hide-details
              @update:model-value="aplicarFiltros"
            />
          </v-col>

          <v-col cols="12" sm="4" md="3">
            <v-select
              v-model="prioridadFiltro"
              :items="PRIORIDAD_OPCIONES"
              item-title="label"
              item-value="value"
              label="Prioridad"
              clearable
              hide-details
              @update:model-value="aplicarFiltros"
            />
          </v-col>

          <v-col cols="12" sm="4" md="3">
            <v-btn variant="text" @click="limpiarFiltros">Limpiar filtros</v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-alert v-if="store.error" type="error" variant="tonal" class="mb-4">
      Ocurrió un error al cargar las solicitudes. Intenta de nuevo.
    </v-alert>

    <v-card>
      <v-data-table-server
        v-model:page="pagina"
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

        <template #item.acciones="{ item }">
          <v-btn
            icon="mdi-eye-outline"
            variant="text"
            size="small"
            title="Ver detalle"
            @click="verDetalle(item)"
          />
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
