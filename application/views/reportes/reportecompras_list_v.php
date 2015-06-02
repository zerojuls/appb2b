<div id="main_container">
    <div class="row-fluid">
        <!--        <a id="btnNuevo" href="#" class="btn btn-secondary color_19" data-toggle="modal">Nuevo</a>
                <a id="btnEditar" href="#" class="btn btn-secondary color_19"  data-toggle="modal">Modificar</a> 
                <a id="btnEditar" href="#" class="btn btn-secondary color_14"  data-toggle="modal">Asignar Montos</a> -->
<!--        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        </div>
        <br/>-->
        <br>
        <div id="grafico" style="min-width: 310px; height: 400px; margin: 0 auto">
        </div>
    </div>
</div>
<script type="text/javascript">

    

    /**** Specific JS for this page ****/
    $(function() {
        <?php
        $jData = str_replace("\\","",$jData);
        $jData = str_replace(array('["[',']","[',']"]}'), array('[[','],[',']]}'), $jData)      
        ?>
        var jData = <?php echo $jData; ?>;
        var jSuma0 = jData.suma0;
        var jSuma1 = jData.suma1;
        var jSuma2 = jData.suma2;
        var jSuma3 = jData.suma3;
        var jSuma4 = jData.suma4;
        var jSuma5 = jData.suma5;
        var jSuma6 = jData.suma6;
        var jSuma7 = jData.suma7;
        var jSuma8 = jData.suma8;
        var jSuma9 = jData.suma9;
        var jSuma10 = jData.suma10;
        var jSuma11 = jData.suma11;
        // var jName1 = jData.mes0.name;

        console.log(jData);
        console.log(jSuma1);
        // console.log(jName1);

        $('#grafico').highcharts({
            chart: {
                type: 'column',
                events: {
                    drilldown: function(e) {
                        if (!e.seriesOptions) {

                            var chart = this,
                                    drilldowns = jData,
                            series = drilldowns[e.point.name];

                            // Carga de las categorias
                            chart.showLoading('Cargando ...');

                            setTimeout(function() {
                                chart.hideLoading();
                                chart.addSeriesAsDrilldown(e.point, series);
                            }, 1000);
                        }

                    }
                }
            },
            title: {
                text: 'Reporte Historico de Compras Corporativas'
            },
            xAxis: {
                type: 'category'
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                        name: 'Monto (S/.)',
                        colorByPoint: true,
                        data: [{
                                    name: 'abril',
                                    y: jSuma0,
                                    drilldown: true
                                },
                                {
                                    name: 'mayo',
                                    y: jSuma1,
                                    drilldown: true
                                },
                                {
                                    name: 'junio',
                                    y: jSuma2,
                                    drilldown: true
                                },
                                {
                                    name: 'julio',
                                    y: jSuma3,
                                    drilldown: true
                                },
                                {
                                    name: 'agosto',
                                    y: jSuma4,
                                    drilldown: true
                                },
                                {
                                    name: 'septiembre',
                                    y: jSuma5,
                                    drilldown: true
                                },
                                {
                                    name: 'octubre',
                                    y: jSuma6,
                                    drilldown: true
                                },
                                {
                                    name: 'noviembre',
                                    y: jSuma7,
                                    drilldown: true
                                },
                                {
                                    name: 'diciembre',
                                    y: jSuma8,
                                    drilldown: true
                                },
                                {
                                    name: 'enero',
                                    y: jSuma9,
                                    drilldown: true
                                },
                                {
                                    name: 'febrero',
                                    y: jSuma10,
                                    drilldown: true
                                },
                                {
                                    name: 'marzo',
                                    y: jSuma11,
                                    drilldown: true
                                }
                            ]
                        }],
            drilldown: {
                series: []
            }
        });
    });
</script>