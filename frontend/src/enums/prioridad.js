// Mantener sincronizado con el backend: app/Enums/Prioridad.php
export const Prioridad = Object.freeze({
    BAJA: 'baja',
    MEDIA: 'media',
    ALTA: 'alta',
});

export const PRIORIDAD_OPCIONES = [
    { value: Prioridad.BAJA, label: 'Baja' },
    { value: Prioridad.MEDIA, label: 'Media' },
    { value: Prioridad.ALTA, label: 'Alta' },
];

export const PRIORIDAD_COLOR = Object.freeze({
    [Prioridad.BAJA]: 'success',
    [Prioridad.MEDIA]: 'warning',
    [Prioridad.ALTA]: 'error',
});

export function labelPrioridad(valor) {
    return PRIORIDAD_OPCIONES.find((opcion) => opcion.value === valor)?.label ?? valor;
}
