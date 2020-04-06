import Home from './components/Home.vue';
import Login from './components/auth/Login.vue';
import List from './components/List.vue';

export const routes = [
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/showall',
        component: List,
        meta: {
            requiresAuth: true
        }
        /*
        children: [
            {
                path: '/',
                component: List
            },
            {
                path: 'new',
                component: New
            }
        ] */
    }
];
