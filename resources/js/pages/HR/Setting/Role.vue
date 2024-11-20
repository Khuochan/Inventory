<template>
    <v-container fluid grid-list-xl>
       <v-card class="rounded-0" >
           <v-data-table
           :headers="headers"
           :items="roles"
           class="elevation-1"
           >
           <template v-slot:top>
               <v-toolbar
               flat color="white"
               >
               <v-toolbar-title>Positions</v-toolbar-title>
               <v-spacer></v-spacer>
               <v-dialog
                   v-model="dialog"
                   max-width="500px"
               >
                   <template v-slot:activator="{ props }">
                       <v-btn color="blue" prepend-icon="mdi-plus-circle" variant="tonal" v-bind="props">
                           add position
                       </v-btn>
                   </template>
                   <v-card>
                   <v-card-title class="text-center">
                       <span class="text-h5 text-indigo-accent-2">{{ formTitle }}</span>
                   </v-card-title>
                   <!-- <v-divider></v-divider> -->
                   <v-card-text>
                       <v-container>
                           <v-text-field
                               v-model="editedItem.role_name"
                               label="Position Name"
                               variant="outlined"
                           ></v-text-field>
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
import axios from 'axios'
// import Role from '../../services/api/role'
   export default {
     data: () => ({
       dialog: false,
       dialogDelete: false,
       roles: [],
       headers: [
         {
           title: 'Name',
           align: 'start',
           sortable: false,
           key: 'role_name',
         },
         { title: 'Actions', key: 'actions', sortable: false },
       ],
       editedIndex: -1,
       editedItem: {
         role_name: '',
       },
       defaultItem: {
         role_name: '',
       },
     }),
     computed: {
       formTitle () {
         return this.editedIndex === -1 ? 'New Position' : 'Edit Position'
       },
     },

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
       },
     methods: {
       getRole() {
        //    Role.listTable()
        axios.get('/api/hr/HR/role/listTable')
           .then(response => {
               this.roles = response.data.data;
            //    console.log("role", this.roles);
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
       editItem (item) {
         this.editedIndex = this.roles.indexOf(item)
         this.editedItem = Object.assign({}, item)
         this.dialog = true
       },
       deleteItemConfirm (item) {
        //    console.log("id", item)
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
                axios.delete('/api/hr/HR/role/delete/' + item.id)
                //    Role.deleteRole(item.id)
                   this.$swal({
                       title: "Success",
                       text: "Delete Successfully!",
                       icon: "success",
                   })
                   this.getRole();
                   this.closeDelete()
               }
           })
           .catch(err =>{
               console.log(err.response)
               this.$swal(
                   {
                       title: 'Oops',
                       text:"Fail, something went wrong!",
                       icon: 'error'
                   }
               )
           });
           this.getRole();
           this.closeDelete()
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

       save () {
           if (this.editedIndex > -1) {
           const index = this.editedIndex
        //    console.log(this.editedItem.id);
        //    Role.updateRole(this.editedItem.id, this.editedItem)
        axios.put('/api/hr/HR/role/update/' + this.editedItem.id, this.editedItem)
           .then(response =>{
               Object.assign(this.roles[index], response.data)
               console.log("data", response.data);
                   this.$swal({
                       title: "Success",
                       text: "Update Successfully!",
                       icon: "success",
                   });
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
       } else {
        //    console.log(this.editedItem)
        //    Role.createRole(this.editedItem)
        axios.post('/api/hr/HR/role/create' , this.editedItem)
           .then(res => {
               console.log(res);
               if (res.data.data == null) {
                   this.$swal({
                       title: "Oops",
                       text: "Fail to create, something went wrong!",
                       icon: "error",
                   });
               }else {
                   this.$swal({
                       title: "Success",
                       text: "Create Successfully!",
                       icon: "success",
                   });
               }
           })
           .catch(err => {
               console.dir(err)
               this.$swal(
                       {
                           title: 'Oops',
                           text:"Fail, something went wrong!",
                           icon: 'error'
                       }
                   )
           })
       }
           this.getRole()
           this.close()
       },
     },
   }
 </script>
