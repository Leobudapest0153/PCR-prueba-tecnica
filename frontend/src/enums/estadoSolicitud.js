// Mantener sincronizado con el backend: app/Enums/EstadoSolicitud.php
export const EstadoSolicitud = Object.freeze({
    PENDIENTE: 'pendiente',
    EN_PROCESO: 'en_proceso',
    RESUELTO: 'resuelto',
});

export const ESTADO_OPCIONES = [
    { value: EstadoSolicitud.PENDIENTE, label: 'Pendiente' },
    { value: EstadoSolicitud.EN_PROCESO, label: 'En proceso' },
    { value: EstadoSolicitud.RESUELTO, label: 'Resuelto' },
];

export const ESTADO_COLOR = Object.freeze({
    [EstadoSolicitud.PENDIENTE]: 'warning',
    [EstadoSolicitud.EN_PROCESO]: 'info',
    [EstadoSolicitud.RESUELTO]: 'success',
});

export function labelEstado(valor) {
    return ESTADO_OPCIONES.find((opcion) => opcion.value === valor)?.label ?? valor;
}
