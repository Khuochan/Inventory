<template>
    <v-container fluid grid-list-xl>
        <div class="d-flex flex-row mb-6">
            <div class="ma-2 pa-2 d-flex me-auto" style="width:200px;">
                <v-select
                    label="Model"
                    v-model="filters.model"
                    :items="models"
                    clearable
                    variant="solo"
                    density="compact"
                    item-title="terminal_model"
                >
                </v-select>
            </div>
            <div class="ma-2 pa-2">
                <v-dialog v-model="dialog" persistent width="700px">
                    <template v-slot:activator="{ props }">
                        <v-btn type="submit" color="green" class="ma-2 pa-2"  prepend-icon="mdi-plus-circle" v-bind="props">
                            Add stock
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title class="d-flex justify-space-between align-center">
                            <span class="text-h6 text-medium-emphasis ps-2">{{ formTitle }}</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container>
                                <v-row dense>
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Spare Part Name<a style="color: red;">*</a></label>
                                        <v-select
                                            v-model="editedItem.sparepart_id"
                                             :items="spares"
                                                item-title="sparepart_name"
                                                item-value="id"
                                                :rules="[v => !!v || 'Spare Part Name is required']"
                                                variant="outlined"
                                                 density="compact"
                                                @change="selectSpare"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="model_id">Model<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="sparepart.terminal_model"
                                            :rules="[v => !!v || 'Model is required']"
                                            variant="outlined"
                                             density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Part Number<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="sparepart.part_number"
                                            :rules="[v => !!v || 'Part Number is required']"
                                            variant="outlined"
                                             density="compact"
                                            readonly
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Quantity<a style="color: red;">*</a></label>
                                        <v-text-field
                                            v-model="editedItem.quantity"
                                            density="compact"
                                            :rules="[v => !!v || 'Quantity is required', v => v > 0 || 'Quantity must be greater than 0']"
                                           variant="outlined"
                                            type="number"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- check serialized true and false -->
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Serial No<a style="color: red;">*</a></label>
                                        <v-combobox
                                            v-model="editedItem.serial_part"
                                            :items="[]"
                                             :rules="[v => !!v || 'Serial No is required']"
                                            variant="outlined"
                                            multiple
                                            density="compact"
                                            chips
                                            clearable
                                            :disabled="!sparepart.serialized"
                                        >

                                        </v-combobox>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <label for="sparepart_id">Condition<a style="color: blue;">*</a></label>
                                        <v-select
                                            v-model="editedItem.condition_id"
                                             :items="conditions"
                                            item-title="condition_name"
                                            item-value="id"
                                             density="compact"
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6">
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
                                    <v-col cols="12" sm="12">
                                        <label for="sparepart_id">Remark<a style="color: blue;">*</a></label>
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
            :items="Stock"
            :search="search"
            density="compact"
           >
            </v-data-table>
       </v-card>
    </v-container>
 </template>

 <script>
   export default {
     data: () => ({
        dialog: false,
        dialogDelete: false,
        expanded: [],
        newspare_part: '',
        loading: false,
        headers: [
            {
            title: 'Name',
            align: 'start',
            sortable: false,
            key: 'sparepart_name',
          },
          { title: 'Part No', align: 'start', key: 'part_number'},
          { title: 'Model', align: 'start', key: 'terminal_model'},
          { title: 'Qty', align: 'start', key: 'quantity'},
          { title: 'Usable', align: 'center', key: 'used_qty'},
          { title: 'Broken', align: 'start', key: 'broken_qty'},
          { title: 'Using', align: 'start', key: 'using_qty'},
          { title: 'Condition', align: 'left', key: 'condition_name'},
          { title: 'Warehouse', align: 'center', key: 'warehouse_name' },
          { title: 'Remark',  key: 'remark', align: 'center',sortable: false },
        ],
        stocks:[],
        models:[],
        spares:[],
        conditions: [],
        defects: [],
        warehouses: [],
        sparepart: {
            terminal_model: '',
            part_number: '',
            serialized: ''
        },
        search: '',
        filters: {
            model: null
        },
        editedIndex: -1,
        editedItem: {
          sparepart_id: '',
          warehouse_id: '',
          condition_id: 1,
          serial_part: null,
          quantity: null,
          remark: '',
        },
        defaultItem: {
            sparepart_id: '',
            warehouse_id: '',
            condition_id: '',
            serial_part: '',
            quantity: '',
            remark: ''
        },
     }),
     computed: {
        Stock(){
            const filtered = this.stocks.filter(data => {
                const model = !this.filters.model || data.terminal_model === this.filters.model;

                return model;
            })

            return filtered;
        },
        formTitle () {
          return this.editedIndex === -1 ? 'New Stock' : 'Edit Spare Part'
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
        this.getStock();
        this.getModel();
        this.getSpare();
        this.getCondition();
        this.getDefect();
        this.getWarehouse();
    },
     methods: {
        getStock() {
            axios.get('/api/v3/IMS/stock/getall')
            .then(response => {
                this.stocks = response.data.data;
                console.log("stocks", this.stocks);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            })
        },
        getModel() {
           axios.get('/api/IMS/terminalmodel/getallmodel')
           .then(response => {
               this.models = response.data;
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
       },
       selectSpare(){
            const selectedId = this.editedItem.sparepart_id;
            const selectedSpare = this.spares.find(spare => spare.id === selectedId);
            if (selectedSpare) {
                this.sparepart = {
                    terminal_model: selectedSpare.terminal_model,
                    part_number: selectedSpare.part_number,
                    serialized: selectedSpare.serialized
                };
                console.log(this.sparepart);
            } else {
                this.sparepart = {
                    terminal_model: '',
                    part_number: '',
                    serialized: ''
                };
            }
       },
       getSpare() {
            axios.get('/api/v3/IMS/sparepart/getall')
            .then(response => {
                this.spares = response.data.data;
                console.log("spare", this.spares);

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
        getCondition() {
            axios.get('/api/v3/IMS/sub-item/get-condition')
           .then(response => {
               this.conditions = response.data.data;
            //    console.log('condition', this.conditions)
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
        },
        getDefect() {
            axios.get('/api/v3/IMS/sub-item/get-defect')
           .then(response => {
               this.defects = response.data.data;
           })
           .catch(error => {
               if (error.response.status === 422) {
                   this.errors = error.response.data.errors;
               }
           })
        },
        getWarehouse() {
            axios
                .get("/api/IMS/warehouse/getallwarehouse")
                .then((Response) => {
                    this.warehouses = Response.data;
                    // console.log(this.warehouses);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        save () {
            const data = {
                condition_id: this.editedItem.condition_id,
                quantity: this.editedItem.quantity,
                warehouse_id: this.editedItem.warehouse_id,
                sparepart_id: this.editedItem.sparepart_id,
                remark: this.editedItem.remark
            };
            if (Array.isArray(this.editedItem.serial_part) && this.editedItem.serial_part.length > 0) {
                data.serial_parts = this.editedItem.serial_part.map(serial => ({ serial_part: serial }));
            }
           axios.post('/api/v3/IMS/stock/add-stock' ,data)
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
                       text: "Create Successfully!",
                       icon: "success",
                   });
               }
           })
           .catch(err => {
               console.dir(err)
               this.$swal(
                       {
                           title: 'Oops',
                           text: err.response.data.message,
                           icon: 'error'
                       }
                   )
           })
           this.getStock()
           this.close()
       },
        openFileDialog() {
            this.dialog = true;
        },
        editItem(item) {
          this.editedIndex = this.stocks.indexOf(item)
          this.editedItem = Object.assign({}, item)
          console.log(this.editedItem)
          this.dialog = true
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



