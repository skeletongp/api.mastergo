<div class="flex flex-col space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div style="height: 22rem" class="p-4 shadow-xl">
                <canvas id="myChart2"></canvas>
            </div>
        </div>
        <div class="h-full">
            <div class="grid grid-cols-2 gap-4 h-full uppercase font-medium">
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>Cant. Invertida</span>
                        <span class="text-lg font-bold">{{ formatNumber($production->setted) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-right"></h1>
                        <span
                            class="text-xs font-bold">{{ formatNumber(($production->setted / $production->setted) * 100) }}%</span>
                    </div>
                </div>
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>Cant. Obtenida</span>
                        <span class="text-lg font-bold">{{ formatNumber($production->getted) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-right"></h1>
                        <span
                            class="text-xs font-bold">{{ formatNumber(($production->getted / $production->setted) * 100) }}%</span>
                    </div>
                </div>
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>Monto Invertido</span>
                        <span
                            class="text-lg font-bold">${{ formatNumber($production->setted * $production->costUnit) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-right"></h1>
                        <span
                            class="text-xs font-bold">{{ formatNumber(($production->setted / $production->setted) * 100) }}%</span>
                    </div>
                </div>
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>Monto Obtenido</span>
                        <span
                            class="text-lg font-bold">${{ formatNumber($production->getted * $production->costUnit) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-right"></h1>
                        <span
                            class="text-xs font-bold">{{ formatNumber(($production->getted / $production->setted) * 100) }}%</span>
                    </div>
                </div>
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>Efectividad</span>
                        <span class="text-lg font-bold">{{ formatNumber($production->eficiency) }}%</span>
                    </div>

                </div>
                <div class=" h-full shadow-xl px-4 py-4 border border-blue-300 rounded-lg flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <span>{{$production->eficiency>=100?'Ganancia':'Merma'}}</span>
                        <span
                            class="text-lg font-bold">{{ formatNumber(abs($production->setted - $production->getted)) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-right"></h1>
                        <span class="text-xs font-bold">{{ formatNumber(abs(100 - $production->eficiency)) }}%</span>
                    </div>
                </div>
            </div>

        </div>
        <div>
            <livewire:productions.production-result :production="$production" />
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {

                const labelResults = @this.data['labelResults'];

                const data = {
                    labels: labelResults,
                    datasets: [{
                        label: 'Productos Terminados',
                        backgroundColor: ['#13aa23'],
                        borderColor: ['#33ae23'],
                        data: @this.data['valueResults'],
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
                                text: 'Resultados de la Producción',
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
</div>
