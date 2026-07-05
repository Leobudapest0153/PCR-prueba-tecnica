<script setup>
import { ref, watch } from 'vue';
import { useSolicitudesTecnicasStore } from '../stores/solicitudesTecnicas';
import { ESTADO_OPCIONES } from '../enums/estadoSolicitud';

const props = defineProps({
    solicitud: {
        type: Object,
        default: null,
    },
});

const abierto = defineModel({ default: false });

const store = useSolicitudesTecnicasStore();

const estadoSeleccionado = ref(null);
const enviando = ref(false);
const error = ref(null);

// Cada vez que se abre el dialogo se parte del estado actual de la
// solicitud seleccionada, para que el select no arrastre la seleccion de
// una solicitud distinta abierta previamente.
watch(abierto, (estaAbierto) => {
    if (estaAbierto && props.solicitud) {
        estadoSeleccionado.value = props.solicitud.estado;
        error.value = null;
    }
});

function cerrar() {
    abierto.value = false;
}

async function enviar() {
    if (!props.solicitud || !estadoSeleccionado.value) return;

    enviando.value = true;
    error.value = null;

    try {
        await store.actualizarEstado(props.solicitud.id, estadoSeleccionado.value);
        cerrar();
    } catch {
        error.value = 'Ocurrió un error al actualizar el estado. Intenta de nuevo.';
    } finally {
        enviando.value = false;
    }
}
</script>

<template>
  <v-dialog v-model="abierto" max-width="480" persistent>
    <v-card v-if="solicitud">
      <v-card-title>Cambiar estado</v-card-title>

      <v-card-text>
        <p class="text-body-2 text-medium-emphasis mb-4">
          {{ solicitud.cliente }} · {{ solicitud.titulo }}
        </p>

        <v-alert v-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>

        <v-select
          v-model="estadoSeleccionado"
          :items="ESTADO_OPCIONES"
          item-title="label"
          item-value="value"
          label="Nuevo estado"
          hide-details
        />
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn variant="text" :disabled="enviando" @click="cerrar">Cancelar</v-btn>
        <v-btn
          color="primary"
          variant="flat"
          :loading="enviando"
          :disabled="estadoSeleccionado === solicitud.estado"
          @click="enviar"
        >
          Guardar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
