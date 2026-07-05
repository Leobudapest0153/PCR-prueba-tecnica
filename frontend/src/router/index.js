import { createRouter, createWebHistory } from 'vue-router';
import SolicitudesListView from '../views/SolicitudesListView.vue';

const routes = [
    {
        path: '/',
        name: 'solicitudes.index',
        component: SolicitudesListView,
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
