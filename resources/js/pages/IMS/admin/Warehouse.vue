<template>
    <v-main style="padding: 20px 20px 20px 20px; background-color: #f2f7ff">
        <v-row style="padding: 10px; height: 90%">
            <v-card class="rounded-0" width="100%">
                <v-card
                    class="d-flex align-center rouned-0"
                    height="65px"
                    style="padding: 15px"
                    outlined
                >
                    <v-card-text style="font-size: 20px; text-align: start">
                        WAREHOUSES INFORMATION
                    </v-card-text>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" class="mb-2" v-bind="props"
                                >New warehouse</v-btn
                            >
                        </template>
                        <v-card>
                            <v-card-title
                                style="
                                    background-color: blue;
                                    text-align: center;
                                    color: white;
                                "
                                >{{ formTitle }}</v-card-title
                            >
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-select
                                                :items="customers"
                                                item-value="id"
                                                item-title="customer_name"
                                                v-model="editedItem.customer_id"
                                                label="Bank Name"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-text-field
                                                v-model="editedItem.warehouse_name"
                                                label="Warehouse Name"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="12" sm="12">
                                            <v-text-field
                                                v-model="editedItem.address"
                                                label="Address"
                                            >
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="red-darken-1"
                                    variant="text"
                                    @click="closedialog"
                                >
                                    Close
                                </v-btn>
                                <v-btn
                                    color="blue-darken-1"
                                    variant="text"
                                    @click="savebank"
                                >
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card>
                <v-card flat class="mt-2">
                    <template v-slot:text>
                        <div class="d-flex align-end">
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                label="Search"
                                prepend-inner-icon="mdi-magnify"
                                single-line
                                variant="outlined"
                                hide-details
                                width="70px"
                            ></v-text-field>
                        </div>
                    </template>
                    <v-data-table
                        :items-per-page="itemsPerPage"
                        :headers="headers"
                        :items="warehouses"
                        :search="search"
                    >
                        <template v-slot:[`item.created_at`]="{ item }">
                            {{ formatDate(item.created_at) }}
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                                <v-icon
                                    small
                                    color="cyan"
                                    @click="editItem(item)"
                                >
                                    mdi-pencil
                                </v-icon>
                        </template>
                    </v-data-table>
                </v-card>
            </v-card>
        </v-row>
    </v-main>
</template>

<script scope>
import axios from "axios";
import moment from "moment";
import Swal from "sweetalert2";
export default {
    data() {
        return {
            dialog: false,
            itemsPerPage: 10,
            search: "",
            headers: [
                {
                    align: "start",
                    key: "customer_name",
                    sortable: false,
                    title: "Bank Name",
                },
                {
                    key: "warehouse_name",
                    sortable: false,
                    title: "Warehouse Name",
                },
                {
                    title: "Address",
                    key: "address",
                    class: " white--text",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            warehouses: [],
            customers:[],
            editedIndex: -1,
            editedItem: {
                warehouse_name:"",
                customer_id:"",
                address: "",
            },
            defaultItem: {
                warehouse_name:"",
                customer_id:"",
                address: "",
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? "Add new bank warehouse"
                : "Update bank warehouse information";
        },
    },
    watch: {
        dialog(val) {
            val || this.closedialog();
        },
    },
    created() {
        this.getWarehouse();
    },

    methods: {
        getWarehouse() {
            axios
                .get("/api/IMS/warehouse/getallwarehouse")
                .then((Response) => {
                    this.warehouses = Response.data;
                    console.log(this.warehouses);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        closedialog() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        formatDate(value) {
            return moment(value).format("YYYY-MM-DD");
        },
        editItem(item) {
            this.editedIndex = this.warehouses.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        savebank() {
            if (this.editedIndex > -1) {
                Object.assign(this.warehouses[this.editedIndex], this.editedItem);
                axios
                    .put("/api/IMS/warehouse/updatewarehouse/" + this.editedItem.id, this.editedItem)
                    .then((Response) => {
                        if (Response.status == 200) {
                            Swal.fire({
                                title: Response.data.message,
                                icon: "success",
                            });
                            console.log(Response.data);
                            this.closedialog();
                        }
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: error.response.data.message,
                            icon: "warning",
                            position: "top",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        console.log(error.response.status);
                    });
            }
            else{
                axios
                .post( "/api/IMS/warehouse/addwarehouse/",this.editedItem )
                .then((Response) => {
                        if (Response.status == 200) {
                            Swal.fire({
                                title: Response.data.message,
                                icon: "success",
                            });
                            console.log(Response.data);
                            this.closedialog();
                        }
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: error.response.data.message,
                            icon: "warning",
                            position: "top",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        console.log(error.response.status);
                    });
            }
            this.getWarehouse();
        },
    },
    mounted: function(){
        this.customers = axios.get("/api/IMS/customer/getallcustomer")
                .then((Response) => {
                    this.customers= Response.data;
                    console.log(this.customers);
                })
                .catch((error) => {
                    console.log(error);
                });
    }

};
</script>
