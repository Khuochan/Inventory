<template>
     <v-container fluid grid-list-xl>
        <v-card class="rounded-0" >
            <v-data-table
            :headers="headers"
            :items="depts"
            class="elevation-1"
            >
            <template v-slot:top>
                <v-toolbar
                flat color="white"
                >
                <v-toolbar-title>Departments</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn color="blue" prepend-icon="mdi-plus-circle" variant="tonal" v-bind="props">
                            add department
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
                                v-model="editedItem.dept_name"
                                label="Department Name"
                                variant="outlined"
                            ></v-text-field>
                            <v-text-field
                                v-model="editedItem.sort_name"
                                label="Sort Name"
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
// import Dept from '../../services/api/department'
    export default {
      data: () => ({
        dialog: false,
        dialogDelete: false,
        depts: [],
        headers: [
          {
            title: 'Name',
            align: 'start',
            sortable: false,
            key: 'dept_name',
          },
          { title: 'Sort_Name', key: 'sort_name' },
          { title: 'Actions', key: 'actions', sortable: false },
        ],
        editedIndex: -1,
        editedItem: {
          sort_name: '',
          dept_name: '',
        },
        defaultItem: {
          dept_name: '',
          sort_name: '',
        },
      }),
      computed: {
        formTitle () {
          return this.editedIndex === -1 ? 'New Department' : 'Edit Department'
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
            this.getDept();
        },
      methods: {
        getDept() {
            axios.get('/api/hr/HR/department/listTable')
            // Dept.listTable()
            .then(response => {
                this.depts = response.data.data;
                // console.log("test", this.depts);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        editItem (item) {
          this.editedIndex = this.depts.indexOf(item)
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
                    axios.delete('/api/hr/HR/department/delete/' + item.id)
                    // Dept.deleteDept(item.id)
                    this.$swal({
                        title: "Success",
                        text: "Delete Successfully!",
                        icon: "success",
                    })
                    this.getDept();
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
            this.getDept();
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
            // console.log(this.editedItem.id);
            axios.put('/api/hr/HR/department/update/' + this.editedItem.id, this.editedItem)
            // Dept.updateDept(this.editedItem.id, this.editedItem)
            .then(response =>{
                Object.assign(this.depts[index], response.data)
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
            // Dept.createDept(this.editedItem)
            axios.post('/api/hr/HR/department/create' ,this.editedItem)
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
            this.getDept()
            this.close()
        },
      },
    }
  </script>

