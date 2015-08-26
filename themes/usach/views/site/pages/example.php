<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <h1>Bienvenido a la Plantilla Base!</h1>
        <p>Para informarse de los módulos y características implementadas, leer el archivo README.md</p>
		<p>Esta plantilla puede ser modificada en <code><?php echo __FILE__; ?></code>.</p>   
        <i class="fa fa-refresh fa-spin"></i> Ejemplo de Font Awesome         
        <p><a class="btn btn-primary btn-lg" href="https://github.com/ikirux/matrix">Aprenda más &raquo;</a></p>	
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-offset-2">
            <strong>Ejemplo Gráfico</strong>
        </div>
    </div>
    <div class="row">
        <?php $this->widget(
            'chartjs.widgets.ChBars', 
            [
                'width' => 600,
                'height' => 300,
                'htmlOptions' => [],
                'labels' => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio"],
                'datasets' => [
                    [
                        "fillColor" => "rgba(220,220,220,0.5)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => [65, 59, 90, 81, 56, 55, 40],
                    ],
                    [
                        "fillColor" => "rgba(151,187,205,0.5)",
                        "strokeColor" => "rgba(151,187,205,1)",
                        "data" => [28, 48, 40, 19, 96, 27, 100],                    
                    ]
                ],
                'options' => []
            ]
        ); ?>  
    </div>    
</div>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-lg-4">
            <h2>Cabecera</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
	    <div class="col-lg-4">
	        <h2>Cabecera</h2>
	        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
	        <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
	    </div>
	    <div class="col-lg-4">
	        <h2>Cabecera</h2>
	        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
	        <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
	    </div>
    </div>
</div> <!-- /container -->   