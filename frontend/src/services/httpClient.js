import axios from 'axios';

// En Docker, docker-compose inyecta VITE_API_BASE_URL como variable de
// entorno real del proceso, que Vite prioriza sobre el valor del .env del
// servicio (mismo patron usado en el backend con las variables de la BD).
const baseURL = import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:8000/api/v1';

export const httpClient = axios.create({
    baseURL,
    headers: {
        Accept: 'application/json',
    },
});
