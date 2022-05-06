    <div class=" w-full">
        <div style="height: 22rem" class="p-4 shadow-xl">
            <canvas id="myChart" ></canvas>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {

                const labels = @this.data['labels'];

                const data = {
                    labels: labels,
                    datasets: [{
                        label: 'Balance de Hoy',
                        backgroundColor: ['#33ae23', '#991111'],
                        borderColor: ['#33ae23', '#991111'],
                        data: @this.data['values'],
                    }]
                };

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Balance de Hoy',
                                font: {
                                    size: 18
                                }
                            },
                            legend: {
                                display: true,
                                labels: {
                                    color: '#000',
                                    font: {
                                        size: 14
                                    }
                                }
                            }
                        }
                    }
                };
                const myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                );
            })
        </script>
    @endpush
