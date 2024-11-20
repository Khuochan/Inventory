<template>
    <div class="ma-2 text-grey-darken-3 text-center">
        <h3>Total Number of Terminal - By Bank</h3>
    </div>
    <div class="ma-4 ml-12" style="width: 400px; height: 300px">
        <Pie v-if="loaded" :data="chartData" :options="chartOptions"/>
    </div>
</template>

<script>
  import { Pie } from 'vue-chartjs'
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale
  } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

  export default {
    name: 'PieChart',
    components: {
      Pie
    },

    data() {
      return {
        loaded: false,
        chartData: null,
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
        }
      }
    },
    mounted () {
        this.loaded = false
        axios.get('/api/IMS/terminal/pieChart')
                .then(response => {
                    // this.chartData = response.data;
                    const { amk, wing, aba, phillip, bti } = response.data;
                    this.chartData = {
                        labels: ['AMK', 'Wing', 'ABA', 'Phillip', 'BTI Share'],
                        datasets: [
                            {
                                label: 'Terminal',
                                backgroundColor: ['#FF1493', '#00FF00', '#0000FF', '#000099', '#000066'],
                                data: [amk, wing, aba, phillip, bti],
                            }
                        ]
                    };

        this.loaded = true;
                    console.log(this.chartData);
                })
                .catch(error => {
                    console.log(error);
                })
    },
  }
  </script>


