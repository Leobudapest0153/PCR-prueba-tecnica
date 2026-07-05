<script setup>
import { ref, watch } from 'vue';
import { solicitudesTecnicasService } from '../services/solicitudesTecnicasService';
import { PRIORIDAD_COLOR, labelPrioridad } from '../enums/prioridad';
import { ESTADO_COLOR, labelEstado } from '../enums/estadoSolicitud';

const props = defineProps({
    solicitudId: {
        type: [Number, String],
        default: null,
    },
});

const abierto = defineModel({ default: false });

const cargando = ref(false);
const error = ref(null);
const solicitud = ref(null);

async function cargar() {
    if (!props.solicitudId) return;

    cargando.value = true;
    error.value = null;
    solicitud.value = null;

    try {
        solicitud.value = await solicitudesTecnicasService.ver(props.solicitudId);
    } catch {
        error.value = 'Ocurrió un error al cargar el detalle de la solicitud.';
    } finally {
        cargando.value = false;
    }
}

// Se consulta el detalle contra la API cada vez que se abre el dialogo,
// para mostrar siempre la informacion mas reciente de la solicitud.
watch([abierto, () => props.solicitudId], ([estaAbierto]) => {
    if (estaAbierto) cargar();
});

function formatearFecha(fechaIso) {
    if (!fechaIso) return '—';

    return new Date(fechaIso).toLocaleString('es-SV', {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
}
</script>

<template>
  <v-dialog v-model="abierto" max-width="600">
    <v-card>
      <v-card-title>Detalle de la solicitud</v-card-title>

      <v-card-text>
        <div v-if="cargando" class="d-flex justify-center py-8">
          <v-progress-circular indeterminate color="primary" />
        </div>

        <v-alert v-else-if="error" type="error" variant="tonal">
          {{ error }}
        </v-alert>

        <dl v-else-if="solicitud" class="d-flex flex-column ga-3">
          <div>
            <dt class="text-caption text-medium-emphasis">Cliente</dt>
            <dd class="text-body-1">{{ solicitud.cliente }}</dd>
          </div>

          <div>
            <dt class="text-caption text-medium-emphasis">Título</dt>
            <dd class="text-body-1">{{ solicitud.titulo }}</dd>
          </div>

          <div>
            <dt class="text-caption text-medium-emphasis">Descripción</dt>
            <dd class="text-body-1" style="white-space: pre-line">{{ solicitud.descripcion }}</dd>
          </div>

          <div class="d-flex ga-8">
            <div>
              <dt class="text-caption text-medium-emphasis">Prioridad</dt>
              <dd>
                <v-chip :color="PRIORIDAD_COLOR[solicitud.prioridad]" size="small" variant="flat">
                  {{ labelPrioridad(solicitud.prioridad) }}
                </v-chip>
              </dd>
            </div>

            <div>
              <dt class="text-caption text-medium-emphasis">Estado</dt>
              <dd>
                <v-chip :color="ESTADO_COLOR[solicitud.estado]" size="small" variant="flat">
                  {{ labelEstado(solicitud.estado) }}
                </v-chip>
              </dd>
            </div>
          </div>

          <div class="d-flex ga-8">
            <div>
              <dt class="text-caption text-medium-emphasis">Creada</dt>
              <dd class="text-body-2">{{ formatearFecha(solicitud.fecha_creacion) }}</dd>
            </div>

            <div>
              <dt class="text-caption text-medium-emphasis">Última actualización</dt>
              <dd class="text-body-2">{{ formatearFecha(solicitud.fecha_actualizacion) }}</dd>
            </div>
          </div>
        </dl>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn variant="text" @click="abierto = false">Cerrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
