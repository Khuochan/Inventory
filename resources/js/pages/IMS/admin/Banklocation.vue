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
                        LOCATION'S BANK INFORMATION
                    </v-card-text>
                    <v-dialog v-model="dialog" max-width="700px">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" class="mb-2" v-bind="props"
                                >New Location</v-btn
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
                                                v-model="editedItem.bank_name_id"
                                                label="Bank Name"
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-select
                                                :items="sites"
                                                item-value="id"
                                                item-title="site_name"
                                                v-model="editedItem.site_name_id"
                                                label="Site Name"
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="editedItem.siteID"
                                                label="Site ID"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="12" sm="12">
                                            <v-text-field
                                            width="100%"
                                                variant="outlined"
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
                                    @click="savesite"
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
                        :items="banklocations"
                        :search="search"
                    >
                        <template v-slot:[`item.actions`]="{ item }">
                                <v-icon
                                    small
                                    @click="editItem(item)"
                                    color="cyan"
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
                    key: "site_name",
                    title: "Site Name",
                },
                {
                    key: "siteID",
                    title: "Site ID",
                },
                {
                    key: "address",
                    title: "address",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            banklocations: [],
            sites:[
                {title: 'site_name'}
            ],

            banks:[
                {title: 'customer_name'}
            ],
            editedIndex: -1,
            editedItem: {
                site_name_id: "",
                bank_name_id:"",
                siteID:"",
                address:"",
         
            },
            defaultItem: {
                site_name_id: "",
                bank_name_id:"",
                siteID:"",
                address:"",
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? "Add new location"
                : "Update location information";
        },
    },
    watch: {
        dialog(val) {
            val || this.closedialog();
        },
      
    },
    created() {
        this.getbanklocation();
    },

    methods: {
        getbanklocation() {
            axios
                .get("/api/IMS/banklocation/getall")
                .then((Response) => {
                    this.banklocations = Response.data;
                    console.log(this.banklocations);
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
       
        editItem(item) {
            this.editedIndex = this.banklocations.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        savesite() {
          
          if(this.editedIndex >-1){
            Object.assign(this.banklocations[this.editedIndex], this.editedItem)
            axios.put("/api/IMS/banklocation/updatebanklocation/" +this.editedItem.id,this.editedItem)
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
                        position: "top",
                        showConfirmButton: false,
                        timer: 1500
                        });
                });
          }
          else{
            axios.post("/api/IMS/banklocation/addbanklocation",this.editedItem)
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
                            position: "top",
                            showConfirmButton: false,
                            timer: 1500
                            });
                    });
          }
          this.getbanklocation();
        },
        deleteItem(item){
         
        }
    },
    mounted: function(){
        this.banks = axios.get("/api/IMS/customer/getallcustomer")
                .then((Response) => {
                    this.banks = Response.data;
                    console.log(this.banks);
                })
                .catch((error) => {
                    console.log(error);
                });

        this.sites = axios.get("/api/IMS/site/getallsite")
                .then((Response) => {
                    this.sites = Response.data;
                    console.log(this.sites);
                })
                .catch((error) => {
                    console.log(error);
                });
    },
};
</script>
