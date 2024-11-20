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
            <div class="ma-2 pa-2">
                <v-dialog v-model="dialog" persistent width="700px">
                    <template v-slot:activator="{ props }">
                        <v-btn type="submit" color="green" class="ma-2 pa-2"  prepend-icon="mdi-plus-circle" v-bind="props">
                            Add Request
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title class="d-flex justify-space-between align-center">
                            <span class="text-h6 text-medium-emphasis ps-2">Add Spare Request</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container>
                                <v-row dense >
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Request No<a style="color: blue;">*</a></label>
                                        <v-text-field
                                            v-model="request"
                                            variant="outlined"
                                            hide-details="auto"
                                            density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Date Request<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="editedItem.request_date"
                                            :rules="[v => !!v || 'Date is required']"
                                            variant="outlined"
                                            type="date"
                                            density="compact"
                                            :value="new Date().toISOString().substr(0, 10)"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Ticket No<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="editedItem.ticket_no"
                                            :rules="[v => !!v || 'Ticket No is required']"
                                            variant="outlined"
                                            density="compact"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Terminal ID<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.terminal_id"
                                            :items="terminals"
                                            item-title="atm_id"
                                            item-value="id"
                                            :rules="[v => !!v || 'Terminal ID is required']"
                                            variant="outlined"
                                            density="compact"
                                             @change="checkWarranty"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Spare Part Name<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.sparepart_id"
                                            :items="s_part"
                                            item-title="sparepart_name"
                                            item-value="id"
                                            density="compact"
                                            :rules="[v => !!v || 'Spare Part Name is required']"
                                            variant="outlined"
                                            @change="selectSpare"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Part Number<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="sparepart.part_number"
                                            :rules="[v => !!v || 'Part Number is required']"
                                            variant="outlined"
                                            readonly
                                            density="compact"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="request_id">Quantity<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.spare_qty"
                                             :items="quantities"
                                            item-title="quantity"
                                            density="compact"
                                            :rules="[v => !!v || 'Quantity is required']"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6">
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
                                    <v-col class="text-right">
                                        <v-btn
                                        class="text-none ms-4 text-white"
                                        color="#3c519c"
                                        variant="flat"
                                        @click="addspare"
                                    >
                                        Add to List
                                    </v-btn>
                                    </v-col>
                                    <v-col cols="12" sm="12">
                                        <v-table density="compact" class="design rounded">
                                            <thead>
                                              <tr>
                                                <th class="text-left">
                                                  Part No
                                                </th>
                                                <th class="text-left">
                                                  Part Name
                                                </th>
                                                <th class="text-center">
                                                   Request Qty
                                                </th>
                                                <th class="text-center">
                                                    Delete
                                                </th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr v-for="(item, index) in editedItemList" :key="index">
                                                <td>{{ item.part_number }}</td>
                                                <td>{{ item.sparepart_name }}</td>
                                                <td class="text-center">{{ item.spare_qty}}</td>
                                                <td class="text-center">
                                                    <v-btn
                                                        @click="deleteItem(index)"
                                                        class="ma-2"
                                                        color="red"
                                                        icon="mdi-close-box"
                                                        variant="text"
                                                    ></v-btn>
                                                  </td>
                                              </tr>
                                            </tbody>
                                          </v-table>
                                    </v-col>
                                    <v-col cols="12" sm="12">
                                        <label for="request_id">Remark<a style="color: blue;">*</a></label>
                                        <v-text-field
                                            v-model="editedItem.remark"
                                            variant="outlined"
                                            density="compact"
                                        ></v-text-field>
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
                </v-dialog>
                <v-dialog v-model="dialog1" max-width="800px">
                    <v-card>
                            <v-card-title class="d-flex justify-space-between align-center">
                                <span class="text-h6 text-medium-emphasis ps-2">Spare Request</span>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-container>
                                    <v-row dense >
                                        <v-col cols="12" sm="6">
                                            <label for="request_id">Request No</label>
                                            <v-text-field
                                                v-model="editedItem.request_name"
                                                variant="outlined"
                                                hide-details="auto"
                                                density="compact"
                                                readonly
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <label for="request_id">Date Request</label>
                                            <v-text-field
                                                v-model="editedItem.request_date"
                                                variant="outlined"
                                                type="date"
                                                readonly
                                                density="compact"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <label for="request_id">Ticket No</label>
                                            <v-text-field
                                                v-model="editedItem.ticket_no"
                                                variant="outlined"
                                                density="compact"
                                                readonly
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <label for="request_id">Terminal ID</label>
                                            <v-select
                                                v-model="editedItem.terminal_id"
                                                :items="terminals"
                                                item-title="atm_id"
                                                item-value="id"
                                                variant="outlined"
                                                density="compact"
                                                readonly
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <label for="request_id">Engineer</label>
                                            <v-select
                                                v-model="editedItem.engineer_id"
                                                 :items="engineers"
                                                item-title="engineer_name"
                                                density="compact"
                                                item-value="id"
                                                readonly
                                                :rules="[v => !!v || 'Engineer is required']"
                                                variant="outlined"
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="12">
                                            <label for="request_id">Remark</label>
                                            <v-text-field
                                                v-model="editedItem.remark"
                                                variant="outlined"
                                                density="compact"
                                                readonly
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12">
                                            <v-table density="compact" class="design rounded">
                                                <thead>
                                                  <tr>
                                                    <th class="text-left">
                                                        Stock
                                                      </th>
                                                    <th class="text-left">
                                                      Part No
                                                    </th>
                                                    <th class="text-left">
                                                      Part Name
                                                    </th>
                                                    <th class="text-center">
                                                       Req. Qty
                                                    </th>
                                                    <th class="text-center">
                                                        Stock.Qty
                                                    </th>
                                                    <th class="text-center">
                                                        Send.Qty
                                                    </th>
                                                    <th class="text-center">
                                                        SNR
                                                    </th>
                                                    <th class="text-center">
                                                        Select SNR
                                                    </th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <tr v-for="(item, index) in editedItem.request_data" :key="index">
                                                    <td class="text-center" >
                                                        <v-btn
                                                            v-if="getSparepart(item.sparepart_id).serialized == false"
                                                            @click="viewStock(item)"
                                                            color="blue"
                                                            icon="mdi-eye"
                                                            variant="text"
                                                        ></v-btn>
                                                        <v-dialog v-model="dialog2" max-width="600px">
                                                            <template v-slot:default="{ isActive }">
                                                                <v-card>
                                                                    <v-card-title class="d-flex justify-space-between align-center">
                                                                        <span class="text-h6 text-medium-emphasis ps-2">View Stock</span>

                                                                        <v-btn
                                                                            icon="mdi-close"
                                                                            variant="text"
                                                                            @click="isActive.value = false"
                                                                        ></v-btn>
                                                                    </v-card-title>
                                                                    <v-divider></v-divider>
                                                                    <v-card-text>
                                                                        <v-table density="compact" class="design rounded">
                                                                            <thead>
                                                                              <tr>
                                                                                <th class="text-left">
                                                                                    Location
                                                                                  </th>
                                                                                <th class="text-center">
                                                                                  Stock in Hand
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    Condition
                                                                                </th>
                                                                                <th class="text-left">
                                                                                    Consume
                                                                                </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr v-for="(stockItem, stockIndex) in stock" :key="stockIndex">
                                                                                    <td>{{ stockItem.warehouse_name }}</td>
                                                                                    <td class="text-center">{{ stockItem.stock_qty }}</td>
                                                                                    <td class="text-center">{{ stockItem.condition_name }}</td>
                                                                                    <td>
                                                                                      <v-checkbox :value="stockItem.id" @change="updateStock(stockItem)"></v-checkbox>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </v-table>
                                                                    </v-card-text>
                                                                </v-card>
                                                            </template>
                                                        </v-dialog>
                                                    </td>
                                                    <td>{{ getSparepart(item.sparepart_id).part_number }}</td>
                                                    <td>{{ getSparepart(item.sparepart_id).sparepart_name }}</td>
                                                    <td class="text-center">{{ item.spare_qty}}</td>
                                                    <td class="text-center">{{ item.stock_qty }}</td>
                                                    <td class="text-center">{{ item.spare_qty}}</td>
                                                    <td class="text-center">{{item.serial_part}}</td>
                                                    <td class="text-center" v-if="getSparepart(item.sparepart_id).serialized == true">
                                                        <v-btn
                                                            @click="ViewSerial(item)"
                                                            class="ma-2"
                                                            color="blue"
                                                            icon="mdi-eye"
                                                            variant="text"
                                                        ></v-btn>
                                                        <v-dialog v-model="dialog3" max-width="600px">
                                                            <template v-slot:default="{ isActive }">
                                                                <v-card>
                                                                    <v-card-title class="d-flex justify-space-between align-center">
                                                                        <span class="text-h6 text-medium-emphasis ps-2">View Serial</span>

                                                                        <v-btn
                                                                            icon="mdi-close"
                                                                            variant="text"
                                                                            @click="isActive.value = false"
                                                                        ></v-btn>
                                                                    </v-card-title>
                                                                    <v-divider></v-divider>
                                                                    <v-card-text>
                                                                        <v-table density="compact" class="design rounded">
                                                                            <thead>
                                                                              <tr>
                                                                                <th class="text-left">
                                                                                    Part No
                                                                                  </th>
                                                                                <th class="text-center">
                                                                                  Part Name
                                                                                </th>
                                                                                <th class="text-center">
                                                                                   Serial
                                                                                  </th>
                                                                                <th class="text-center">
                                                                                    Condition
                                                                                </th>
                                                                                <th class="text-center">
                                                                                    Consume
                                                                                </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr v-for="(item, index) in serial" :key="index">
                                                                                    <td>{{ item.part_number }}</td>
                                                                                    <td>{{ item.sparepart_name }}</td>
                                                                                    <td class="text-center">{{ item.serial_part }}</td>
                                                                                    <td class="text-center">{{ item.condition_name }}</td>
                                                                                    <td class="text-center"><v-checkbox :value="item.id"  @change="updateSerial(item)"></v-checkbox></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </v-table>
                                                                    </v-card-text>
                                                                </v-card>
                                                            </template>
                                                        </v-dialog>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </v-table>
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
                                @click="close"
                            >
                                Close
                            </v-btn>
                            <span v-if="editedItem.status_name == 'Pending'">
                                <v-btn
                                    class="text-none ms-4 text-white"
                                    color="red"
                                    variant="flat"
                                    @click="RejectRequest"
                                >
                                    Reject
                                </v-btn>
                                <v-btn
                                    class="text-none ms-4 text-white"
                                    color="#3c519c"
                                    variant="flat"
                                    @click="ApproveRequest"
                                >
                                    Approve
                                </v-btn>
                            </span>

                            </v-card-actions>
                        </v-card>
                </v-dialog>
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
            :items="Request"
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
        machine: false,
        request: 'Auto Generate',
        expanded: [],
        newspare_part: '',
        loading: false,
        headers: [
            {
            title: 'Request No',
            align: 'start',
            sortable: false,
            key: 'request_name',
          },
          { title: 'Date', align: 'start', key: 'request_date'},
          { title: 'Qty', align: 'start', key: 'sum_spare_qty'},
          { title: 'Ticket No', align: 'start', key: 'ticket_no'},
          { title: 'Status', align: 'start', key: 'status_name'},
          { title: 'Remark', align: 'center', key: 'remark'},
          { title: 'Action',  key: 'actions', align: 'center',sortable: false },
        ],
        spares:[],
        statuses: [],
        terminals: [],
        datas: [],
        stock: [],
        stocks: {
            stock_qty: ''
        },
        user: {},
        serial: [],
        engineers: [],
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
            status: 'Pending',
            start_date: '',
            end_date: ''
        },
        editedIndex: -1,
        editedItemList: [],
        editedItem: {
          request_name:'',
          terminal_id: '',
          sparepart_id: '',
          request_data: [],
          spare_qty: '',
          ticket_no: '',
          request_date: new Date().toISOString().substr(0, 10),
          engineer_id: '',
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
     computed: {
        Request(){
            const filtered = this.datas.filter(data => {
                const status = !this.filters.status || data.status_name === this.filters.status;
                const itemDate = new Date(data.request_date);
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
        },
        'editedItem.terminal_id': function(newVal) {
            if (this.dialog) { // Check if the dialog is open
                this.checkWarranty();
            }
        }
    },
    created() {
        this.getRequest();
        this.Sparepart();
        // this.getSpare();
        this.getTerminal();
        this.getEngineer();
        this.getStatus();

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
        getStatus() {
            axios.get("/api/v3/IMS/sub-item/get-status")
                .then((Response) => {
                    this.statuses = Response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getSparepart(sparepart_id) {
            const selectedSpare = this.s_part.find(spare => spare.id === sparepart_id);
            return selectedSpare || { part_number: '', sparepart_name: '', serialized: '' };
        },
        checkWarranty() {
            if (this.editedItem.terminal_id) {
                // Find the selected terminal in the terminals array
                const selectedTerminal = this.terminals.find(
                (terminal) => terminal.id === this.editedItem.terminal_id
                );

                // Calculate the number of days since the terminal was installed
                const installDate = new Date(selectedTerminal.install_date);
                const currentDate = new Date();
                const daysSinceInstall = Math.floor(
                (currentDate - installDate) / (1000 * 60 * 60 * 24)
                );
                // Set the isOutOfWarranty flag based on the number of days since installation
                this.machine = daysSinceInstall > 365;
                if (this.machine) {
                    alert(`The selected terminal is out of warranty. It was installed more than 365 days ago.`);
                }
            } else {
                // Reset the isOutOfWarranty flag if no terminal is selected
                this.machine = false;
            }
        },
        getRequest() {
            axios.get('/api/v3/IMS/request/getall')
            .then(response => {
                this.datas = response.data.data;
                // console.log('list request', this.datas);
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
            // console.log('show spare', this.spareparts)
        });
        },
        Sparepart() {
           axios.get('/api/v3/IMS/sparepart/get-spare')
           .then(response => {
                this.s_part = response.data.data; // Assign the fetched data to s_path
                // console.log("Updated s_path:", this.s_path);
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
        getTerminal() {
            axios.post("/api/IMS/terminal/filter",this.filters)
                .then((Response) => {
                    this.terminals = Response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
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
        addspare() {
            this.displaySpare();

            this.spareparts.forEach(sparepart => {
                this.editedItemList.push({
                ...this.editedItem,
                part_number: sparepart.part_number,
                sparepart_name: sparepart.sparepart_name,
                sparepart_id: sparepart.sparepart_id,
                serialized: sparepart.serialized
                });
            });
            console.log('get sapre', this.editedItem)

            // Clear only stock_id and spare_qty in editedItem for a new entry
            this.editedItem.sparepart_id = [];
            this.editedItem.spare_qty = 0;
        },
        deleteItem(index) {
            this.editedItemList.splice(index, 1);
        },
        save () {
                const requestData = this.editedItemList.map(item => ({
                    terminal_id: item.terminal_id,
                    sparepart_id: item.sparepart_id,
                    spare_qty: item.spare_qty,
                    ticket_no: item.ticket_no,
                    request_date: item.request_date ? item.request_date : new Date().toISOString().substr(0, 10),
                    engineer_id: item.engineer_id,
                    remark: this.editedItem.remark,
                }));
                console.log(requestData);
                axios.post('/api/v3/IMS/request/request-multiple' , requestData, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
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
                this.getRequest();
                this.close();
       },
        openFileDialog() {
            this.dialog = true;
        },
        editItem(item) {
          this.editedIndex = this.datas.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialog1 = true

          console.log("spare", item);
        },
        close () {
        this.dialog = false
          this.dialog1 = false
          this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedItemList = [];
            this.editedIndex = -1
          })
        },
        viewStock(item) {
            this.dialog2 = true
            console.log("get stock", item);
            axios.get(`/api/v3/IMS/stock/one-stock/${item.sparepart_id}`)
            .then(response => {
                this.stock = response.data.data;
                console.log("get stock", this.stock);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        updateStock(stockItem) {
            const requestDataItem = this.editedItem.request_data.find(item => item.sparepart_id === stockItem.sparepart_id);
            if (requestDataItem) {
                requestDataItem.stock_qty = stockItem.stock_qty;
                requestDataItem.stock_id = stockItem.serial_data[0].stock_id;
                console.log("Updated request data stock item:", stockItem );
            } else {
                console.error("No matching request data item found for stockItem:", stockItem);
            }
            this.dialog2 = false;
        },
        ViewSerial(item) {
            this.dialog3 = true
            console.log("serial", item);
            axios.get(`/api/v3/IMS/stock/one-serial/${item.sparepart_id}`)
            .then(response => {
                this.serial = response.data.data;
                console.log("get serial", this.serial);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        updateSerial(Item){
            const requestDataItem = this.editedItem.request_data.find(item => item.sparepart_id === Item.sparepart_id);
            if (requestDataItem) {
                requestDataItem.serial_part = Item.serial_part;
                requestDataItem.stock_qty = Item.used_qty;
                requestDataItem.stock_id = Item.id;
                console.log("Updated request data serail item:", requestDataItem);
            } else {
                console.error("No matching request data item found for stockItem:", stockItem);
            }
            this.dialog3 = false;
        },
        ApproveRequest() {
            console.log(this.editedItem)
            const stockIds = this.editedItem.request_data.map(item => item.stock_id);

            const data = {
                request_name: this.editedItem.request_name,
                stock_ids: stockIds
            }
            axios.post('/api/v3/IMS/request/approve-request', data)
           .then(res =>{
               console.log("data", res.data);
                   this.$swal({
                       title: "Success",
                       text: res.data.message,
                       icon: "success",
                   });
            this.getRequest();
            this.close();
           })
           .catch(err => {
               this.$swal(
                   {
                       title: 'Oops',
                       text: err.response.data.message,
                       icon: 'error'
                   }
               )
           })
           this.close();
        },
        RejectRequest() {
            const data = {
                request_name: this.editedItem.request_name,
            }
            axios.post('/api/v3/IMS/request/reject-request', data)
           .then(res =>{
               console.log("data", res.data);
                   this.$swal({
                       title: "Success",
                       text: res.data.message,
                       icon: "success",
                   });
            this.getRequest();
            this.close();
           })
           .catch(err => {
               this.$swal(
                   {
                       title: 'Oops',
                       text: err.response.data.message,
                       icon: 'error'
                   }
               )
           })
           this.close();
        }

     }
   }
 </script>

<style>
    .design th{
        background-color: #3c519c!important;
        color: white!important;
    }
</style>




