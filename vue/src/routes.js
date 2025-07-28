import {createRouter, createWebHistory} from 'vue-router';

import { routesConfig } from "./routesConfig";
import axios from "axios";

const routes = [...routesConfig]
const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((routeTo, routeFrom, next) => {
    let user = null;
    if(window.localStorage.getItem('CURRENT_USER') !== 'undefined') {
      user = JSON.parse(window.localStorage.getItem('CURRENT_USER'));
    }

    const authRequired = routeTo.matched.some((route) => route.meta.authRequired)
    const isAdmin = routeTo.matched.some((route) => route.meta.isAdmin)
    const isExpert = routeTo.matched.some((route) => route.meta.isExpert)
    const isClient = routeTo.matched.some((route) => route.meta.isClient)

    document.title = "Shopexperts";

    const nearestWithTitle = routeTo.matched.slice().reverse().find(r => r.meta && r.meta.title);

    const nearestWithMeta = routeTo.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    const previousNearestWithMeta = routeFrom.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    if(nearestWithTitle) {
        document.title = nearestWithTitle.meta.title;
    } else if(previousNearestWithMeta) {
        document.title = previousNearestWithMeta.meta.title;
    }

    Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

    function handleLogout(loginPage) {
        window.localStorage.removeItem('CURRENT_USER');
        window.localStorage.removeItem('CURRENT_TOKEN');
        delete axios.defaults.headers.common['Authorization'];
        redirectTo(loginPage);
    }

    const isPasswordResetRoute = routeTo.path === '/reset-password';
    const isSSOLoginRoute = routeTo.path === '/sso-login';

    if (!user) {
        if (isAdmin) {
            handleLogout('admin-login');
        } else if (isClient) {
            handleLogout('client-login');
        } else if (isExpert) {
            handleLogout('expert-login');
        }
    } else if (!isPasswordResetRoute && !isSSOLoginRoute) {
        const [, prefix, route] = routeTo.path.split('/')

        if (user.role_id === 1 && (prefix !== 'admin' || route === 'login')) {
            redirectTo('admin');
        } else if (user.role_id === 2 && (prefix !== 'client' || route === 'login')) {
            redirectTo('client-dashboard');
        } else if (user.role_id === 3 && (prefix !== 'expert' || route === 'login')) {
            redirectTo('expert-dashboard');
        }
    }
    if (isClient || isExpert) {
        window.Echo.join(`presence.receiver`)
    }

    if (nearestWithMeta) {
        nearestWithMeta.meta.metaTags.map(tagDef => {
            const tag = document.createElement('meta');

            Object.keys(tagDef).forEach(key => {
                tag.setAttribute(key, tagDef[key]);
            });

            tag.setAttribute('data-vue-router-controlled', '');

            return tag;
        })
            .forEach(tag => document.head.appendChild(tag));
    }

    if (!nearestWithMeta || !authRequired) return next();

    function redirectTo(route) {
        // Pass the original route to the login component
        next({
            name: route,
        })
    }
});
export default router
