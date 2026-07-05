<script setup>
import { reactive, ref } from 'vue';
import { useSolicitudesTecnicasStore } from '../stores/solicitudesTecnicas';
import { useNotificacionesStore } from '../stores/notificaciones';
import { PRIORIDAD_OPCIONES } from '../enums/prioridad';

const emit = defineEmits(['creada']);

const store = useSolicitudesTecnicasStore();
const notificaciones = useNotificacionesStore();

const abierto = defineModel({ default: false });

const formRef = ref(null);
const enviando = ref(false);
const erroresCampos = reactive({});

const datosIniciales = () => ({
    cliente: '',
    titulo: '',
    descripcion: '',
    prioridad: null,
});

const datos = reactive(datosIniciales());

const reglas = {
    requerido: (valor) => !!valor || 'Este campo es obligatorio.',
    maxLength: (max) => (valor) => !valor || valor.length <= max || `Máximo ${max} caracteres.`,
};

function limpiarErrores() {
    Object.keys(erroresCampos).forEach((campo) => delete erroresCampos[campo]);
}

function resetearFormulario() {
    Object.assign(datos, datosIniciales());
    limpiarErrores();
    formRef.value?.resetValidation();
}

function cerrar() {
    abierto.value = false;
    resetearFormulario();
}

async function enviar() {
    limpiarErrores();

    const { valid } = await formRef.value.validate();
    if (!valid) return;

    enviando.value = true;

    try {
        const solicitud = await store.crear({ ...datos });
        notificaciones.mostrarExito('Solicitud creada correctamente.');
        emit('creada', solicitud);
        cerrar();
    } catch (error) {
        if (error.response?.status === 422) {
            Object.assign(erroresCampos, error.response.data.errors);
        } else {
            notificaciones.mostrarError('Ocurrió un error al crear la solicitud. Intenta de nuevo.');
        }
    } finally {
        enviando.value = false;
    }
}
</script>

<template>
  <v-dialog v-model="abierto" max-width="600" persistent>
    <v-card>
      <v-card-title>Nueva solicitud técnica</v-card-title>

      <v-card-text>
        <v-form ref="formRef" @submit.prevent="enviar">
          <v-text-field
            v-model="datos.cliente"
            label="Cliente"
            :rules="[reglas.requerido, reglas.maxLength(255)]"
            :error-messages="erroresCampos.cliente"
            class="mb-2"
          />

          <v-text-field
            v-model="datos.titulo"
            label="Título"
            :rules="[reglas.requerido, reglas.maxLength(255)]"
            :error-messages="erroresCampos.titulo"
            class="mb-2"
          />

          <v-textarea
            v-model="datos.descripcion"
            label="Descripción"
            :rules="[reglas.requerido]"
            :error-messages="erroresCampos.descripcion"
            class="mb-2"
          />

          <v-select
            v-model="datos.prioridad"
            :items="PRIORIDAD_OPCIONES"
            item-title="label"
            item-value="value"
            label="Prioridad"
            :rules="[reglas.requerido]"
            :error-messages="erroresCampos.prioridad"
          />
        </v-form>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn variant="text" :disabled="enviando" @click="cerrar">Cancelar</v-btn>
        <v-btn color="primary" variant="flat" :loading="enviando" @click="enviar">Crear</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
