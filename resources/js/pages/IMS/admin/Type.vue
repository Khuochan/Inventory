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
                        TERMINAL TYPE INFORMATION
                    </v-card-text>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" class="mb-2" v-bind="props"
                                >New Type</v-btn
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
                                        <v-col>
                                            <v-text-field
                                                v-model="
                                                    editedItem.terminal_type
                                                "
                                                label="Type Name"
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
                        :items="types"
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
                    key: "terminal_type",
                    sortable: false,
                    title: "Terminal Type",
                },
                {
                    title: "Created At",
                    key: "created_at",
                    class: " white--text",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            types: [],
            editedIndex: -1,
            editedItem: {
                terminal_type: "",
            },
            defaultItem: {
                terminal_type: "",
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? "Add new terminal type"
                : "Update terminal type information";
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
                .get("/api/IMS/terminaltype/allterminaltype")
                .then((Response) => {
                    this.types = Response.data;
                    console.log(this.types);
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
            this.editedIndex = this.types.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        savebank() {
            if (this.editedIndex > -1) {
                Object.assign(this.types[this.editedIndex], this.editedItem);
                axios
                    .put("/api/IMS/terminaltype/updatetype/" + this.editedItem.id, this.editedItem)
                    .then((Response) => {
                        if (Response.status == 200) {
                            Swal.fire({
                                title: "Updated successfully",
                                icon: "success",
                            });
                            console.log(Response.data);
                            this.closedialog();
                        }
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: "Something when wrong",
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
                .post( "/api/IMS/terminaltype/addtype/",this.editedItem )
                .then((Response) => {
                    if (Response.status == 200) {
                        Swal.fire({
                            title: "Add new terminal type successfully",
                            icon: "success",
                        });
                        this.closedialog();
                        console.log(Response.data);
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        title: "Something when wrong!",
                        icon: "warning",
                        position: "top",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    console.log(error);
                });
            }
            this.getBank();
        },
    },
};
</script>
