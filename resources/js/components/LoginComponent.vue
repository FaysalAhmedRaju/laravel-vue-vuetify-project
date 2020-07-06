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
                                    top
                                    color="white accent-4"
                                    >
                                </v-progress-linear>

                                    <v-form
                                            ref="form"
                                            v-model="valid"

                                    >
                                    <v-text-field color="error"
                                        label="Email"
                                        name="email"
                                        v-model="email"
                                        :rules="emailRules"
                                        required
                                        prepend-icon="mdi-email-newsletter"
                                        type="email"
                                    ></v-text-field>

                                    <v-text-field color="error"
                                        id="password"
                                        label="Password"
                                        v-model="password"
                                                  :rules="passwordRules"
                                        name="password"
                                        required
                                        prepend-icon="mdi-account-lock"
                                        type="password"
                                    ></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error"
                                       :disabled="!valid"
                                       @click="login">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-snackbar
                                v-model="snackbar"
                        >
                            {{ text }}


                                <v-btn
                                        color="pink"
                                        text
                                        @click="snackbar = false"
                                >
                                    Close
                                </v-btn>

                        </v-snackbar>
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
               valid: true,
               email:'',
               emailRules: [
                   v => !!v || 'E-mail is required',
                   v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
               ],
               password:'',
               passwordRules: [
                   v => !!v || 'Password is required',

               ],
               loading:false,
               snackbar:false,
               text:'',
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
               axios.post('/api/login',{'email':this.email,'password':this.password})
               .then(res => {

                //  console.log(res);
                   localStorage.setItem('token',res.data.token)
                   localStorage.setItem('loggedIn',true)
                   this.$router.push('/admin')
                       .then(res =>console.log("LoggedIn Successfully"))
                       .catch(res => console.log(err))
               })
               .catch( err =>{
                   console.log(err);//undefined
                   //console.dir(err)
                  // this.text = err.data.status;
                   this.text = err.response.data.status;
                   this.snackbar = true;
               })


           }
        }
    }
</script>
<style scoped></style>
