import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from "./components/LoginComponent";
import AdminComponent from "./components/AdminComponent";
import UserComponent from "./components/UserComponent";
import RolesComponent from "./components/RolesComponent";
// const foo = {template:"<v-alert type='error'>I'm Foo Component</v-alert>"}
// const bar = {template:"<v-alert type='error'>I'm Bar Component</v-alert>"}
// const user = {template: "<v-alert type='info'>I'm {{$route.params.name}}</v-alert>"}
Vue.use(VueRouter)

const routes = [
    // {
    //     path: '/foo',
    //     component: foo,
    // },
    // {
    //     path: '/bar',
    //     component: bar
    // },
    // {
    //     path: '/user/:name',
    //     component: user
    // }
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        component: LoginComponent,
        name: 'Login'
    },
    {
        path: '/admin',
        component: AdminComponent,
        name: 'Admin',
        children: [
            {
                path: '/roles',
                component: RolesComponent,
                name: 'Roles'
            },

        ],
        beforeEnter: (to, from, next) => {
            axios.get('api/verify')
                .then(res => next())
                .catch(err => next('/login'))
            // if(localStorage.getItem('token')){
            //     next();
            // }else{
            //     next('/login');
            // }

        }
    },
    {
        path: '/user',
        component: UserComponent,
        name: 'User'
    }
]
const router = new VueRouter({routes})
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token') || null;
    window.axios.defaults.headers['Authorization'] = "Bearer " + token;
    next();
})

export default router
