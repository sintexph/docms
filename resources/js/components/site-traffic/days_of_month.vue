<script>
    import {
        Line
    } from 'vue-chartjs'

    export default {
        extends: Line,
        props: ['custom_date'],
        methods: {
            getSource() {
                axios.post('/traffic/days-month', {
                    sd: this.custom_date
                }).then(this.renderCharting);
            },
            renderCharting(response) {
                var labels = [];
                var data = [];
                var step = 0;

                response.data.forEach(element => {
                    labels.push(element.day);
                    data.push(element.count);

                });

                // 50 percent of max value will be the step of the chart
                step = Math.max(...data) * 0.2;

                this.renderChart({
                    labels: labels,
                    datasets: [{
                        label: 'Current Month Traffic',
                        backgroundColor: '#eb6c6c80',
                        data: data,
                    }]
                }, {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: step
                            }
                        }]
                    }
                })
            }
        },
        watch: {
            custom_date() {
                this.getSource();
            }
        },
        mounted() {
            this.getSource();

        }
    }

</script>
