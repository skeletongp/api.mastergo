<div class=" w-full col-span-2">
    <div style="height: 22rem" class="p-4 shadow-xl">
        <canvas id="myChart2" ></canvas>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function() {

            const labelsIncomes = @this.data['labelsIncomes'];

            const data = {
                labels: labelsIncomes,
                datasets: [{
                    label: 'Ingresos',
                    backgroundColor: ['#33ae23'],
                    borderColor: ['#33ae23'],
                    data: @this.data['valuesIncomes'],
                },
                {
                    label: 'Gastos',
                    backgroundColor: ['#991111'],
                    borderColor: ['#991111'],
                    data: @this.data['valuesOutcomes'],
                }
            ]
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
                            text: 'Balance de la Semana',
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
                document.getElementById('myChart2'),
                config
            );
        })
    </script>
@endpush
