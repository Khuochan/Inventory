<template>
    <v-main style="padding: 20px 20px 20px 20px; background-color: #f2f7ff">
        <v-row style="padding: 10px; height: 90%">
            <v-card class="rounded-0" width="100%">
                <v-row>
                    <v-col cols="3" sm="3" md="2">
                        <label for="alias_id">Type </label>
                        <v-select :items="types" item-value="id" item-title="terminal_type" v-model="filters.type_id"   clearable
                         @update:model-value="getbanklocation(filters)" outlined variant="outlined"></v-select>
                    </v-col>
                    <v-col cols="3" sm="3" md="2">
                        <label for="alias_id">Model</label>
                        <v-select :items="models" item-value="id" item-title="terminal_model" v-model="filters.model_id"   clearable
                        @update:model-value="getbanklocation(filters)" outlined variant="outlined"></v-select>
                    </v-col>

                    <v-col cols="3" sm="3" md="2">
                        <label for="alias_id">Bank Name </label>
                        <v-select :items="banks" item-value="id" item-title="customer_name"   clearable
                           v-model="filters.bank_id" @update:model-value="getbanklocation(filters)" variant="outlined">
                        </v-select>
                    </v-col>
                </v-row>
                <v-card class="d-flex align-center rouned-0" height="65px" style="padding: 15px" outlined>
                    <v-card-text style="font-size: 20px; text-align: start">
                        ALL TERMINALS INFORMATION
                    </v-card-text>

                </v-card>
                <v-card flat class="mt-2">
                    <template v-slot:text>
                        <div class="d-flex align-end">
                            <v-spacer></v-spacer>
                            <v-text-field clearable v-model="search" label="Search" prepend-inner-icon="mdi-magnify"
                                single-line variant="outlined" hide-details width="70px"></v-text-field>
                        </div>
                    </template>
                    <v-data-table :items-per-page="itemsPerPage" :headers="headers" :items="banklocations"
                        :search="search"  @event="getbanklocation">

                        <template v-slot:[`item.actions`]="{ item }">
                            <router-link :to="'/portal/viewdetailterminal/' + item.id">
                                <v-icon size="small" class="me-2" color="blue">mdi-eye
                                </v-icon>
                            </router-link>
                            <router-link :to="'/portal/updateterminal/' + item.id">
                                <v-icon size="small" class="me-2" color="orange">mdi-pencil
                                </v-icon>
                            </router-link>

                        </template>
                    </v-data-table>
                </v-card>
            </v-card>
        </v-row>
    </v-main>
</template>

<script scope>
import axios from "axios";
export default {
    data() {
        return {
            itemsPerPage: 10,
            search: "",
            headers: [
                {
                    align: "start",
                    key: "atm_id",
                    sortable: false,
                    title: "ATM ID",
                },
                {
                    key: "alias_id",
                    title: "Alias ID",
                },
                {
                    key: "serial_number",
                    title: "Serail Number",
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
                    key: "customer_name",
                    title: "Bank",
                },
                {
                    key: "siteID",
                    title: "Site ID",
                },
                {
                    key: "site_name",
                    title: "Site Name",
                },
                {
                    key: "address",
                    title: "address",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            banklocations: [],
            types: [],
            models: [],
            banks: [],
            filters: {
                type_id: "",
                model_id: "",
                bank_id: "",
            }
        };
    },
    created() {
        this.getbanklocation();
    },

    methods: {
        getbanklocation() {
            axios
                .post("/api/IMS/terminal/filter",this.filters)
                .then((Response) => {
                    this.banklocations = Response.data;
                    console.log("all", this.banklocations);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

    },
    mounted: function () {
        this.getbanklocation(this.params);

        axios.get("/api/IMS/terminaltype/allterminaltype")
            .then((Response) => {
                this.types = Response.data;
                console.log("type", this.types);
            })
            .catch((error) => {
                console.log(error);
            });

        axios.get("/api/IMS/terminalmodel/getallmodel")
            .then((Response) => {
                this.models = Response.data;
                console.log("model", this.models);
            })
            .catch((error) => {
                console.log(error);
            });
        axios.get("/api/IMS/customer/getallcustomer")
            .then((Response) => {
                this.banks = Response.data;
                console.log(this.banks);
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>
