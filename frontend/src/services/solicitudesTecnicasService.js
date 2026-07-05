import { httpClient } from './httpClient';

const RECURSO = '/solicitudes-tecnicas';

/**
 * Capa de acceso a la API de solicitudes tecnicas. Cada metodo devuelve
 * directamente el `data` del sobre de respuesta del backend
 * (`{ success, message, data }`).
 */
export const solicitudesTecnicasService = {
    /**
     * @param {{ estado?: string, prioridad?: string, per_page?: number, page?: number }} filtros
     * @returns {Promise<{ items: object[], meta: object }>}
     */
    async listar(filtros = {}) {
        const { data } = await httpClient.get(RECURSO, { params: filtros });
        return data.data;
    },

    /**
     * @param {number|string} id
     */
    async ver(id) {
        const { data } = await httpClient.get(`${RECURSO}/${id}`);
        return data.data;
    },

    /**
     * @param {{ cliente: string, titulo: string, descripcion: string, prioridad: string, estado?: string }} datos
     */
    async crear(datos) {
        const { data } = await httpClient.post(RECURSO, datos);
        return data.data;
    },

    /**
     * @param {number|string} id
     * @param {string} estado
     */
    async actualizarEstado(id, estado) {
        const { data } = await httpClient.patch(`${RECURSO}/${id}/estado`, { estado });
        return data.data;
    },
};
