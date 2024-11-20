<template>
  <v-container fluid grid-list-xl>
    <div class="d-flex justify-space-between">
        <div class="ma-2 me-auto" style="width:200px;">
                <v-select
                    label="Department"
                    v-model="params.filters.dept_id"
                    item-value="id"
                    item-title="sort_name"
                    :items="depts"
                    clearable
                    variant="solo"
                >
                </v-select>
        </div>
        <div class="ma-2 pa-2">
            <v-dialog v-model="dialog1" persistent width="400">
                <template v-slot:activator="{ props }">
                    <v-btn color="green" class="ma-2 pa-2"  prepend-icon="mdi-file-import" v-bind="props">
                        import
                    </v-btn>
                </template>
                <v-card>
                        <v-card-title>
                            <span class="text-h5">Import file</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-file-input label="File input" variant="outlined" accept=".csv" @change="handleFileUpload"></v-file-input>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="grey"
                            variant="flat"
                            @click="dialog1 = false"
                        >
                            Close
                        </v-btn>
                        <v-btn
                            color="blue-darken-1"
                            variant="flat"
                            @click="importCsv"
                        >
                            Save
                        </v-btn>
                        </v-card-actions>
                </v-card>
            </v-dialog>
            <v-menu open-on-hover>
                <template v-slot:activator="{ props }">
                    <v-btn color="green" class="ma-2 pa-2" prepend-icon="mdi-file-export" v-bind="props">
                        export
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item color="primary" @click="generatePDF">
                        <v-list-item-title>Export to PDF</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="Export">
                        <v-list-item-title>Export to CSV</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

        </div>
    </div>
    <v-card class="rounded-0" >
        <v-data-table
            :headers="headers"
            :items="filterEm"
            item-key="name"
            :search="params.search"
            :custom-filter="customFilter"
            class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar
            flat color="white"
            >
            <v-toolbar-title>Employees</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field
                density="compact"
                variant="outlined"
                label="Search"
                hide-details
                v-model="params.search"
                @input="getEmployee(params)"
            ></v-text-field>
            <div class="ma-2">
                    <router-link :to="{ name: 'AddEmployee' }" class="text">
                        <v-btn color="blue" prepend-icon="mdi-plus-circle" variant="tonal">
                        add Employee
                        </v-btn>
                    </router-link>
            </div>
            <v-dialog v-model="dialog" max-width="800px">
                <v-card>
                    <v-card-title class="text-center">
                        <span class="text-h5 text-indigo-accent-2">Edit Employee</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="6">
                                    <v-text-field
                                        v-model="editedItem.em_id"
                                        label="Employee ID"
                                        variant="outlined"
                                        readonly
                                        :rules="[v => !!v || 'Employee ID is required']"
                                    ></v-text-field>
                                </v-col>
                                    <v-col cols="12" sm="6" md="6">
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
                                    <v-col cols="12" sm="6" md="6">
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
                                            v-model="editedItem.first_name"
                                            label="First Name"
                                            :rules="nameRules"
                                            variant="outlined"
                                        ></v-text-field>
                                        <div class="error" v-if="errors.name">
                                            {{ errors.name }}
                                        </div>
                                    </v-col>

                                    <v-col cols="12" sm="6" md="6">
                                        <v-radio-group
                                            v-model="editedItem.gender"
                                            inline
                                            >
                                            <v-radio
                                                label="Male"
                                                value="Male"
                                                color="blue"
                                            ></v-radio>
                                            <v-radio
                                                label="Female"
                                                value="Female"
                                                color="blue"
                                            ></v-radio>
                                        </v-radio-group>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-select
                                        v-model="editedItem.family_status"
                                        :items="status"
                                        item-value="id"
                                        item-title="dept_name"
                                        label="Family Status"
                                        variant="outlined"
                                        required
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="pass.password"
                                            label="New Password"
                                            variant="outlined"
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                            @click:append="showConfirmPassword = !showConfirmPassword"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="pass.con_password"
                                            label="Confirm Password"
                                            variant="outlined"
                                            :rules="[v => pass.password === v || 'Passwords do not match']"
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                            @click:append="showConfirmPassword = !showConfirmPassword"
                                            required
                                        ></v-text-field>
                                        <div class="error" v-if="errors.password">
                                            {{ errors.password}}
                                        </div>
                                    </v-col>

                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="editedItem.email"
                                            label="Email"
                                            :rules="[rules.email]"
                                            variant="outlined"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-text-field
                                            v-model="editedItem.phone"
                                            label="Phone"
                                            variant="outlined"
                                            @input="formatPhoneNumber"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field
                                            v-model="editedItem.address"
                                            label="Address"
                                            variant="outlined"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4">
                                        <v-text-field
                                            v-model="editedItem.start_date"
                                            label="Start_date"
                                            variant="outlined"
                                            :rules="[v => !!v || 'Start date is required']"
                                            type="date"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4">
                                        <v-select
                                            v-model="editedItem.dept_id"
                                            :items="depts"
                                            item-value="id"
                                            item-title="sort_name"
                                            label="Department"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4">
                                        <v-select
                                            v-model="editedItem.role_id"
                                            :items="roles"
                                            item-value="id"
                                            item-title="role_name"
                                            label="Position"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-divider></v-divider>
                                    <v-card-title>Family Information</v-card-title>
                                    <v-col cols="12"></v-col>
                                    <v-col cols="12" sm="6" md="6">
                                            <v-text-field
                                            v-model="editedItem.family_phone"
                                            label="Family Phone Number"
                                            variant="outlined"
                                            @input="formatPhoneNumber"
                                            prepend-inner-icon="mdi-phone"
                                            ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                            <v-text-field
                                            v-model="editedItem.family_name"
                                            label="Family Name"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-account"
                                            required
                                            ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                            <v-text-field
                                            v-model="editedItem.family_member"
                                            label="Family Member"
                                            variant="outlined"
                                            prepend-inner-icon="mdi-account"
                                            required
                                            ></v-text-field>
                                    </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                            <v-spacer></v-spacer>
                            <!-- <span v-if="editedItem.active == true" class="mr-2">
                                <v-btn
                                color="green"
                                variant="tonal"
                                @click="save"
                                >
                                Active
                                </v-btn>
                            </span>
                            <span v-else class="mr-2">
                                <v-btn
                                color="red"
                                variant="tonal"
                                @click="save"
                                >
                                Inactive
                                </v-btn>
                            </span> -->
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
            {{ item.last_name }} {{ item.first_name }}
        </template>
        <template v-slot:[`item.dept`]="{ item }">
            {{ item.sort_name }}
        </template>
        <template v-slot:[`item.address`]="{ item }">
            <span v-if="item.address.length <= 15">
              {{ item.address }}
            </span>
            <span v-else>
              {{ item.address.slice(0, 15) }}...
            </span>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <v-icon
            size="small"
            class="me-2"
            color="yellow"
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
import { mapActions, mapGetters } from 'vuex';
// import Staff from '../../services/api/employee'
// import Dept from '../../services/api/department'
// import Role from '../../services/api/role'
import { excelParser} from './CSVFile'
import { generatePDF } from './PdfFile'

export default {
    name: 'ManageEmployee',
    computed: {
        ...mapGetters("employees", {
            employee: 'employees'
        }),
        filterEm() {
            // Apply the search filter
            // search
            if (this.params.search) {
                let searchRegex = new RegExp(this.params.search, 'i');
                this.em = this.em.filter(item => searchRegex.test(item.name) || searchRegex.test(item.email));
            }
            // filter
            if(!this.params.filters.dept_id) {
                return this.em;
            }
            else {
                return this.em.filter(item => item.dept_id === this.params.filters.dept_id);
            }
        },
        params: () => {
            return {
                page: 1,
                limit: 25,
                search: null,
                keySearch: ["name", "sort_name"],
                filters: { dept_id: null }
            }
        },

    },
    data() {
        return {
            showConfirmPassword: false,
            name: "Employees",
            dialog: false,
            dialog1: false,
            dialogDelete: false,
            errors: [],
            error: false,
            processing:false,
            em: [],
            depts: [
                {title: 'sort_name'}
            ],
            roles: [
                {title: 'role_name'}
            ],
            params: {
                filters: {
                    sort_name: ''
                }
            },
            headers: [
                { title: 'ID', key: 'em_id' },
                {
                    title: 'Name',
                    sortable: false,
                    key: 'name',
                },
                { title: 'Email', key: 'email' },
                { title: 'Phone', key: 'phone', },
                { title: 'Department', key: 'dept',align: 'center' },
                { title: 'Address', key: 'address'},
                { title: 'Joining Date', key: 'start_date' },
                { title: 'Actions', key: 'actions', align: 'center', sortable: false},
            ],
            editedIndex: -1,
            editedItem: {
                last_name: '',
                first_name: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                dept_id: '',
                start_date: '',
                family_member: '',
                family_name: '',
                family_phone: '',
                family_status: '',
            },
            pass: {
                password: '',
                con_password: '',
            },
            status: [
                'Single',
                'Married',
            ],
            defaultItem: {
                password: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                dept_id: '',
                start_date: '',
                family_member: '',
                family_name: '',
                family_phone: '',
                family_status: '',
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
        }
    },
    mounted() {
        this.getEmployee(this.params);
        this.FilterDept();
        this.FilterRole();
    },
    watch: {
        dialog (val) {
          val || this.close()
        },
        dialogDelete (val) {
          val || this.closeDelete()
        },
      },
    methods: {
        // ...mapActions({
        //     getEmployee: "employees/getEmployees",
        // }),
        handleFileUpload(event) {
            this.file = event.target.files[0];
        // Perform further processing with the uploaded CSV file
        },
        importCsv() {
            const formData = new FormData();
            formData.append('file', this.file);
            console.log(this.file)
            axios.post('/api/ticket/import', formData)
            .then(res => {
                console.log(res.data)
                setTimeout(function() {
                        alert('Uploaded successfuly...');
                }, 3000);
            })
            this.dialog = false;
        },
        formatPhoneNumber() {
            let formattedValue = this.editedItem.phone.replace(/\D/g, ''); // Remove non-digit characters
            let formattedFamilyPhone = this.editedItem.family_phone.replace(/\D/g, ''); // Remove non-digit characters

            if (formattedValue.length >= 9) {
                formattedValue = formattedValue.replace(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})$/, '$1 $2 $3');
            }
            if (formattedValue.length <= 10) {
                formattedValue = formattedValue.replace(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/, '$1 $2 $3');
            }

            if (formattedFamilyPhone.length >= 9) {
                formattedFamilyPhone = formattedFamilyPhone.replace(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})$/, '$1 $2 $3');
            }
            if (formattedFamilyPhone.length <= 10) {
                formattedFamilyPhone = formattedFamilyPhone.replace(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/, '$1 $2 $3');
            }

            this.editedItem.phone = formattedValue;
            this.editedItem.family_phone = formattedFamilyPhone;
        },
        generatePDF() {
            // Staff.listTable()
            axios.post('/api/hr/HR/staff/listTable')
                .then((response) => {
                    const data = response.data.data;
                    console.log(data);
                    // Add content to the PDF
                    generatePDF(data);
                })
                .catch((error) => {
                    if (error.response) {
                        this.errors = error.response.data.errors;
                    }
                });
        },
        Export() {
            axios.post('/api/hr/HR/staff/listTable')
            // Staff.listTable()
                .then((response) => {
                    const data = response.data.data;
                    console.log(data);
                    excelParser().exportDataFromJSON(data, null, null);
                })
                .catch((error) => {
                if (error.response) {
                    this.errors = error.response.data.errors;
                }
                });
        },
        customFilter(value, search) {
            if (!this.params.filters.dept_id) {
                return true;
            }
            else {
                return value.dept_id=== this.params.filters.dept_id;
            }
        },
        getEmployee() {
            axios.post('/api/hr/HR/staff/dataTable')
            // Staff.dataTable()
            .then(response => {
                this.em = response.data.data.data;
                console.log("employee", this.em);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        FilterDept() {
            axios.get('/api/hr/HR/department/Deptall')
            // Dept.getDept()
            .then(res => {
                this.depts = res.data.data
                // console.log("test", this.depts)
            }).catch(err=> {
                console.log(err)
            })
        },
        FilterRole() {
            axios.get('/api/hr/HR/role/listTable')
            // Role.listTable()
            .then(res => {
                this.roles = res.data.data
                // console.log("role", this.roles)
            }).catch(err=> {
                console.log(err)
            })
        },
        editItem (item) {
          this.editedIndex = this.em.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialog = true
          console.log(this.editedItem)
        },
        deleteItemConfirm(item) {
            console.log("id", item)
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
                    axios.delete('/api/hr/HR/staff/delete/'+ item.user_id)
                    // Staff.deleteEmployee(item.user_id);
                    this.$swal({
                        title: "Success",
                        text: "Deleted Successfully!",
                        icon: "success",
                    })
                    this.getEmployee();
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
                this.getEmployee();
                this.closeDelete()
            });
        },
        save() {
            let item = {}
            this.editedItem.password = this.pass.password
            item = this.editedItem
            if (this.editedIndex > -1) {
                const index = this.editedIndex
                axios.put('/api/hr/HR/staff/update/'+ this.editedItem.user_id, item)
                // Staff.updateEmployee(this.editedItem.user_id, item)
                .then(response =>{
                    Object.assign(this.em[index], response.data)
                    console.log("data", response.data);
                        this.$swal({
                            title: "Success",
                            text: "Update Successfully!",
                            icon: "success",
                        });
                    this.close();
                    this.getEmployee()
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
            this.getEmployee()
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
    }
}
</script>

<style>

</style>
