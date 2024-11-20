<template>
    <v-container fluid grid-list-xl>
       <v-card class="rounded-0" >
           <v-data-table
           :headers="headers"
           :items="users"
           class="elevation-1"
           >
           <template v-slot:top>
               <v-toolbar
               flat color="white"
               >
               <v-toolbar-title>Admin Users</v-toolbar-title>
               <v-spacer></v-spacer>
               <v-dialog
                   v-model="dialog"
                   max-width="800px"
               >
                   <v-card>
                   <v-card-title class="text-center">
                       <span class="text-h5 text-indigo-accent-2">Edit Admin User</span>
                   </v-card-title>
                   <!-- <v-divider></v-divider> -->
                   <v-card-text>
                       <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                        <v-text-field
                                            v-model="editedItem.last_name"
                                            label="Last Name"
                                            :rules="nameRules"
                                            variant="outlined"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.last_name">
                                            {{ errors.last_name }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field
                                            v-model="editedItem.first_name"
                                            label="First Name"
                                            :rules="nameRules"
                                            variant="outlined"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.name">
                                            {{ errors.name }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field
                                            v-model="editedItem.name"
                                            label="User Name"
                                            :rules="nameRules"
                                            variant="outlined"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.name">
                                            {{ errors.name }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="editedItem.email"
                                            label="Email"
                                            variant="outlined"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="pass.password"
                                            label="Password"
                                            variant="outlined"
                                            :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="visible ? 'text' : 'password'"
                                            required
                                            @click:append-inner="visible = !visible"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.password">
                                            {{ errors.password}}
                                        </div>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="pass.con_password"
                                            label="Confirm Password"
                                            variant="outlined"
                                            :rules="[v => pass.password === v || 'Passwords do not match']"
                                            :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="visible ? 'text' : 'password'"
                                            required
                                            @click:append-inner="visible = !visible"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.password">
                                            {{ errors.password}}
                                        </div>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-select
                                            v-model="editedItem.role_id"
                                            :items="roles"
                                            item-value="id"
                                            item-title="role_name"
                                            label="Role"
                                            variant="solo"
                                            required
                                        ></v-select>
                                    </v-col>
                            </v-row>
                       </v-container>
                   </v-card-text>

                   <v-card-actions>
                       <v-spacer></v-spacer>
                       <v-btn
                       color="grey-darken-1"
                       variant="tonal"
                       @click="close"
                       >
                       Cancel
                       </v-btn>
                       <v-btn
                       color="blue-darken-1"
                       variant="tonal"
                       @click="save"
                       >
                       Save
                       </v-btn>
                   </v-card-actions>
                   </v-card>
               </v-dialog>
               </v-toolbar>
               <v-divider></v-divider>
           </template>
           <template v-slot:[`item.name`]="{ item }">
                {{ item.name }}
            </template>
            <template v-slot:[`item.actions`]="{ item }">
            <v-icon
                size="small"
                class="me-2"
                color="green"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                size="small"
                color="red"
                @click="deleteItemConfirm(item)"
            >
                mdi-delete
            </v-icon>
            </template>
   </v-data-table>
       </v-card>
    </v-container>
 </template>

 <script>
import axios from 'axios';
// import Role from '../../services/api/role'
// import Staff from '../../services/api/employee'
   export default {
     data: () => ({
        errors: [],
        error: false,
       dialog: false,
       dialogDelete: false,
       roles: [
                {title: 'role_name'}
        ],
       users: [],
       headers: [
         {
           title: 'Name',
           align: 'start',
           sortable: false,
           key: 'name',
         },
         { title: 'Position', key: 'role_name', sortable: false },
         { title: 'Actions', key: 'actions', sortable: false },
       ],
       editedIndex: -1,
       editedItem: {
            last_name: '',
            first_name: '',
            name: '',
            role_id: '',
        },
        pass: {
                password: '',
                con_password: '',
        },
       defaultItem: {
            last_name: '',
            first_name: '',
            name: '',
            role_id: '',
       },
       nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 10) || 'Name must be less than 10 characters',
            ],
            rules: {
                email: v => !!(v || '').match(/@/) || 'Please enter a valid email',
                length: len => v => (v || '').length >= len || `Invalid character length, required ${len}`,
                required: v => !!v || 'This field is required',
                phone: v => !!(v || '').match(v?.length >9 && /[0-9-]+/.test(v)) || 'Phone number needs to be at least 9 digits.',
            },
     }),

     watch: {
       dialog (val) {
         val || this.close()
       },
       dialogDelete (val) {
         val || this.closeDelete()
       },
     },
     created() {
           this.getRole();
           this.FilterRole();
       },
     methods: {
        FilterRole() {
            // Role.listTable()
            axios.get('/api/hr/HR/role/listTable')
            .then(res => {
                this.roles = res.data.data
                console.log("role", this.roles)
            }).catch(err=> {
                console.log(err)
            })
        },
       getRole() {
        axios.get('/api/hr/HR/role/roleUsers')
        //    Role.roleUsers()
           .then(response => {
               this.users = response.data.data;
               console.log("users", this.users);
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
       editItem (item) {
         this.editedIndex = this.users.indexOf(item)
         this.editedItem = Object.assign({}, item)
         this.dialog = true
       },

       close () {
         this.dialog = false
         this.$nextTick(() => {
           this.editedItem = Object.assign({}, this.defaultItem)
           this.editedIndex = -1
         })
       },

       closeDelete () {
         this.dialogDelete = false
         this.$nextTick(() => {
           this.editedItem = Object.assign({}, this.defaultItem)
           this.editedIndex = -1
         })
       },
       deleteItemConfirm(item) {
            // console.log("id", item)
            this.$swal({
                title: "Are you sure?",
                text: "Do you want to delete this item ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##DC143C",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.value) {
                    // Staff.deleteEmployee(item.user_id);
                    axios.delete('/api/hr/HR/staff/delete/' + item.user_id)
                    this.$swal({
                        title: "Success",
                        text: "Delete Successfully!",
                        icon: "success",
                    })
                    this.getRole();
                    this.closeDelete()
                }
            }).catch(err =>{
                console.log(err.response)
                this.$swal(
                    {
                        title: 'Oops',
                        text:"Fail, something went wrong!",
                        icon: 'error'
                    }
                )
                this.getRole();
                this.closeDelete()
            });
        },
       save() {
        let item = {}
            this.editedItem.password = this.pass.password
            item = this.editedItem
            if (this.editedIndex > -1) {
                const index = this.editedIndex
                // console.log(this.editedItem.user_id);
                // Role.updateUser(this.editedItem.user_id, item)
                axios.put('/api/hr/HR/role/updateUser/'+ this.editedItem.user_id, item)
                .then(response =>{
                    Object.assign(this.users[index], response.data)
                    console.log("data", response.data);
                    this.$swal({
                        title: "Success",
                        text: "Update Successfully!",
                        icon: "success",
                    })
                    this.close();
                })
                .catch(err => {
                    console.log(err.response)
                    this.$swal(
                    {
                        title: 'Oops',
                        text:"Fail, something went wrong!",
                        icon: 'error'
                    }
                )
                })
            }
            this.getRole();
        },
     },
   }
 </script>
