$(document).ready(function(){
    var year = (new Date).getFullYear();
    datag1(_baseurl_, year);
    datatest(_baseurl_, year);
    dataDiagnosis(_baseurl_);
    datag3(_baseurl_);
    datag5(_baseurl_);
    datag6(_baseurl_);

    $('#year').on('change', function(){
        let yearselect = $(this).val();
        datag1(_baseurl_, yearselect);
        datatest(_baseurl_, yearselect);
    });
    /* GRAFICO CANTIDAD DE PACIENTES */
    function datag1(_baseurl_, year){
        let namesMonth = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        fetch(`${_baseurl_}hcl/reportes/getData/${year}`, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(res => {
            let meses       = new Array();
            let cantidad    = new Array();
            $.each(res.data, function(key, value){
                meses.push(namesMonth[value.mes -1]);
                cantidad.push(Number(value.cantidad));
            });
            graficag1(meses, cantidad, year);
        });
    }

    function graficag1(meses, cantidad, year){
        Highcharts.chart('histories', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Pacientes nuevos'
            },
            subtitle: {
                text: 'Año: ' + year
            },
            xAxis: {
                categories: meses,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de pacientes'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr>'+
                    '<td style="color:{series.color};padding:0">Cantidad: </td>'+
                    '<td style="padding:0"><b>{point.y:f} pacientes</b></td>'+
                    '</tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series:{
                    dataLabels:{
                        enabled:true,
                        formatter:function(){
                            return Highcharts.numberFormat(this.y, 0)
                        }
                    }
                }
            },
            series: [{
                name: 'Meses',
                data: cantidad
            }]
        });
    }
    /**Gráfico test*/
    function datatest(_baseurl_, year){
        namesMonth = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        fetch(`${_baseurl_}hcl/reportes/getDataTest/${year}`, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(res => {
            let meses       = new Array();
            let cantidad    = new Array();
            $.each(res.data, function(key, value){
                meses.push(namesMonth[value.mes -1]);
                cantidad.push(Number(value.cantidad));
            });
            graficaTest(meses, cantidad, year);
        });
    }

    function graficaTest(meses, cantidad, year){
        Highcharts.chart('tests', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Exámenes nuevos'
            },
            subtitle: {
                text: 'Año: ' + year
            },
            xAxis: {
                categories: meses,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de exámenes'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr>'+
                    '<td style="color:{series.color};padding:0">Cantidad: </td>'+
                    '<td style="padding:0"><b>{point.y:f} exámenes</b></td>'+
                    '</tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series:{
                    dataLabels:{
                        enabled:true,
                        formatter:function(){
                            return Highcharts.numberFormat(this.y, 0);
                        }
                    }
                }
            },
            series: [{
                name: 'Meses',
                data: cantidad
            }]
        });
    }
    /**Gráfico de diagnóstico*/
    function dataDiagnosis(_baseurl_){
        fetch(`${_baseurl_}hcl/reportes/getDataDiagnosis`, {method: 'GET'})
        .then(res => res.json())
        .then(res => {
            let values = new Array();
            for(const i in res.data){
                let value = new Array(res.data[i].diagnostico, Number(res.data[i].cantidad));
                values.push(value);
            }
            graficaD(values);
        });
    }

    function graficaD(values){
        Highcharts.chart('diagnosis', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Diagnóstico'
            },
            subtitle: {
                text: 'Diagnósticos de pacientes'
            },
            tooltip: {
                pointFormat: '{values.name} Cantidad: <b>{point.y}</b> <br>Porcentaje: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
                series:{
                    dataLabels:{
                        enabled:true,
                    }
                }
            },
            series: [{
                name: 'Nro. de diagnósticos',
                colorByPoint: true,
                data: values
            }]
        });
    }

    /* GRAFICO PACIENTES POR SEXO*/
    function datag3(_baseurl_){
        fetch(`${_baseurl_}hcl/reportes/getDataSex`, {method: 'GET'})
        .then(res => res.json())
        .then(res => {
            let series = new Array();
            for(const i in res.data){
                let serie = new Array(res.data[i].sexo, Number(res.data[i].cantidad));
                series.push(serie);
            }
            graficag3(series);
        });
    }

    function graficag3(series){
        Highcharts.chart('sexo', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Pacientes por sexo'
            },
            subtitle: {
                text: 'Sexo de pacientes'
            },
            tooltip: {
                pointFormat: '{series.name}: {point.y} <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
                series:{
                    dataLabels:{
                        enabled:true,
                    }
                }
            },
            series: [{
                name: 'Nro. de pacientes',
                colorByPoint: true,
                data: series
            }]
        });
    }

    function datag5(_baseurl_){
        fetch(`${_baseurl_}hcl/reportes/getDataBloodGroup`, {method:'GET'})
        .then(res => res.json())
        .then(res => {
            let values = new Array();    
            for(const i in res.data){
                let serie = new Array(res.data[i].grupo, Number(res.data[i].cantidad));
                values.push(serie);
            }
            graficag5(values);
        });
    }

    function graficag5(values){
        Highcharts.chart('graphicbloodgroup', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grupos sanguíneos'
            },
            subtitle: {
                text: 'Data Base Neumotar'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Datos recolectados'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Nro de pacientes: {point.y}'
            },
            series: [{
                name: 'Population',
                data: values,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    }

    function datag6(_baseurl_){
        fetch(`${_baseurl_}hcl/reportes/getQuery6`, {method: 'GET'})
        .then(res => res.json())
        .then(res => {
            let values = [];
            for(const i in res.data){
                let indice = new Array(res.data[i].estados, Number(res.data[i].cantidad));
                values.push(indice);
            }
            graficag6(values);
        });
    }

    function graficag6(values){
        Highcharts.chart('status', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Estados civil de pacientes'
            },
            subtitle: {
                text: 'Data Base Neumotar'
            },
            tooltip: {
                pointFormat: '{series.name}: {point.y} <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
                series:{
                    dataLabels:{
                        enabled:true,
                    }
                }
            },
            series: [{
                name: 'Cantidad',
                colorByPoint: true,
                data: values
            }]
        });
    }
});