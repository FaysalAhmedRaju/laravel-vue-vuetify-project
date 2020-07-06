<template>
    <v-app id="inspire">
        <v-data-table
                item-key="name"
                      class="elevation-1"
                      color="error"
                      :loading = "loading"
                      loading-text="Loading... Please wait"
                      :headers="headers"
                       @pagination="paginate"
                      :server-items-length="roles.total"
                      :items="roles.data"
                      :items-per-page=5
                      sort-by="calories"
                      :footer-props="{
                      itemsPerPageOptions: [5,10,15],
                      itemsPerPageText: 'Roles Per Page',
                      'show-current-page': true,
                      'show-first-last-page': true
                      }"
        >
            <template v-slot:top>
                <v-text-field @input="searchit"   label="Search..." />
                <v-toolbar flat color="dark">
                    <v-toolbar-title>Role Management System</v-toolbar-title>
                    <v-divider
                            class="mx-4"
                            inset
                            vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="error" dark class="mb-2" v-on="on">Add New Role</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field color="error" v-model="editedItem.name" label="Role Name"></v-text-field>
                                        </v-col>
                                    </v-row>

                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error darken-1" text @click="close">Cancel</v-btn>
                                <v-btn color="error darken-1" text @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                        small
                        @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="error" @click="initialize">Reset</v-btn>
            </template>
        </v-data-table>
        <v-snackbar
                v-model="snackbar"
        >
            Record Deleted Successfully!


            <v-btn
                    color="error"
                    text
                    @click="snackbar = false"
            >
                Close
            </v-btn>

        </v-snackbar>

    </v-app>
</template>
<script>
    export default {
        data: () => ({
            dialog: false,
            loading:false,
            snackbar:false,
            headers: [
                {
                    text: '#',
                    align: 'start',
                    sortable: false,
                    value: 'id',
                },
                { text: 'Name', value: 'name' },
                { text: 'Created At', value: 'created_at' },
                { text: 'Updated At', value: 'updated_at' },
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            roles: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                name: '',
                created_at: '',
                updated_at: '',


            },
            defaultItem: {
                id: '',
                name: '',
                created_at: '',
                updated_at: '',

            },
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.initialize()
        },

        methods: {
            searchit(e){
                if(e.length > 3){
                    console.log(e);
                    axios.get(`/api/roles/${e}`)
                        .then(res => this.roles = res.data.roles)
                        .catch(err => console.dir(err.response))
                }
                if(e.length <= 0){
                    axios.get(`/api/roles`)
                        .then(res => this.roles = res.data.roles)
                        .catch(err => console.dir(err.response))
                }
            },
            paginate(e){

                //console.log($event);
//  one way     axios.get('/api/roles?page='+$event.page,{})
                axios.get(`/api/roles?page=${e.page}`,{params:{'per_page': e.itemsPerPage}})
                    .then(res => this.roles = res.data.roles)
                    .catch(err =>{
                        if(err.response.status == 401)
                            localStorage.removeItem('token');
                        this.$router.push('/login');
                    })
            },
            initialize () {
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

            },

            editItem (item) {

                this.editedIndex = this.roles.data.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.roles.data.indexOf(item)
                let decide = confirm('Are you sure you want to delete this item?')
                if(decide){
                    axios.delete('/api/roles/'+item.id)
                        .then(res =>{
                            this.snackbar = true
                            this.roles.data.splice(index, 1)
                        }).catch(err => console.log(err.response))
                }


            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save () {
                if (this.editedIndex > -1) {
                    const  index = this.editedIndex
                    axios.put('/api/roles/'+this.editedItem.id,{'name': this.editedItem.name})
                        .then(res =>{
                                Object.assign(this.roles.data[index], res.data.role)

                        })
                        .catch(err => console.log(err.response))


                } else {
                    axios.post('/api/roles',{'name':this.editedItem.name})
                        .then(res =>{
                            this.roles.data.push(res.data.role)

                        })
                        .catch(err => console.dir(err.response))
                    //this.roles.push(this.editedItem)
                }
                this.close()
            },
        },
    }
</script>
<style scoped></style>