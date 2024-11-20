<template>
        <v-card class="mx-auto">
            <v-card-title class="mb-2">
                <v-row align="center" no-gutters>
                    <v-col cols="6">
                        {{ Name }}
                        <v-spacer></v-spacer>
                    </v-col>
                    <v-col cols="6" class="text-right"  v-if="search">
                        <v-text-field
                            v-model="params.search"
                            density="compact"
                            variant="outlined"
                            label="Search"
                            hide-details
                            append-icon="mdi-magnify"
                            @input="$emit('event', params)"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="items"
                v-model:items-per-page="itemsPerPage"
                :items-length="totalItems"
                :loading="loading"
                class="elevation-1"
            ></v-data-table>
        </v-card>
</template>

<script>
export default {
    // name: Stable,
    props: {
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
        headers: {
            type: Array
        },
        items: {
            type: Array
        },
        Name: {
            type: Object
        }
    },
    data () {
      return {
        itemsPerPage: 5,
        serverItems: [],
        loading: true,
        totalItems: 0,
      }
    },
    beforeMount() {
        // this.loadItems({ page: 1, itemsPerPage: 5, sortBy: [] })
    },
    methods: {
        // loadItems ({ page, itemsPerPage, sortBy }) {
        //     this.loading = true
        //     FakeAPI.fetch(page, itemsPerPage, sortBy).then(({ items, total }) => {
        //     this.serverItems = items
        //     this.totalItems = total
        //     this.loading = false
        //     })
        // },
    }
}
</script>

<style>
/* ::v-deep .v-data-table-header {
background-color: #2197BC;


}
::v-deep .sub-table .v-data-table-header { background-color: transparent;}
v-data-table >>> div > table {
border-spacing: 0 0.5rem;

} */
</style>
