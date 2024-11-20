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
                        ORDER INFORMATION
                    </v-card-text>
                    <v-dialog v-model="dialog" max-width="700px">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" class="mb-2" v-bind="props"
                                >New Incoming</v-btn
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
                                                :items="banks"
                                                item-value="id"
                                                item-title="customer_name"
                                                v-model="editedItem.bank_id"
                                                label="Bank Name"
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col>
                                            <v-select
                                                :items="types"
                                                item-value="id"
                                                item-title="terminal_type"
                                                v-model="editedItem.type_id"
                                                label="Machine Type"
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-select
                                                :items="models"
                                                item-value="id"
                                                item-title="terminal_model"
                                                v-model="editedItem.model_id"
                                                label=" Machine Model"
                                                variant="outlined"

                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-text-field
                                                v-model="editedItem.quantity"
                                                label="Quantity"
                                                variant="outlined"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-select
                                                :items="statuses"
                                                item-value="id"
                                                item-title="status"
                                                v-model="editedItem.status_id"
                                                label=" Status"
                                                variant="outlined"

                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-select
                                                :items="warehouses"
                                                item-value="id"
                                                item-title="warehouse_name"
                                                v-model="editedItem.warehouse_id"
                                                label=" Warehouse"
                                                variant="outlined"

                                            ></v-select>
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
                        :items="orders"
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
                    title: "Order For",
                },
                {
                    key: "terminal_type",
                    title: "Type",
                },
                {
                    key: "terminal_model",
                    title: "Model",
                },
                {
                    key: "quantity",
                    title: "Quantity",
                },
                {
                    key: "status",
                    title: "Status",
                },
                {
                    key: "warehouse_name",
                    title: "Warehouse",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            orders: [],
            editedIndex: -1,
            editedItem: {
                bank_id:"",
                model_id:"",
                type_id:"",
                warehouse_id:"",
                status_id:"",
                quantity:"",
            },
            defaultItem: {
                bank_id:"",
                model_id:"",
                type_id:"",
                warehouse_id:"",
                status_id:"",
                quantity:"",
            },
            banks:[{title:'customer_name'}],
            types:[{title:'terminal_type'}],
            models:[{title:'terminal_model'}],
            warehouses:[{title:'warehouse_name'}],
            statuses:[{title:'status'}],
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? "Add new incoming machine"
                : "Update information";
        },
    },
    watch: {
        dialog(val) {
            val || this.closedialog();
        },
    },
    created() {
        this.getBank();
    },

    methods: {
        getBank() {
            axios
                .get("/api/IMS/order/getorder")
                .then((Response) => {
                    this.orders = Response.data;
                    console.log(this.orders);
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
            this.editedIndex = this.orders.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
            console.log('data', this.banklocations)
        },
        savebank() {
            if (this.editedIndex > -1) {
                Object.assign(this.orders[this.editedIndex], this.editedItem);
                axios.put("/api/IMS/order/updateorder/"+ this.editedItem.id, this.editedItem)
                    .then((Response) => {

                            Swal.fire({
                                title: "Updated successfully",
                                icon: "success",
                            });
                            console.log(Response.data);
                            this.closedialog();

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
                console.log(this.editedItem.id);
            }
            else{
                axios
                .post( "/api/IMS/order/addorder/",this.editedItem )
                .then((Response) => {
                    if (Response.status == 200) {
                        Swal.fire({
                            title: Response.data.message,
                            icon: "success",
                        });
                        this.closedialog();
                        console.log(Response.data);
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        title: error.response.data.message,
                        icon: "warning",
                        position: "top",
                        showConfirmButton: false,

                    });
                    console.log(error.response.data.message);
                });
            }
            this.getBank();
        },
    },
    mounted:function(){

        this.banks = axios.get("/api/IMS/customer/getallcustomer")
            .then((Response) => {
                    this.banks = Response.data;
                    console.log("bank",this.banks);
                })
                .catch((error) => {
                    console.log(error);
                });

        this.types = axios.get("/api/IMS/terminaltype/allterminaltype")
            .then((Response) => {
                    this.types = Response.data;
                    console.log("type",this.types);
                })
                .catch((error) => {
                    console.log(error);
                });
        this.models=axios.get("/api/IMS/terminalmodel/getallmodel")
            .then((Response) => {
                    this.models = Response.data;
                    console.log("model", this.models);
                })
                .catch((error) => {
                    console.log(error);
                });
        this.warehouses = axios.get("/api/IMS/warehouse/getallwarehouse")
            .then((Response) => {
                        this.warehouses = Response.data;
                        console.log("warehouse",this.warehouses);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
        this.statuses = axios.get("/api/IMS/status/all")
            .then((Response) => {
                    this.statuses = Response.data;
                    console.log("status", this.statuses);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
};
</script>
