@extends('admin.page')

@section('title', $page->op_title . ' | Estatísticas - LinkBree')

@section('body')

    <div class="mb-5 container d-flex flex-column align-items-center">
        <h2 class="text-center my-3">Views</h2>

        <div class="chart-container" style="position: relative; height: 100%; width: 90%; z-index: 1">
            <canvas class="chartjs-render-monitor" id="myChart"></canvas>
        </div>
    </div>

    <h2 class="text-center my-3">Cliques</h2>

    @foreach ($links as $link)
        <div class="card mx-2 my-4">
            <div class="card-header bg-success text-white">
                {{ $link->title }}
            </div>
            <div class="card-body">
                <div class="row-cols-3 d-flex flex-wrap">
                    <div class="flex-grow-1 d-flex flex-column align-items-center m-2 bg-dark text-white px-2 py-3 rounded">
                        <div class="text-center mb-2">
                            Último dia
                        </div>
                        <div class="fs-4">
                            {{ $link->last_day }}
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column align-items-center m-2 bg-dark text-white px-2 py-3 rounded">
                        <div class="text-center mb-2">
                            Última semana
                        </div>
                        <div class="fs-4">
                            {{ $link->last_week }}
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column align-items-center m-2 bg-dark text-white px-2 py-3 rounded">
                        <div class="text-center mb-2">
                            Último mês
                        </div>
                        <div class="fs-4">
                            {{ $link->last_month }}
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column align-items-center m-2 bg-dark text-white px-2 py-3 rounded">
                        <div class="text-center mb-2">
                            Último ano
                        </div>
                        <div class="fs-4">
                            {{ $link->last_year }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        let dataArray = [{{$viewsData}}];

        var ctx = document.getElementById("myChart");
        var data = {
            labels: [{!!$viewsLabel!!}],
            datasets: [{
                data: dataArray,
                backgroundColor: "#4082c4"
            }]
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                "hover": {
                    "animationDuration": 0
                },
                "animation": {
                    "duration": 1,
                    "onComplete": function() {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart
                            .defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];

                                console.log(bar._model.x);
                                console.log(bar._model.y);

                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    "display": false
                },
                tooltips: {
                    "enabled": false
                },
                scales: {
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            max: Math.max(...data.datasets[0].data) + (Math.max(...data.datasets[0].data) * (40 / 100)),
                            display: false,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

    </script>
@endsection
