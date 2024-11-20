<template>
  <v-container fluid grid-list-xl>
    <!-- leave balance -->
    <v-row class="py-2">
        <v-col cols="12" md="4" sm="4" >
            <v-card  class="mx-auto" max-width="240">
               <v-card-title class="ma-2 text-center">Annaul Leave</v-card-title>
               <v-card-text class="py-0 ma-2">
                    <v-row align="center" no-gutters>
                        <v-col class="text-h2 text-grey-darken-3 ml-2" cols="6">
                            <span v-if="leave_al.leave_name == 'Annual Leave'">
                                {{ leave_al.leave_balance }}
                            </span>
                            <span v-else>
                                0
                            </span>
                        </v-col>
                        <v-col cols="4" class="text-right">
                            <v-icon
                                color="orange"
                                icon="mdi-bag-suitcase-outline"
                                size="88"
                            ></v-icon>
                        </v-col>
                    </v-row>
               </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" md="4" sm="4" >
            <v-card  class="mx-auto" max-width="240">
               <v-card-title class="ma-2 text-center">Sick Leave</v-card-title>
               <v-card-text class="py-0 ma-2">
                    <v-row align="center" no-gutters>
                        <v-col class="text-h2 text-grey-darken-3 ml-2" cols="6">
                            <span v-if="leave_sl.leave_name == 'Sick Leave'">
                                {{ leave_sl.leave_balance }}
                            </span>
                            <span v-else>
                                0
                            </span>
                        </v-col>
                        <v-col cols="4" class="text-right">
                            <v-icon
                                color="green"
                                icon="mdi-bed"
                                size="88"
                            ></v-icon>
                        </v-col>
                    </v-row>
               </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" md="4" sm="4" >
            <v-card  class="mx-auto" max-width="240">
               <v-card-title class="ma-2 text-center">Unpaid Leave</v-card-title>
               <v-card-text class="py-0 ma-2">
                    <v-row align="center" no-gutters>
                        <v-col class="text-h2 text-grey-darken-3 ml-2" cols="6">
                            <span v-if="leave_ul.leave_id== 3">
                                {{ leave_ul.leave_balance}}
                            </span>
                            <span v-else>
                                0
                            </span>
                        </v-col>
                        <v-col cols="4" class="text-right">
                            <v-icon
                                color="blue"
                                icon="mdi-calendar-multiselect"
                                size="88"
                            ></v-icon>
                        </v-col>
                    </v-row>
               </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="6" md="4" sm="4">
           <v-card>
            <div class="text-center text-grey-darken-3">
                <v-avatar size="150" class="ma-6">
                    <v-img :src="getImageUrl(user.image)" />
                </v-avatar>
                <h3>{{ user.last_name }} {{ user.first_name }}</h3>
            </div>
            <div class="ma-4 text-grey-darken-2">
                <h4>Details</h4>
                <v-divider></v-divider>
                <v-list>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title><b>ID:</b> {{ user.em_id }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Gender:</b> {{ user.gender }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Email:</b> {{ user.email }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Phone:</b> {{ user.phone }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Address:</b> {{ user.address }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Department:</b> {{ user.dept_name }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Start Date:</b> {{ user.start_date }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><b>Position:</b> <strong class="text-blue">{{ user.role_name }}</strong></v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
            </div>
           </v-card>
        </v-col>
        <v-col>
            <div class="text-right">
                <v-btn color="green" class="ma-2 pa-2" prepend-icon="mdi-file-export" @click="generatePDF">
                        export leave
                </v-btn>
            </div>
            <v-card rounded="lg" class="mt-4">
                <v-data-table
                    :headers="headers"
                    :items="leaves"
                    class="elevation-1"
                >
                <template v-slot:top>
                <v-toolbar
                flat color="white"
                >
                <v-toolbar-title>Leave Annuals</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn color="blue" prepend-icon="mdi-plus-circle" variant="tonal" v-bind="props">
                            add
                        </v-btn>
                    </template>
                    <v-card>
                    <v-card-title class="text-center">
                        <span class="text-h5 text-indigo-accent-2">{{ formTitle }}</span>
                    </v-card-title>
                    <!-- <v-divider></v-divider> -->
                    <v-card-text>
                        <v-container>
                            <v-select
                                v-model="editedItem.year"
                                :items="yearOptions"
                                item-value="id"
                                item-title="year_number"
                                label="Year"
                                variant="outlined"
                            ></v-select>
                            <v-select
                                v-model="editedItem.leave_id"
                                :items="leave_types"
                                item-value="id"
                                item-title="leave_name"
                                label="Leave Type"
                                variant="outlined"
                            ></v-select>
                            <v-text-field
                                v-model="editedItem.credit_leave"
                                label="Credit Leave"
                                variant="outlined"
                                type="number"
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
                        @click="save(user.user_id)"
                        >
                        Save
                        </v-btn>
                    </v-card-actions>
                    </v-card>
                </v-dialog>
                </v-toolbar>
                <v-divider></v-divider>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                    size="small"
                    class="me-2"
                    color="green"
                    @click="editItem(item.raw)"
                    >
                    mdi-pencil
                    </v-icon>
                    <v-icon
                    size="small"
                    color="red"
                    @click="deleteItemConfirm(item.raw)"
                    >
                    mdi-delete
                    </v-icon>
                </template>
                </v-data-table>
            </v-card>
        </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios';
// import Api from "../../services/api/Api";
import { generatePDF } from './PdfEmployee'

export default {
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            { title: 'Leave Type', key: 'leave_name'},
            { title: 'Entitle Leave', key: 'credit_leave'},
            { title: 'Actions', key: 'actions'}
        ],
        user: {},
        years: [
            {title: 'year_number'}
        ],
        leaves: [],
        leave_al: {},
        leave_sl: {},
        leave_ul: {},
        leave_balance: {},
        id: '',
        em: [],
        leave_types: [
            {title: 'leave_name'}
        ],
        editedIndex: -1,
        editedItem: {
          user_id: '',
          leave_id: '',
          credit_leave: '',
        },
        defaultItem: {
            leave_id: '',
            credit_leave: '',
        },
    }),

    computed: {
        formTitle () {
          return this.editedIndex === -1 ? 'New Leave Annual' : 'Edit Leave Annual'
        },

        yearOptions() {
            const startYear = 2020;
            const endYear = 2100;
            const years = [];
            for (let year = startYear; year <= endYear; year++) {
                years.push(year);
            }
            return years;
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
        this.id = this.$route.params.id;
        // console.log(this.id);
        // get User
        axios.get('http://localhost:8000/api/hr/HR/staff/getStaff/'+this.id)
        .then(res => {
            this.user = res.data[0];
            // console.log('this user:', this.user);
        }).catch(err => {
            console.log("create error", err);
        })
        // get Leave credit
        this.getLeave();

        // get leave type
        axios.get('http://localhost:8000/api/hr/HR/leave/listTable')
        .then(res => {
            this.leave_types = res.data.data;
        })
        this.getYear();

        // leave balance
        axios.get('http://localhost:8000/api/hr/HR/attendance/leave_blance/'+this.id)
        .then(res => {
            // this.leave_balance = res.data.user
            // this.leave_al = res.data.leave_al[0];
            // this.leave_sl = res.data.leave_sl[0];
            this.leave_ul = res.data.leave_ul[0];
            this.leave_al = res.data.leave_al && res.data.leave_al.length > 0 ? res.data.leave_al[0] : null;
this.leave_sl = res.data.leave_sl && res.data.leave_sl.length > 0 ? res.data.leave_sl[0] : null;
this.leave_ul = res.data.leave_ul && res.data.leave_ul.length > 0 ? res.data.leave_ul[0] : null;

            console.log("this leave balance", res.data)
        })
    },
    methods: {
        generatePDF() {
            axios.get('http://localhost:8000/api/hr/HR/attendance/leave_blance/'+this.id)
            .then(res => {

                const data = [
                    ...res.data.user,
                    ...res.data.leave_al,
                    ...res.data.leave_sl,
                    ...res.data.leave_ul
                ]
                    console.log("pdf", data);
                    // Add content to the PDF
                    generatePDF(data);
            })
            .catch((error) => {
                if (error.response) {
                    this.errors = error.response.data.errors;
                }
            });
        },

        getLeave () {
            axios.get('http://localhost:8000/api/hr/HR/leave_annual/getLeaveStaff/'+this.id)
            .then(res => {
                this.leaves = res.data;
                // console.log("leave", this.leaves);
            })
        },

        getYear () {
            axios.get('http://localhost:8000/api/hr/HR/leave_annual/years')
            .then(res => {
                this.years = res.data
                // console.log("year", this.years);
            })
        },

        editItem (item) {
          this.editedIndex = this.leaves.indexOf(item)
          this.editedItem = Object.assign({}, item)
          console.log('data', this.editedItem);
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

        save (id) {
            let item = {}
            // console.log('this id', id);
            this.editedItem.user_id = id
            item = this.editedItem
            // console.log('annual', this.editedItem)
            if (this.editedIndex > -1) {
                const index = this.editedIndex
                // console.log(this.editedItem.id);
                axios.put(`http://localhost:8000/api/hr/HR/leave_annual/update/${this.editedItem.id}` , this.editedItem)
                .then(response =>{
                    Object.assign(this.leaves[index], response.data)
                    // console.log("data", response.data);
                    if (response.data.data == null) {
                        this.$swal({
                            title: "Oops",
                            text: "Fail to update, something went wrong!",
                            icon: "error",
                        });
                    }else {
                        this.$swal({
                            title: "Success",
                            text: "Update Successfully!",
                            icon: "success",
                        });
                    }
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
                // leave.createLeaveAnnual(this.editedItem)
                axios.post('http://localhost:8000/api/hr/HR/leave_annual/create', this.editedItem)
                .then(res => {
                   console.log('success', res);
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
                }) .catch(err => {
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
            this.close();
            this.getLeave();
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
                    axios.delete('http://localhost:8000/api/hr/HR/leave_annual/delete/' + item.id)
                    this.$swal({
                        title: "Success",
                        text: "Create Successfully!",
                        icon: "success",
                    })
                    this.getLeave();
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
            this.getLeave();
            this.closeDelete()
        },
        getImageUrl(attachment) {
                return `/storage/${attachment}`;
            },
    }
}
</script>

<style>

</style>
