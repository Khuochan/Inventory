<template>
    <div class="card">
        <div class="card-body">
            <div class="row mb-2" v-if="perPage || search">
                <div class="col">
                    <div id="tickets-table_length" class="dataTables_length" v-if="perPage">
                        <label class="d-inline-flex align-items-center">
                            Show&nbsp;
                            <b-form-select v-model="params.limit" size="sm" density="compact" variant="solo" :options="pageOptions"
                                           @change="$emit('event', params)"/>
                            &nbsp;entries
                        </label>
                    </div>
                </div>
                <!-- Search -->
                <div class="col">
                    <div id="tickets-table_filter" class="dataTables_filter text-right" v-if="search">
                        <label class="d-inline-flex align-items-center">
                            Search:
                            <b-form-input v-model="params.search" type="text" placeholder="Search..." density="compact" variant="solo"
                                          class="form-control form-control-sm ml-2"
                                          @input="$emit('event', params)"/>
                        </label>
                    </div>

                </div>
                <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive mb-0">
                <b-table
                    :items="data"
                    :fields="fields"
                    class="text-nowrap"
                    responsive="sm"
                    head-variant="light"
                    bordered>
                    <!-- selectable
                    @row-selected="onRowSelected" -->
                    <template v-for="(_, slotName) of tableSlots" v-slot:[slotName]="scope">
                        <slot :name="slotName" v-bind="scope"></slot>
                    </template>
                </b-table>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="dataTables_paginate paging_simple_numbers float-left">
                        <ul class="pagination pagination-rounded mb-0">
                            {{pageOptions.per_page}} of {{num_rows}} entries
                        </ul>
                    </div>
                </div>
                <div class="col-9">
                    <div class="dataTables_paginate paging_simple_numbers float-right">
                        <ul class="pagination pagination-rounded mb-0">
                            <b-pagination v-model="params.page" :total-rows="num_rows"
                                          :per-page="pageOptions.per_page"
                                          @input="$emit('event', params)">
                            </b-pagination>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DT",
    props: {
        perPage: {
            type: Boolean,
            default: true
        },
        search: {
            type: Boolean,
            default: true
        },
        params: {
            type: Object,
            default: () => {
                return {
                    limit: 0
                }
            }
        },
        pageOptions: {
            type: Array,
            default: () => [2,25, 50, 100, 200, 500]
        },
        fields: {
            type: Array
        },
        data: {
            type: Array
        },
        paginate: {
            type: Object
        }
    },
    // methods: {
    //     onRowSelected(items) {
    //         this.$emit('rowselected', items)
    //     },
    // }
}
</script>

<style scoped>
@media (max-width: 767.98px) {
    .dataTables_paginate ul {
         text-align: center !important;
         display: flex !important;
         margin: 0 !important;
    }
}

</style>
