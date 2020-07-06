<template>
    <form>
        <v-text-field
                v-model="newItem.name"
                :counter="10"
                label="Name"
        ></v-text-field>
        <v-text-field
                v-model="newItem.email"
                label="E-mail"
        ></v-text-field>

        <v-btn class="mr-4" @click="submit">submit</v-btn>
        <v-btn @click="clear">clear</v-btn>
    </form>
</template>
<script>


    export default {


        data: () => ({
           newItem: {'name':'','email':''}


        }),

        computed: {

        },

        methods: {
            submit () {
                var input = this.newItem;
                var _this = this;
                //console.log("name:"+input['name']+"email:"+input['email']);
                if(input['name'] == '' || input['email']==''){
                    alert("Please fill all fields.");

                }else {
                    //console.log(input);
                    axios.post('/storeItem',input)
                    .then(res => {
                        console.log(res);
                        // localStorage.setItem('token',res.data.token)
                        _this.newItem = {'name':'','email':''}
                    })
                    .catch( err =>{
                            console.log(err);//undefined
                            //console.dir(err)
                             this.text = "Email or Password is Wrong";
                             this.snackbar = true;
                        })
                }
            },
            clear () {

                this.newItem.name = ''
                this.newItem.email = ''

            },
        },
    }
</script>
<style scoped></style>
