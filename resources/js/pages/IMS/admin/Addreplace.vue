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
                        REPLACEMENT INFORMATION
                    </v-card-text>
                    <v-dialog v-model="dialog" max-width="700px">
                        <template v-slot:activator="{ props }">
                            <v-btn color="primary" class="mb-2" v-bind="props"
                                >Add New</v-btn
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
                                          <label for="atm_id">Terminal ID<a style="color: red;">*</a></label>
                                            <v-select
                                                :items="terminals"
                                                item-value="id"
                                                item-title="atm_id"
                                                v-model="editedItem.terminal_id"
                                                label=""
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                          <label for="atm_id">Sparepart Name<a style="color: red;">*</a></label>
                                            <v-select
                                                :items="spareparts"
                                                item-value="id"
                                                item-title="sparepart_name"
                                                v-model="editedItem.sparepart_id"
                                                variant="outlined"
                                            >
                                            </v-select>
                                        </v-col>
                                      
                                        <v-col cols="12" md="6" sm="12">
                                          <label for="atm_id">Quantity<a style="color: red;">*</a></label>
                                            <v-text-field
                                                variant="outlined"
                                                v-model="editedItem.quantity"
                                                label=""
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                          <label for="atm_id">Engineer Name<a style="color: red;">*</a></label>
                                            <v-select
                                                :items="engineers"
                                                variant="outlined"
                                                item-value="id"
                                                item-title="engineer_name"
                                                v-model="editedItem.engineer_id"
                                                
                                            >
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" md="6" sm="12">
                                          <label for="atm_id">Replace Date<a style="color: red;">*</a></label>
                                          <v-text-field v-model="editedItem.replace_date" label="" type="date" prepend-inner-icon="mdi-calendar"
                                                variant="outlined"></v-text-field>
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
                        <template v-slot:[`item.replace_date`]="{ item}">
                            {{ formatDate(item.replace_date) }}
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
                    key: "atm_id",
                    title: "ATM ID",
                    sortable: false,
                },
                {
                   
                    key: "sparepart_name",
                    title: "Sparepart Name",
                },
              
                {
                    key: "quantity",
                    title: "Quantity",
                },
                {
                    key: "replace_date",
                    title: "Replace Date",
                },
                {
                    key: "engineer_name",
                    title: "Engineer",
                },
                { title: "Actions", key: "actions", class: " white--text" },
            ],
            banklocations:[],
            terminals: [{title:'atm_id'}],
            engineers:[
                {title: 'engineer_name'}
            ],
  
            spareparts:[
                {title: 'sparepart_name'}
            ],
            editedIndex: -1,
            editedItem: {
                terminal_id: "",
                sparepart_id:"",
                quantity:"",
                replace_date:"",
                engineer_id:"",
         
            },
            defaultItem: {
                terminal_id: "",
                sparepart_id:"",
                quantity:"",
                replace_date:"",
                engineer_id:"",
            },
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1
                ? "Add new replace sparepart"
                : "Update  replacement information";
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

        formatDate(value) {
            return moment(value).format("DD-MM-YYYY");
        },
        getbanklocation() {
            axios
                .get("/api/IMS/replace/getdata")
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
            axios.put("/api/IMS/replace/update/" +this.editedItem.id,this.editedItem)
                .then((Response) => {
                    if (Response.status == 200) {
                        Swal.fire({
                            title: "Update information successfully",
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
            axios.post("/api/IMS/replace/add",this.editedItem)
                .then((Response) => {
                     
                            Swal.fire({
                                title: "Add new replace successful",
                                icon: "success",
                            });
                            this.closedialog();
                            console.log(Response.data);
                    })
                    .catch((error) => {
                            Swal.fire({
                            title: "Something when wrong",
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
    
        this.engineers = axios.get("/api/IMS/engineer/all")
                .then((Response) => {
                    this.engineers = Response.data;
                    console.log(this.engineers);
                })
                .catch((error) => {
                    console.log(error);
                });

        this.spareparts= axios.get("/api/IMS/spareparts/getdata")
              .then((Response) => {
                  this.spareparts = Response.data;
                  console.log(this.spareparts);
              })
              .catch((error) => {
                  console.log(error);
              });
        this.terminals=axios.get("/api/IMS/terminal/get")
                .then((Response) => {
                    this.terminals = Response.data;
                    console.log("all",this.terminals);
                })
                .catch((error) => {
                    console.log(error);
                });
    },
  };
  </script>
  