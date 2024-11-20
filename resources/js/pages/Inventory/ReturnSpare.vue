<template>
    <v-container fluid grid-list-xl>
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <span class="text-h6 text-medium-emphasis ps-2">Add Spare Return</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container>
                    <v-form ref="form">
                        <v-row dense>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Date Return<a style="color: red;">*</a></label>
                                <v-text-field
                                    v-model="editedItem.return_date"
                                    :rules="[v => !!v || 'Date is required']"
                                    variant="outlined"
                                    type="date"
                                    density="compact"
                                    :value="new Date().toISOString().substr(0, 10)"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="return_id">Request ID<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="id_request"
                                    :items="request"
                                    item-title="request_name"
                                    item-value="id"
                                    density="compact"
                                    :rules="[v => !!v || 'Request ID is required']"
                                    variant="outlined"
                                    @change="selectRequest"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="sparepart_id">Spare Part Name<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="editedItem.request_id"
                                    :items="list_request"
                                    item-title="sparepart_name"
                                    item-value="request_id"
                                    density="compact"
                                    :rules="[v => !!v || 'Spare Part Name is required']"
                                    variant="outlined"
                                     @change="selectSparepart"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Part Number<a style="color: red;">*</a></label>
                                <v-text-field
                                    v-model="data_request.part_number"
                                    :rules="[v => !!v || 'Part Number is required']"
                                    variant="outlined"
                                    readonly
                                    density="compact"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Serial Number<a style="color: blue;">*</a></label>
                                <v-text-field
                                    v-model="editedItem.serial_part"
                                    :rules="[v => !!v || 'Serail Number is required']"
                                    variant="outlined"
                                    density="compact"
                                     :disabled="!data_request.serialized"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="sparepart_id">Warehouse<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="editedItem.warehouse_id"
                                    :items="warehouses"
                                    :rules="[v => !!v || 'Warehouse No is required']"
                                    item-title="warehouse_name"
                                    item-value="id"
                                    variant="outlined"
                                     density="compact"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Usage<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="editedItem.usage_id"
                                     :items="usages"
                                    item-title="usage_name"
                                    density="compact"
                                    item-value="id"
                                    :rules="[v => !!v || 'Usage is required']"
                                    variant="outlined"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="sparepart_id">Defect Type<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="editedItem.defect_id"
                                    :items="defects"
                                    item-title="type_name"
                                    item-value="id"
                                     density="compact"
                                    variant="outlined"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="12" class="pb-2 pr-6">
                                <label for="request_id">Remark<a style="color: blue;">*</a></label>
                                <v-textarea
                                    v-model="editedItem.remark"
                                    variant="outlined"
                                    density="compact"
                                    rows="2"
                                ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                class="text-none ms-4 text-black"
                color="#ABB3CD"
                variant="flat"
                @click="reset"
            >
                Clear
            </v-btn>
            <v-btn
                class="text-none ms-4 text-white"
                color="#32BF2F"
                variant="flat"
                @click="AddJobSheet"
            >
                Create Job Sheet
            </v-btn>
            <v-btn
                class="text-none ms-4 text-white"
                color="#3c519c"
                variant="flat"
                @click="save"
            >
                Save
            </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>
<script>
export default {
  data: () => ({
     loading: false,
     spares:[],
     defects: [],
     stock: [],
     stocks: {
         stock_qty: ''
     },
     serial: [],
     data_request: {
        request_id:'',
        sparepart_name: '',
        part_number: '',
        serialized: '',
        sparepart_id:'',
    },
     warehouses: [],
     spareparts: [],
     usages: [],
     list_request: [],
     request: [],
     id_request: null,
     user: {},
     quantities: Array.from({ length: 10 }, (v, k) => k + 1),

     editedIndex: -1,
     editedItemList: [],
     editedItem: {
       request_id:'',
       sparepart_id:'',
       spare_qty: 1,
       warehouse_id: '',
       return_date: new Date().toISOString().substr(0, 10),
       usage_id: '',
       defect_id: '',
       remark: '',
     },
     defaultItem: {
         terminal_id: '',
         sparepart_id: '',
         spare_qty: 0,
         ticket_no: '',
         request_data: [],
         request_date: '',
         engineer_id: '',
         remark: '',
     },
  }),
 watch: {
     dialog (val) {
       val || this.close()
     },
     'id_request': function(newVal) {
         this.selectRequest();
     },
     'editedItem.request_id': function(newVal) {
         this.selectSparepart();
     },
 },
 created() {
    this.getRequest();
    //  this.Sparepart();
     this.getDefect();
     this.getWarehouse();
     this.getUsage();
     const token = localStorage.getItem("token");
            const auth = {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            };
            axios.get('/api/user', auth)
                .then(res => {
                    this.user = res.data
                    // console.log('user', this.user)
                });
 },
  methods: {
     getRequest() {
            axios.get('/api/v3/IMS/request/get-request')
            .then(response => {
                this.request = response.data.data;
                // console.log('list request', this.request);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
    },
    selectRequest() {
        if (this.id_request) {
            this.Request(this.id_request);
        } else {
            this.list_request = [];
            this.data_request.sparepart_name = null;
            this.data_request.part_number = '';
        }
    },
    selectSparepart() {
        const selectedId = this.editedItem.request_id;
        const selectedSpare = this.list_request.find(sp => sp.request_id === selectedId);
        if (selectedSpare) {
            this.data_request.part_number = selectedSpare.part_number;
            this.data_request.sparepart_id = selectedSpare.sparepart_id;
            this.data_request.serialized = selectedSpare. serialized;
            console.log("Selected Sparepart:", this.data_request);
        } else {
            this.data_request.part_number = '';
        }
    },
    Request(requestId) {
        axios.get(`/api/v3/IMS/request/one-request/${requestId}`)
        .then(response => {
             this.list_request = response.data.data; // Assign the fetched data to s_path
             console.log("Updated s_path:", this.list_request);
        })
        .catch(error => {
            if (error.response.status === 422) {
                this.errors = error.response.data.errors;
            }
        })
    },
     getDefect() {
         axios.get("/api/v3/IMS/sub-item/get-defect")
             .then((Response) => {
                 this.defects = Response.data.data;
             })
             .catch((error) => {
                 console.log(error);
             });
     },
    getWarehouse() {
            axios.get("/api/IMS/warehouse/getallwarehouse")
                .then((Response) => {
                    this.warehouses = Response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
    },
    getUsage() {
        axios.get('/api/v3/IMS/sub-item/get-usage')
           .then(response => {
               this.usages = response.data.data;
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
    },
     addspare() {
         this.displaySpare();

         this.spareparts.forEach(sparepart => {
             this.editedItemList.push({
                ...this.editedItem,
                part_number: sparepart.part_number,
                sparepart_name: sparepart.sparepart_name,
                sparepart_id: sparepart.sparepart_id,
                serialized: sparepart.serialized,
             });
         });
         console.log('get spare', this.editedItem)

         // Clear only stock_id and spare_qty in editedItem for a new entry
         this.editedItem.sparepart_id = [];
         this.editedItem.serial_part = '';
         this.editedItem.spare_qty = 0;
     },
     deleteItem(index) {
         this.editedItemList.splice(index, 1);
     },
     AddJobSheet () {

            const data = {
                user_id: this.user.id,
                request_id: this.editedItem.request_id,
                sparepart_id: this.data_request.sparepart_id,
                spare_qty: this.editedItem.spare_qty,
                serial_part: this.editedItem.serial_part,
                warehouse_id: this.editedItem.warehouse_id,
                return_date: this.editedItem.return_date,
                usage_id: this.editedItem.usage_id,
                condition_id: this.editedItem.condition_id,
                defect_id: this.editedItem.defect_id,
                usage_id:this.editedItem.usage_id,
                remark: this.editedItem.remark,
            }
            console.log('get data', data)
             axios.post('/api/v3/IMS/return/add-job' , data)
             .then(res => {
                 console.log(res);
                 if (res.data.data == null) {
                     this.$swal({
                         title: "Success",
                         text: res.data.message,
                         icon: "error",
                     });
                     setTimeout(()=> {
                        window.location.reload();
                    },1000)
                 }else {

                     this.$swal({
                         title: "Success",
                         text: res.data.message,
                         icon: "success",
                     });
                     setTimeout(()=> {
                        window.location.reload();
                    },1000)
                 }

             })
             .catch(err => {
                 console.dir(err)
                 this.$swal({
                     title: "Error",
                     text: err.response.data.message || 'An error occurred',
                     icon: "error"
                 });
             })
             this.editedItem = Object.assign({}, this.defaultItem)
            //  this.close();
    },
    save(){
        const data = {
                request_id: this.editedItem.request_id,
                sparepart_id: this.data_request.sparepart_id,
                spare_qty: this.editedItem.spare_qty,
                serial_part: this.editedItem.serial_part,
                warehouse_id: this.editedItem.warehouse_id,
                return_date: this.editedItem.return_date,
                usage_id: this.editedItem.usage_id,
                defect_id: this.editedItem.defect_id,
                remark: this.editedItem.remark,
            }
            console.log(data)
             axios.post('/api/v3/IMS/return/return-spare' , data)
             .then(res => {
                 console.log(res);
                 if (res.data.data == null) {
                     this.$swal({
                         title: "Oops",
                         text: res.data.message,
                         icon: "error",
                     });
                 }else {

                     this.$swal({
                         title: "Success",
                         text: res.data.message,
                         icon: "success",
                     });
                     setTimeout(()=> {
                        window.location.reload();
                    },1000)
                 }

             })
             .catch(err => {
                 console.dir(err)
                 this.$swal({
                     title: "Error",
                     text: err.response.data.message || 'An error occurred',
                     icon: "error"
                 });
             })
             this.editedItem = Object.assign({}, this.defaultItem)
    },
    reset () {
        this.$refs.form.reset()
    },


  }
}
</script>

<style>
 .design th{
     background-color: #3c519c!important;
     color: white!important;
 }
</style>
