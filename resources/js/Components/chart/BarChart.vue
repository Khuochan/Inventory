<template>
    <div class="ma-2 text-grey-darken-3 text-center">
        <h3>Total Number of Terminal - By Bank</h3>
    </div>
    <div class="ma-4 ml-12" style="width: 700px;height: 350px">
      <Bar v-if="loaded" :data="chartData" :options="chartOptions"/>
    </div>
  </template>

  <script>
  import { Bar } from 'vue-chartjs'
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    BarElement
  } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)

  export default {
    name: 'BarChart',
    components: {
      Bar
    },

    data() {
      return {
        loaded: false,
        chartData: null,
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              grid: {
                display: false
              }
            },
            y: {
              beginAtZero: true
            }
          }
        }
      }
    },
    mounted () {
      this.loaded = false
      axios.get('/api/IMS/terminal/pieChart')
        .then(response => {
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
        })
        .catch(error => {
          console.log(error);
        })
    },
  }
  </script>
