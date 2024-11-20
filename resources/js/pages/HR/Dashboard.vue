<template>
    <!-- <v-container> -->
    <v-container fluid grid-list-xl>
        <v-row class="py-0">
            <v-col cols="12" sm="6" md="3">
                <!-- <v-sheet class="ma-6 rounded-lg"> -->
                    <v-card class="max-auto widget-card ma-6" max-width="368" color="white">
                        <v-card-text class="py-2">
                            <v-row no-gutters align="center">
                                <v-col cols="6">
                                    <v-card  color="blue" size="60" variant="tonal" class="text-center mt-2" style="width: 90px; height: 90px;">
                                        <v-icon size="70" color="blue" class="mt-3">mdi-account-multiple</v-icon>
                                    </v-card>
                                </v-col>
                                <v-col class="text-h2 text-right text-grey-darken-3" cols="6" style="width: 140px;">{{ em.User }}</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text class="text-right font-weight-bold text-grey-darken-1">Total Employee</v-card-text>
                    </v-card>
                <!-- </v-sheet> -->
            </v-col>
            <v-col cols="12" sm="6"  md="3">
                <!-- <v-sheet class="ma-6 rounded-lg"> -->
                    <v-card class="max-auto widget-card ma-6" max-width="368" color="white">
                        <v-card-text class="py-2">
                            <v-row no-gutters align="center">
                                <v-col cols="6">
                                    <v-card  color="indigo" size="60" variant="tonal" class="text-center mt-2" style="width: 90px; height: 90px;">
                                        <v-icon size="70" color="indigo" class="mt-3">mdi-office-building</v-icon>
                                    </v-card>
                                </v-col>
                                <v-col class="text-h2 text-right text-grey-darken-3" cols="6" style="width: 140px;">{{ em.Dept }}</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text class="text-right font-weight-bold text-grey-darken-1">Total Department</v-card-text>
                    </v-card>
                <!-- </v-sheet> -->
            </v-col>
            <v-col cols="12" sm="6"  md="3">
                <!-- <v-sheet class="ma-6 rounded-lg"> -->
                    <v-card class="max-auto widget-card ma-6" max-width="368" color="white" >
                        <v-card-text class="py-2">
                            <v-row no-gutters align="center">
                                <v-col cols="6">
                                    <v-card  color="green" size="60" variant="tonal" class="text-center mt-2" style="width: 90px; height: 90px;">
                                        <v-icon size="70" color="green" class="mt-3">mdi-book-multiple</v-icon>
                                    </v-card>
                                </v-col>
                                <v-col class="text-h2 text-right text-grey-darken-3" cols="6" style="width: 140px;">{{ em.Leave }}</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text class="text-right font-weight-bold text-grey-darken-1">Total Leave Type</v-card-text>
                    </v-card>
                <!-- </v-sheet> -->
            </v-col>
            <v-col cols="12" sm="6"  md="3">
                <!-- <v-sheet class="ma-6 rounded-lg"> -->
                    <v-card class="max-auto widget-card ma-6" max-width="368" color="white">
                        <v-card-text class="py-2">
                            <v-row no-gutters align="center">
                                <v-col cols="6">
                                    <v-card  color="orange" size="60" variant="tonal" class="text-center mt-2" style="width: 90px; height: 90px;">
                                        <v-icon size="70" color="orange" class="mt-3">mdi-card-account-details-outline</v-icon>
                                    </v-card>
                                </v-col>
                                <v-col class="text-h2 text-right text-grey-darken-3" cols="6" style="width: 140px;">{{ em.day }}</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text class="text-right font-weight-bold text-grey-darken-1">Leave Today</v-card-text>
                    </v-card>
                <!-- </v-sheet> -->
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios'
// import BarChart from '../../Components/chart/BarChart.vue'
// import PieChart from '../../Components/chart/PieChart.vue'
export default {
    name: 'dashbord',
    // components: {
    //     BarChart
    //     PieChart,
    // },
    mounted() {
        axios.get('/api/hr/HR/staff/userCount')
        // Staff.countUser()
            .then(response => {
                this.em = response.data
            })
    },
    data: () => ({
        em: {},
        tab: null,
        items: [
            { text: 'HR', value: 'hr', route: '/portal/hr-dashboard' },
            { text: 'Inventory', value: 'inventory', route: '/portal/inventory-dashboard' },
        ]

    }),

}
</script>

<style>
.bar_chart {
    width: 60%;
    margin-top: 20px;
    padding: 20px;
}
.pie_chart {
    height: 500px;
    margin-top: 20px;
    width: 30%;
}
</style>
