<template>
    <div class="container">
        <div class="row justify-content-center">
           <v-card>
               <v-card-text>
                  <line-chart :chart-data="datacollection"></line-chart>
                    <button @click="fillData()">Randomize</button>
               </v-card-text>
           </v-card>
        </div>
    </div>
</template>

<script>
    import LineChart from './LineChart.js'
    export default {
        components: {
            LineChart
        },
        data () {
            return {
                datacollection: null
            }
        },
        mounted () {
            this.fillData()
        },
        methods: {
            fillData () {
                this.datacollection = {
                labels: [this.getRandomInt(), this.getRandomInt(),this.getRandomInt()],
                datasets: [
                    {
                        label: 'Data One',
                        backgroundColor: '#f87979',
                        data: [this.getRandomInt(), this.getRandomInt(),this.getRandomInt()]
                    }
                ]
                }
            },
            getRandomInt () {
                return Math.floor(Math.random() * (50 - 5 + 1)) + 5
            }
        }
    }
</script>
