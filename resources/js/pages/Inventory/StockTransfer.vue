<template>
    <v-container fluid grid-list-xl>
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <span class="text-h6 text-medium-emphasis ps-2">Stock Transfer</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container>
                    <v-form ref="form">
                        <v-row dense>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Date<a style="color: red;">*</a></label>
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
                                <label for="sparepart_id">Job Sheet<a style="color: red;">*</a></label>
                                <v-select
                                    v-model="editedItem.return_id"
                                    :items="spares"
                                    item-title="return_id"
                                    item-value="id"
                                    density="compact"
                                    :rules="[v => !!v || 'Job Sheet is required']"
                                    variant="outlined"
                                    @change="selectSpare"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="sparepart_id">Spare Part Name<a style="color: red;">*</a></label>
                                <v-text-field
                                    v-model="sparepart.sparepart_name"
                                    :rules="[v => !!v || 'Part Number is required']"
                                    variant="outlined"
                                    readonly
                                    density="compact"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Part Number</label>
                                <v-text-field
                                    v-model="sparepart.part_number"
                                    :rules="[v => !!v || 'Part Number is required']"
                                    variant="outlined"
                                    readonly
                                    density="compact"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="request_id">Serial Number</label>
                                <v-text-field
                                    v-model="sparepart.serial_part"
                                    variant="outlined"
                                    density="compact"
                                    readonly
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" class="pb-2 pr-6">
                                <label for="sparepart_id">Warehouse</label>
                                <v-text-field
                                v-model="sparepart.warehouse_name"
                                variant="outlined"
                                density="compact"
                            ></v-text-field>
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
     stock: [],
     stocks: {
         stock_qty: ''
     },
     serial: [],
     sparepart: {
         sparepart_name: '',
         terminal_model: '',
         part_number: '',
         serialized: ''
     },
     warehouses: [],
     conditions: [],
     spareparts: [],
     usages: [],
     s_part: [],
     user: {},
     quantities: Array.from({ length: 10 }, (v, k) => k + 1),

     editedIndex: -1,
     editedItemList: [],
     editedItem: {
       return_id: '',
       sparepart_id: '',
       ticket_no: '',
       spare_qty: 1,
       warehouse_id: '',
       return_date: new Date().toISOString().substr(0, 10),
       usage_id: '',
       status_id: '',
       defect_id: '',
       remark: '',
     },
  }),
 watch: {
     dialog (val) {
       val || this.close()
     },
     'editedItem.return_id': function(newVal) {
         this.selectSpare();
     }
 },
 created() {
    this.GetListRepaire();
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
    selectSpare() {
        // console.log('id', this.editedItem.return_id)
      const selectedSpare = this.spares.find(spare => spare.id === this.editedItem.return_id);
      if (selectedSpare) {
        this.sparepart.part_number = selectedSpare.part_number;
        this.sparepart.serial_part = selectedSpare.serial_part;
        this.sparepart.warehouse_name = selectedSpare.warehouse_name;
        this.sparepart.warehouse_id = selectedSpare.warehouse_id;
        this.sparepart.spare_qty = selectedSpare.spare_qty;
        this.sparepart.status_id= selectedSpare.status_id;
        this.sparepart.defect_id= selectedSpare.defect_id;
        this.sparepart.used_qty= selectedSpare.used_qty;
        this.sparepart.sparepart_id= selectedSpare.sparepart_id;
        this.sparepart.sparepart_name= selectedSpare.sparepart_name;
        this.sparepart.id= selectedSpare.id;
      }
    },
     GetListRepaire() {
        axios.get('/api/v3/IMS/return/list-repaire')
        .then(response => {
             this.spares = response.data.data; // Assign the fetched data to s_path
             console.log("Updated s_path:", this.spares);
        })
        .catch(error => {
            if (error.response.status === 422) {
                this.errors = error.response.data.errors;
            }
        })
    },
    save() {
        console.log('spare', this.sparepart)
        const data = {
                id:this.sparepart.id,
                sparepart_id: this.sparepart.sparepart_id,
                spare_qty: this.editedItem.spare_qty,
                serial_part: this.sparepart.serial_part,
                warehouse_id: this.sparepart.warehouse_id,
                return_date: this.editedItem.return_date,
                defect_id: this.sparepart.defect_id,
                remark: this.editedItem.remark,
                status_id: this.sparepart.status_id
            }
            console.log(data)
             axios.post('/api/v3/IMS/return/stock-transfer' , data)
             .then(res => {
                 console.log(res);
                     this.$swal({
                         title: "Success",
                         text: res.data.message,
                         icon: "success",
                     });
                setTimeout(()=> {
                    window.location.reload();
                },5000)
             })

             this.$refs.form.reset()
             .catch(err => {
                 console.dir(err)
                 this.$swal({
                     title: "Error",
                     text: err.response.data.message || 'An error occurred',
                     icon: "error"
                 });
         })
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
