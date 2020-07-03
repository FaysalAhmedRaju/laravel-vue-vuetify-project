import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginComponent from "./components/LoginComponent";
import AdminComponent from "./components/AdminComponent";
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
        beforeEnter: (to, from, next) => {
            if(localStorage.getItem('token')){
                next();
            }else{
                next('/login');
            }

        }
    }
]

export default new VueRouter({routes})
