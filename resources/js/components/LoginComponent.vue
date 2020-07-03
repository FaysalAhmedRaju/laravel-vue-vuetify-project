<template>
    <v-app id="inspire">
        <v-main>
            <v-container
                class="fill-height"
                fluid
            >
                <v-row
                    align="center"
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="8"
                        md="4"
                    >
                        <v-card class="elevation-12">
                            <v-toolbar
                                color="error"
                                dark
                                flat
                            >
                                <v-toolbar-title>Login form</v-toolbar-title>
                                <v-spacer></v-spacer>

                            </v-toolbar>
                            <v-card-text>
                                <v-progress-linear
                                    :active="loading"
                                    :indeterminate="loading"
                                    absolute
                                    bottom
                                    color="deep-purple accent-4"
                                    >

                                </v-progress-linear>
                                <v-form>
                                    <v-text-field
                                        label="Email"
                                        name="email"
                                        v-model="email"
                                        prepend-icon="mdi-email-newsletter"
                                        type="email"
                                    ></v-text-field>

                                    <v-text-field
                                        id="password"
                                        label="Password"
                                        v-model="password"
                                        name="password"
                                        prepend-icon="mdi-account-lock"
                                        type="password"
                                    ></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="login">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
    export default {
       data(){
           return{
               email:'',
               password:'',
               loading:false
           }
       },
        methods:{
           login: function () {
               // Add a request interceptor
               axios.interceptors.request.use( (config)=> {
                   // Do something before request is sent
                   this.loading = true;
                   return config;
               },  (error)=> {
                   this.loading = false;
                   // Do something with request error
                   return Promise.reject(error);
               });

// Add a response interceptor
               axios.interceptors.response.use( (response)=> {
                   // Any status code that lie within the range of 2xx cause this function to trigger
                   // Do something with response data
                   this.loading = false;
                   return response;
               },  (error)=> {
                   this.loading = false;
                   // Any status codes that falls outside the range of 2xx cause this function to trigger
                   // Do something with response error
                   return Promise.reject(error);
               });
               axios.post('/api/login',{'email':this,'password':this.password})
               .then(res => {
                   localStorage.setItem('token',res.data.token)
               })
               .catch( err => console.dir(err))


           }
        }
    }
</script>
<style scoped></style>
