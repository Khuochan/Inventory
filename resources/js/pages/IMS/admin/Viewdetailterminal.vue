<template>
    <v-container fluid grid-list-xl class="pt-0">
        <v-card>
            <v-card-title>View detail terminal information</v-card-title>
            <v-container>
                <v-row class="pt-2" variant="outlined">
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="atm_id">ATM ID<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.atm_id" variant="outlined" disabled></v-text-field>
                    </v-col>
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="alias_id">Alias ATM ID</label>
                        <v-text-field v-model="update.alias_id" disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="pb-0 py-0 " cols="12" sm="6" md="4">
                        <label for="alias_id">Install Date</label>
                        <v-text-field v-model="update.install_date" type="date" prepend-inner-icon="mdi-calendar"
                        disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0 pb-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Delivery Date</label>

                        <v-text-field v-model="update.delivery_date" disabled type="date" prepend-inner-icon="mdi-calendar"
                            variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Take Over Date<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.takeover_date" label="" type="date"
                            prepend-inner-icon="mdi-calendar-end" disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Category<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.category_name"
                        disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Serial Number<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.serial_number" disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Model<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.terminal_model"
                        disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Warranty Days <a style="color: red;">*</a></label>
                        <v-text-field v-model="update.warrenty" disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Status<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.status"
                             variant="outlined" disabled></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Type <a style="color: red;">*</a></label>
                        <v-text-field  v-model="update.terminal_type"
                            disabled outlined variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Android Version<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.android_version" disabled  variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Bank Name<a style="color: red;">*</a></label>
                        <v-text-field v-model="update.customer_name" disabled variant="outlined"></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">SiteID <a style="color: red;">*</a></label>
                        <v-text-field v-model="update.siteID" variant="outlined" disabled></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" sm="6" md="4">
                        <label for="alias_id">Site Name <a style="color: red;">*</a></label>
                        <v-text-field v-model="update.site_name" variant="outlined" disabled></v-text-field>
                    </v-col>
                    <v-col class="py-0" cols="12" >
                        <label for="alias_id">Site Name <a style="color: red;">*</a></label>
                        <v-text-field v-model="update.address" variant="outlined" disabled></v-text-field>
                    </v-col>
                </v-row>
                <v-row class="d-flex justify-space-between pa-2 mb-2">
                    <v-btn color="red">
                        <router-link :to="'/portal/allterminal'"  style="color:white;" class="text-decoration-none" >Back</router-link>
                    </v-btn>
                    <!-- <v-btn color="blue darken-1" outlinetext style="color:white; margin-left:6px;" @click="Update">Update</v-btn> -->
                </v-row>
            </v-container>
            <v-divider></v-divider>
        </v-card>
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
        },


    }),
    created(){
        const id = this.$route.params.id;
        console.log("id",id);
        axios.get('http://localhost:8000/api/IMS/terminal/getviewdetail/'+id)
        .then((Response) => {
            this.update = Response.data[0];
            console.log("update",Response.data);
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
    },
    mounted: function () {
        if (alert) {
            this.hide_success();
        }
    }
}
</script>
<style scoped>

</style>
