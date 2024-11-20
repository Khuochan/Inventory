<template>
    <v-container fluid grid-list-xl class="pt-0">
        <v-card>
            <v-card-title>Update terminal information</v-card-title>
            <v-container>
                <v-row class="pt-2" variant="outlined">
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="atm_id">ATM ID<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.atm_id" variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="alias_id">Alias ATM ID</label>
                        <v-text-field v-model="update.alias_id" label="" outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="alias_id">Install Date</label>
                        <v-text-field v-model="update.install_date" type="date" prepend-inner-icon="mdi-calendar"
                            outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0 pb-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Delivery Date</label>

                        <v-text-field v-model="update.delivery_date" label="" type="date" prepend-inner-icon="mdi-calendar"
                            variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Take Over Date<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.takeover_date" label="" type="date"
                            prepend-inner-icon="mdi-calendar-end" outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Category<a style="color: red;">*</a></label>
                        <v-select :items="categories" item-value="id" item-title="category_name" v-model="update.category_id"
                            label="" outlined variant="outlined"></v-select>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Serial Number<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.serial_number" label="" outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Model<a style="color: red;">*</a></label>
                        <v-select :items="models" item-value="id" item-title="terminal_model" v-model="update.model_id"
                            label="" outlined variant="outlined"></v-select>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Warranty Days <a style="color: red;">*</a></label>
                        <v-text-field v-model="update.warrenty" label="" outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Status<a style="color: red;">*</a></label>
                        <v-select :items="statuses" item-value="id" item-title="status" v-model="update.status_id" label=""
                             variant="outlined"></v-select>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Type <a style="color: red;">*</a></label>
                        <v-select :items="types" item-value="id" item-title="terminal_type" v-model="update.type_id" label=""
                            outlined variant="outlined"></v-select>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Android Version<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.android_version" label="" outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="6">
                        <label for="alias_id">SiteID <a style="color: red;">*</a></label>
                        <v-autocomplete :items="banklocations" item-value="id" item-title="siteID"
                            v-model="update.banklocation_id" clearable variant="outlined" placeholder="site ID of bank location"></v-autocomplete>
                    </v-col>
                </v-row>
                <v-row class="d-flex justify-space-between pa-2 mb-2">
                    <v-btn color="red">
                        <router-link :to="'/portal/allterminal'"  style="color:white;" class="text-decoration-none" >Back</router-link>
                    </v-btn>
                    <v-btn color="blue darken-1" outlinetext style="color:white; margin-left:6px;" @click="Update">Update</v-btn>
                </v-row>
            </v-container>
            <v-divider></v-divider>
            <v-card-title>Bank information</v-card-title>
            <v-container>
                <v-row>
                    <v-col class="py-0" cols="12" sm="6" md="">
                        <v-text-field variant="outlined" item-value="id" item-title="siteID"
                            label="Search bank information here" prepend-inner-icon="mdi-magnify"
                            @click="find"></v-text-field>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <!-- search bank location  -->
        <v-dialog v-model="dialog" width="70%">
            <v-card>
                <v-data-table :items-per-page="itemsPerPage" :headers="headers" :items="banklocations"
                    :search="search"></v-data-table>
                <v-btn @click="close" style="color: blue;">Close</v-btn>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
import Swal from "sweetalert2";
import axios from 'axios';
export default {
    data: () => ({
        types: [],
        dialog: false,
        categories: [],
        banklocations: [],
        models: [],
        statuses: [],
        update: {
            atm_id: '',
            serial_number: '',
            alias_id: '',
            install_date: '',
            delivery_date: '',
            takeover_date: '',
            android_version: '',
            model_id: '',
            type_id: '',
            banklocation_id: '',
            category_id: '',
            status_id: '',
            warrenty: '',
        },
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
            { title: "", key: "actions", class: " white--text" },
        ],

    }),
    created(){
        const id = this.$route.params.id;
        console.log("id",id);
        axios.get('http://localhost:8000/api/IMS/terminal/getid/'+id)
        .then((Response) => {
            this.update = Response.data[0];
            console.log("update",Response.data.atm_id);
        })
        .catch((error) => {
                console.log(error);
            });
    },
    watch: {
        dialog(val) {
            val || this.close();
        },
    },
    methods: {
        hide_success: function (event) {
            console.log('Hide')
            window.setInterval(() => {
                this.success = false;
                this.error = false;
            }, 5000)
        },
        find() {
            this.dialog = true;
        },
        close() {
            this.dialog = false;
        },
        Update(){
            axios.put("/api/IMS/terminal/update/"+this.update.id, this.update)
                .then((Response) => {
                    if (Response.status == 200) {
                        Swal.fire({
                            title: Response.data.message,
                            icon: "success",
                        });
                        console.log(Response.data);
                        setTimeout(() => {
                            this.$router.push({ name: "allterminal_page" });
                        }, 3000);
                    }
                })
                .catch((error) => {

                    console.log(error.response);
                    if(error.response.status==500){
                         Swal.fire({
                        title: "This serail number already exit",
                        icon: "warning",
                        position: "top",
                        showConfirmButton: false,
                        timer: 1500
                        });
                    }
                    else{
                        Swal.fire({
                        title: error.response.data.message,
                        icon: "warning",
                        position: "top",
                        showConfirmButton: false,
                        timer: 1500
                        });
                    }
                });
        }
    },
    mounted: function () {
        if (alert) {
            this.hide_success();
        }

        axios.get("/api/IMS/categorie/allcategory")
            .then((Response) => {
                this.categories = Response.data.data;
                // console.log("category", this.categories);
            })
            .catch((error) => {
                console.log(error);
            });
        axios.get("/api/IMS/terminaltype/allterminaltype")
            .then((Response) => {
                this.types = Response.data;
                // console.log("type", this.types);
            })
            .catch((error) => {
                console.log(error);
            });

        axios.get("/api/IMS/terminalmodel/getallmodel")
            .then((Response) => {
                this.models = Response.data;
                // console.log("model", this.models);
            })
            .catch((error) => {
                console.log(error);
            });

        axios.get("/api/IMS/status/all")
            .then((Response) => {
                this.statuses = Response.data;
                // console.log("status", this.statuses);
            })
            .catch((error) => {
                console.log(error);
            });
        axios.get("/api/IMS/banklocation/getall")
            .then((Response) => {
                this.banklocations = Response.data;
                // console.log("location", this.banklocations);
            })
            .catch((error) => {
                console.log(error);
            });
    }
}
</script>
<style scoped>

</style>
