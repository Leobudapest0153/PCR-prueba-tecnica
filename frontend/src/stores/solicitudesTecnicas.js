import { defineStore } from 'pinia';
import { solicitudesTecnicasService } from '../services/solicitudesTecnicasService';

export const useSolicitudesTecnicasStore = defineStore('solicitudesTecnicas', {
    state: () => ({
        items: [],
        meta: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
        },
        filtros: {
            estado: null,
            prioridad: null,
        },
        cargando: false,
        error: null,
    }),

    actions: {
        /**
         * Carga el listado aplicando los filtros y la pagina actuales.
         */
        async cargar(pagina = 1) {
            this.cargando = true;
            this.error = null;

            try {
                const { items, meta } = await solicitudesTecnicasService.listar({
                    ...this.filtros,
                    page: pagina,
                    per_page: this.meta.per_page,
                });

                this.items = items;
                this.meta = meta;
            } catch (error) {
                this.error = error;
                throw error;
            } finally {
                this.cargando = false;
            }
        },

        /**
         * Actualiza uno o varios filtros y vuelve a la primera pagina.
         */
        async establecerFiltros(filtros) {
            this.filtros = { ...this.filtros, ...filtros };
            await this.cargar(1);
        },

        /**
         * Crea una nueva solicitud y recarga la primera pagina (el listado
         * ordena por fecha de creacion descendente, por lo que la nueva
         * solicitud aparecera primero).
         */
        async crear(datos) {
            const solicitud = await solicitudesTecnicasService.crear(datos);
            await this.cargar(1);
            return solicitud;
        },

        /**
         * Actualiza el estado de una solicitud y refleja el cambio en el
         * listado ya cargado, sin necesidad de recargar toda la pagina.
         */
        async actualizarEstado(id, estado) {
            const solicitud = await solicitudesTecnicasService.actualizarEstado(id, estado);

            const indice = this.items.findIndex((item) => item.id === id);
            if (indice !== -1) {
                this.items[indice] = solicitud;
            }

            return solicitud;
        },
    },
});
