import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [vue()],
    server: {
        host: true,
        port: 5173,
        watch: {
            // El volumen montado desde el host (Windows) no siempre propaga
            // eventos inotify al contenedor Linux, por lo que Vite necesita
            // polling para detectar cambios de archivos de forma confiable.
            usePolling: true,
        },
    },
});
