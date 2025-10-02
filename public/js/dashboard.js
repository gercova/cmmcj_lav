async function hcCount(){
    try {
        const response = await axios.get(`${API_URL}/sys/dashboard/hcCount/`);
        if(response.status == 200){
            console.log(response.data);
            $('#historiesCount').text(response.data.hc).append(`&nbsp;<small>historias</small>`);
            $('#examsCount').text(response.data.ex).append(`&nbsp;<small>exámenes</small>`);
            $('#appointmentsCount').text(response.data.ap).append(`&nbsp;<small>Citas</small>`);
            $('#dailyQuotesCount').text(response.data.cd).append(`&nbsp;<small>pacientes</small>`);
        }
    } catch (error) {
        console.log(error);
    }
}

hcCount();

const year = (new Date).getFullYear();
annualData(year);
dxData();
mxData();

$('#year').on('change', function() {
    const year = $(this).val();
    if (year) {
        annualData(year);
    }
});

async function annualData(year) {
    await fetch(`${API_URL}/sys/dashboard/annual/${year}`)
    .then(response => response.json())
    .then(seriesData => {
        Highcharts.chart('histories', {
            chart: {
                type: 'column'
            },
            title: {
                text: `Producción de Historias y Exámenes en ${year}`
            },
            subtitle: {
                text: 'Fuente: Sistema de Gestión'
            },
            xAxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                crosshair: true,
                accessibility: {
                    description: 'Meses'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad'
                }
            },
            tooltip: {
                valueSuffix: ' (unidades)'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: seriesData // ✅ ¡Aquí va tu JSON directamente!
        });
    })
    .catch(error => {
        console.error('Error cargando los datos:', error);
        document.getElementById('histories').innerHTML = '<p style="color:red;">No se pudieron cargar los datos.</p>';
    });
}

async function dxData () {
    await fetch('/sys/dashboard/dxByExam')
    .then(response => response.json())
    .then(data => {
        const chartOptions = {
            chart: {
                type: 'pie',
                custom: {},
                events: {
                    render() {
                        const chart = this,
                            series = chart.series[0];
                        let customLabel = chart.options.chart.custom.label;

                        if (!customLabel) {
                            const total = series.points.reduce((sum, point) => sum + point.y, 0);
                            customLabel = chart.options.chart.custom.label =
                                chart.renderer.label(
                                    'Total<br/>' +
                                    '<strong>' + total.toLocaleString() + '</strong>'
                                )
                                .css({
                                    color: 'var(--highcharts-neutral-color-100, #000)',
                                    textAnchor: 'middle'
                                })
                                .add();
                        }

                        const x = series.center[0] + chart.plotLeft,
                                y = series.center[1] + chart.plotTop - (customLabel.attr('height') / 2);

                        customLabel.attr({
                            x,
                            y
                        });

                        customLabel.css({
                            fontSize: `${series.center[2] / 12}px`
                        });
                    }
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            title: {
                text: 'Top 10 Diagnósticos por Exámenes'
            },
            subtitle: {
                text: 'Fuente: Sistema de Salud'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    borderRadius: 8,
                    dataLabels: [{
                        enabled: true,
                        distance: 20,
                        format: '{point.name}'
                    }, {
                        enabled: true,
                        distance: -15,
                        format: '{point.percentage:.0f}%',
                        style: {
                            fontSize: '0.9em'
                        }
                    }],
                    showInLegend: true
                }
            },
            series: data.series // ✅ ¡Aquí va tu JSON directamente!
        };

        Highcharts.chart('diagnosisByExams', chartOptions);
    })
    .catch(error => {
        console.error('Error cargando datos de diagnósticos:', error);
        document.getElementById('diagnosisByExams').innerHTML = `
            <p style="color: red; text-align: center; margin-top: 50px;">
                No se pudieron cargar los diagnósticos.
            </p>
        `;
    });
}

async function mxData () {
    await fetch('/sys/dashboard/mxByExam')
    .then(response => response.json())
    .then(data => {
        const chartOptions = {
            chart: {
                type: 'pie',
                custom: {},
                events: {
                    render() {
                        const chart = this,
                            series = chart.series[0];
                        let customLabel = chart.options.chart.custom.label;

                        if (!customLabel) {
                            const total = series.points.reduce((sum, point) => sum + point.y, 0);
                            customLabel = chart.options.chart.custom.label =
                                chart.renderer.label(
                                    'Total<br/>' +
                                    '<strong>' + total.toLocaleString() + '</strong>'
                                )
                                .css({
                                    color: 'var(--highcharts-neutral-color-100, #000)',
                                    textAnchor: 'middle'
                                })
                                .add();
                        }

                        const x = series.center[0] + chart.plotLeft,
                                y = series.center[1] + chart.plotTop - (customLabel.attr('height') / 2);

                        customLabel.attr({
                            x,
                            y
                        });

                        customLabel.css({
                            fontSize: `${series.center[2] / 12}px`
                        });
                    }
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            title: {
                text: 'Top 10 Diagnósticos por Exámenes'
            },
            subtitle: {
                text: 'Fuente: Sistema de Salud'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    borderRadius: 8,
                    dataLabels: [{
                        enabled: true,
                        distance: 20,
                        format: '{point.name}'
                    }, {
                        enabled: true,
                        distance: -15,
                        format: '{point.percentage:.0f}%',
                        style: {
                            fontSize: '0.9em'
                        }
                    }],
                    showInLegend: true
                }
            },
            series: data.series // ✅ ¡Aquí va tu JSON directamente!
        };

        Highcharts.chart('medicationsByExams', chartOptions);
    })
    .catch(error => {
        console.error('Error cargando datos de diagnósticos:', error);
        document.getElementById('diagnosisByExams').innerHTML = `
            <p style="color: red; text-align: center; margin-top: 50px;">
                No se pudieron cargar los diagnósticos.
            </p>
        `;
    });
}