<template>
    <v-container fluid grid-list-xl>
        <div class="d-flex flex-row mb-6">
            <div class="ma-2 pa-2 d-flex" style="width:200px;">
                <v-select
                    label="Status"
                    v-model="filters.status"
                    :items="statuses"
                    clearable
                    variant="solo"
                    density="compact"
                    item-title="status_name"
                >
                </v-select>
            </div>
            <div class="ma-2 pa-2 d-flex" style="width:200px;">
                <v-text-field v-model="filters.start_date" class="pr-1" label="From Date"
                    variant="solo" density="compact" color="blue" autocomplete="false"
                    type="date" />
            </div>
            <div class="ma-2 pa-2 d-flex me-auto" style="width:200px;">
                <v-text-field v-model="filters.end_date" class="pr-1" label="To Date"
                    variant="solo" density="compact" color="blue"
                    autocomplete="false" type="date" />
            </div>
        </div>
       <v-card class="rounded-0 mx-auto" >
        <v-card-title class="d-flex align-center pe-2">
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              density="compact"
              label="Search"
              prepend-inner-icon="mdi-magnify"
              variant="solo-filled"
              flat
              hide-details
              single-line
            ></v-text-field>
          </v-card-title>
          <v-divider></v-divider>
           <v-data-table
            :headers="headers"
            class="elevation-1 design"
            :items="Job"
            :search="search"
            density="compact"
           >
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon
                size="small"
                class="me-2"
                color="blue"
                @click="editItem(item)"
                >
                mdi-eye
                </v-icon>
                <v-dialog v-model="dialog" persistent width="700px">
                    <v-card>
                        <v-card-title class="d-flex justify-space-between align-center">
                            <span class="text-h6 text-medium-emphasis ps-2">View Job Sheet</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="pa-2">
                            <v-container>
                                <v-row dense >
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Job Sheet No</label>
                                        <v-text-field
                                            v-model="editedItem.return_id"
                                            variant="outlined"
                                            hide-details="auto"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Date</label>
                                        <v-text-field
                                            v-model="editedItem.return_date"
                                            variant="outlined"
                                            type="date"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Created By</label>
                                        <v-text-field
                                            v-model="editedItem.first_name"
                                            variant="outlined"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Ticket No</label>
                                        <v-text-field
                                            v-model="editedItem.ticket_no"
                                            variant="outlined"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Spare Part Name</label>
                                        <v-text-field
                                            v-model="editedItem.sparepart_name"
                                            variant="outlined"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Serial No</label>
                                        <v-text-field
                                            v-model="editedItem.serial_part"
                                            variant="outlined"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" class="pb-2 pr-6">
                                        <label for="request_id">Warehouse</label>
                                        <v-text-field
                                        v-model="editedItem.warehouse_name"
                                        variant="outlined"
                                        density="compact"
                                        readonly
                                    ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" class="pb-2 pr-6">
                                        <label for="request_id">Remark</label>
                                        <v-textarea
                                            v-model="editedItem.remark"
                                            variant="outlined"
                                            rows="1"
                                            density="compact"
                                            readonly
                                        ></v-textarea>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-title class="d-flex justify-space-between align-center">
                            <span class="text-h6 text-medium-emphasis ps-2">Closure</span>
                        </v-card-title>
                        <v-divider></v-divider>
                         <v-card-text class="pa-2">
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="4" class="pt-2 pr-6">
                                        <label for="request_id">Activity Date<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="editedItem.date_repaire"
                                            :rules="[v => !!v || 'Date is required']"
                                            variant="outlined"
                                            type="date"
                                            density="compact"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Engineer<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.engineer_id"
                                             :items="engineers"
                                            item-title="engineer_name"
                                            density="compact"
                                            item-value="id"
                                            :rules="[v => !!v || 'Engineer is required']"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="4" class="pb-2 pr-6">
                                        <label for="request_id">Status<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.status_id"
                                             :items="repaires"
                                            item-title="status_name"
                                            density="compact"
                                            item-value="id"
                                            :rules="[v => !!v || 'Status is required']"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="12">
                                        <label for="request_id">Comment<a style="color: blue;">*</a></label>
                                        <v-textarea
                                            v-model="editedItem.follow_up"
                                            variant="outlined"
                                            density="compact"
                                            rows="1"
                                        ></v-textarea>
                                    </v-col>
                                </v-row>
                            </v-container>
                         </v-card-text>
                         <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                class="text-none ms-4 text-black"
                                color="#ABB3CD"
                                variant="flat"
                                @click="dialog = false"
                            >
                                Close
                            </v-btn>
                            <span v-if="editedItem.status_name == 'Open'">
                                <v-btn
                                class="text-none ms-4 text-white"
                                color="#3c519c"
                                variant="flat"
                                @click="save"
                            >
                                Save
                            </v-btn>
                            </span>
                            </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>
            </v-data-table>
       </v-card>
    </v-container>
 </template>

 <script>
   export default {
     data: () => ({
        dialog: false,
        dialog1: false,
        dialog2: false,
        dialog3: false,
        expanded: [],
        loading: false,
        headers: [
            {
            title: 'Job Sheet No',
            align: 'start',
            sortable: false,
            key: 'return_id',
          },
          { title: 'Date', align: 'start', key: 'return_date'},
          { title: 'Qty', align: 'start', key: 'spare_qty'},
          { title: 'Serial', align: 'start', key: 'serial_part'},
          { title: 'Spare part', align: 'start', key: 'sparepart_name'},
          { title: 'Status', align: 'start', key: 'status_name'},
          { title: 'Remark', align: 'center', key: 'remark'},
          { title: 'Action',  key: 'actions', align: 'center',sortable: false },
        ],
        spares:[],
        statuses: [],
        datas: [],
        stock: [],
        stocks: {
            stock_qty: ''
        },
        serial: [],
        engineers: [],
        repaires:[],
        sparepart: {
            sparepart_name: '',
            terminal_model: '',
            part_number: '',
            serialized: ''
        },
        spareparts: [],
        s_part: [],
        quantities: Array.from({ length: 10 }, (v, k) => k + 1),
        search: '',
        filters: {
            status: 'Open',
            start_date: '',
            end_date: ''
        },
        editedIndex: -1,
        editedItemList: [],
        editedItem: {
            sparepart_id: '',
            spare_qty: '',
            warehouse_id: '',
            return_date: '',
            usage_id: '',
            condition_id: '',
            defect_id: '',
            remark: '',
        },
        defaultItem: {
            sparepart_id: '',
            spare_qty: '',
            warehouse_id: '',
            return_date: '',
            usage_id: '',
            condition_id: '',
            defect_id: '',
            remark: '',
        },
     }),
     computed: {
        Job(){
            const filtered = this.datas.filter(data => {
                const status = !this.filters.status || data.status_name === this.filters.status;
                const itemDate = new Date(data.return_date);
                const dateRange = (
                    (!this.filters.start_date || itemDate >= new Date(this.filters.start_date)) &&
                    (!this.filters.end_date || itemDate <= new Date(this.filters.end_date))
                );

                return status && dateRange;
            });

            return filtered;
        },
    },
    watch: {
        dialog (val) {
          val || this.close()
        },
        'editedItem.sparepart_id': function(newVal) {
            this.selectSpare();
        }
    },
    created() {
        this.getJob();
        this.Sparepart();
        this.getStatusRepaire();
        this.getEngineer();
        this.getStatus();

    },
     methods: {
        getStatus() {
            axios.get("/api/v3/IMS/sub-item/get-status-job")
                .then((Response) => {
                    this.statuses = Response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getStatusRepaire() {
            axios.get("/api/v3/IMS/sub-item/get-status-repaire")
                .then((Response) => {
                    this.repaires = Response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getSparepart(sparepart_id) {
            const selectedSpare = this.s_part.find(spare => spare.id === sparepart_id);
            return selectedSpare || { part_number: '', sparepart_name: '', serialized: '' };
        },
        getJob() {
            axios.get('/api/v3/IMS/return/list-job')
            .then(response => {
                this.datas = response.data.data;
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        selectSpare(){
            const selectedId = this.editedItem.sparepart_id;
            const selectedSpare = this.s_part.find(spare => spare.id === selectedId);
            if (selectedSpare) {
                this.sparepart = {
                    sparepart_name: selectedSpare.sparepart_name,
                    part_number: selectedSpare.part_number,
                    serialized: selectedSpare.serialized
                };
                // console.log(this.sparepart);
            } else {
                this.sparepart = {
                    sparepart_name: '',
                    part_number: '',
                    serialized: ''
                };
            }
       },
        displaySpare(){ // work normal
            this.spareparts = [];

            let stockIds = Array.isArray(this.editedItem.sparepart_id) ? this.editedItem.sparepart_id : [this.editedItem.sparepart_id];
            stockIds.forEach(stockId => {
            const selectedSpare = this.s_part.find(spare => spare.id === stockId);
            // console.log("this spare", selectedSpare)
            if (selectedSpare) {
                this.spareparts.push({
                    sparepart_id: selectedSpare.id,
                    part_number: selectedSpare.part_number,
                    sparepart_name: selectedSpare.sparepart_name,
                    serialized: selectedSpare.serialized
                });
            } else {
                this.spareparts.push({ part_number: '', sparepart_name: '', sparepart_id: '' });
            }
        });
        },
        Sparepart() {
           axios.get('/api/v3/IMS/sparepart/get-spare')
           .then(response => {
                this.s_part = response.data.data; // Assign the fetched data to s_path
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
        removeItem(index) {
            this.editedItem.serial_part.splice(index, 1);
        },
        getEngineer() {
            axios.get("/api/IMS/engineer/all")
                .then((Response) => {
                    this.engineers = Response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        save () {
            const data = {
                return_id:this.editedItem.id,
                date_repaire: this.editedItem.date_repaire,
                engineer_id:this.editedItem.engineer_id,
                status_id:this.editedItem.status_id,
                follow_up:this.editedItem.follow_up
            }
            console.log('this data', data)
                axios.post('/api/v3/IMS/return/close-job' , data)
                .then(res => {
                    console.log(res);
                        this.$swal({
                            title: "Success",
                            text: res.data.message,
                            icon: "success",
                        });
                })
                .catch(err => {
                    console.dir(err)
                    this.$swal({
                        title: "Error",
                        text: err.response.data.message || 'An error occurred',
                        icon: "error"
                    });
                })
                this.getJob();
                this.close();
       },
        openFileDialog() {
            this.dialog = true;
        },
        editItem(item) {
          this.editedIndex = this.datas.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialog = true

          console.log("spare", item);
        },
        close () {
            this.dialog = false
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
          })
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

