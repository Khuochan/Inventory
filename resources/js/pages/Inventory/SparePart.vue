<template>
    <v-container fluid grid-list-xl>
        <div class="d-flex mb-2">
            <div class="ma-2 pa-2" style="width:200px;">
                    <v-select
                    v-model="filter.model"
                    label="Model"
                    density="compact"
                    item-title="terminal_model"
                    :items="models"
                    variant="solo"
                    clearable
                    ></v-select>
            </div>
        </div>
       <v-card class="rounded-0">
           <v-data-table
           :headers="headers"
           :items="Spare"
           class="elevation-1 design"
           >
           <template v-slot:top>
               <v-toolbar
               flat color="white"
               >
               <v-toolbar-title>Spare Part</v-toolbar-title>
               <v-spacer></v-spacer>
               <v-dialog
                   v-model="dialog"
                   max-width="500px"
               >
                   <template v-slot:activator="{ props }">
                       <v-btn color="blue" prepend-icon="mdi-plus-circle" variant="tonal" v-bind="props">
                           add spare part
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
                               v-model="editedItem.sparepart_name"
                               label="Spare Part Name"
                               variant="outlined"
                           ></v-text-field>
                           <v-text-field
                               v-model="editedItem.part_number"
                               label="Part Number"
                               variant="outlined"
                           ></v-text-field>
                           <v-select
                                v-model="editedItem.model_id"
                                :items="models"
                                item-value="id"
                                item-title="terminal_model"
                                label="Model"
                                variant="outlined"
                                required
                           ></v-select>
                           <v-checkbox v-model="editedItem.serialized" label="Serialized"></v-checkbox>
                            <slot :item="editedItem"></slot>
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
           <template v-slot:[`item.model`]="{ item }">
                {{ item.terminal_model }}
           </template>
           <template v-slot:[`item.serialized`]="{ item }">
                <template v-if="item.serialized === true">
                yes
              </template>
              <template v-else>
                no
              </template>

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
   export default {
     data: () => ({
       dialog: false,
       dialogDelete: false,
       datas: [],
       models: [],
       filter: {
        model: null
       },
       headers: [
         {
           title: 'Spare Part Name',
           align: 'start',
           sortable: false,
           key: 'sparepart_name',
         },
         { title: 'Part Number', key: 'part_number' },
         { title: 'Model', key: 'model' },
         { title: 'Serialized', key: 'serialized' },
         { title: 'Actions', key: 'actions', sortable: false },
       ],
       editedIndex: -1,
       editedItem: {
         sparepart_name: '',
         part_number: '',
         model_id: '',
         serialized: false
       },
       defaultItem: {
         dept_name: '',
         sparepart_name: '',
         part_number: '',
         model_id: ''
       },
     }),
     computed: {
       formTitle () {
         return this.editedIndex === -1 ? 'New Spare Part' : 'Edit Spare Part'
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
           this.getData();
           this.getModel();
       },

    computed: {
        Spare(){
            const filtered = this.datas.filter(data => {
                const model = !this.filter.model || data.terminal_model === this.filter.model;

                return model;
            })

            return filtered;
        },
        formTitle () {
          return this.editedIndex === -1 ? 'New Spare Part' : 'Edit Spare Part'
        },
    },
     methods: {
       getData() {
           axios.get('/api/v3/IMS/sparepart/getall')
           .then(response => {
               this.datas = response.data.data;
            //    console.log("test", this.datas);
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
       getModel() {
           axios.get('/api/IMS/terminalmodel/getallmodel')
           .then(response => {
               this.models = response.data;
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
       editItem (item) {
         this.editedIndex = this.datas.indexOf(item)
         this.editedItem = Object.assign({}, item)
         this.dialog = true
       },
       deleteItemConfirm (item) {
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
                   axios.delete('/api/v3/IMS/sparepart/delete/' + item.id)
                   this.$swal({
                       title: "Success",
                       text: "Delete Successfully!",
                       icon: "success",
                   })
                   this.getData();
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
           this.getData();
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
           axios.put('/api/v3/IMS/sparepart/update/' + this.editedItem.id, this.editedItem)
           .then(response =>{
               Object.assign(this.datas[index], response.data)
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
           // console.log(this.editedItem)
           axios.post('/api/v3/IMS/sparepart/create' ,this.editedItem)
           .then(res => {
               // console.log(res);
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
           this.getData()
           this.close()
       },
     },
   }
 </script>

 <style>
 .design th{
    background-color: #3c519c!important;
    color: white!important;
}
</style>

