import { defineStore } from 'pinia';

/**
 * Estado global para mostrar feedback puntual (exito/error) al usuario
 * mediante un snackbar, sin acoplar cada componente a su propia logica de
 * visibilidad/temporizador.
 */
export const useNotificacionesStore = defineStore('notificaciones', {
    state: () => ({
        visible: false,
        mensaje: '',
        tipo: 'success',
    }),

    actions: {
        mostrarExito(mensaje) {
            this.mostrar(mensaje, 'success');
        },

        mostrarError(mensaje) {
            this.mostrar(mensaje, 'error');
        },

        mostrar(mensaje, tipo) {
            this.mensaje = mensaje;
            this.tipo = tipo;
            this.visible = true;
        },
    },
});
