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