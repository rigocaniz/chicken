<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 12 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="#/" >
	            	<img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	            </a>
	        </div>
			<!-- TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==1}" ng-click="reporteMenu=1;">
					<a href="" role="tab" data-toggle="tab">
						VENTAS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==2}" ng-click="reporteMenu=2;">
					<a href="" role="tab" data-toggle="tab">
						PEDIDO CANCELADO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==3}" ng-click="reporteMenu=3;">
					<a href="" role="tab" data-toggle="tab">
						INGRESOS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==4}" ng-click="reporteMenu=4;">
					<a href="" role="tab" data-toggle="tab">
						INVENTARIO
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!--  VENTAS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==1}" ng-show="reporteMenu==1">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fechaInicio" data-max-date="{{ fechaFinal }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="fechaFinal" data-min-date="{{ fechaInicio }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-warning" ng-click="consultarVentas()">
						    		CONSULTAR REPORTE <span class="glyphicon glyphicon-ok"></span>
						    	</button>
						    	<button type="button" class="btn btn-sm btn-danger">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
						<hr>
						<b>AGRUPAR POR:</b>
						<div class="btn-group" role="group" aria-label="...">
						  	<button type="button" class="btn btn-default" ng-click="filtro='combo'">
						  		COMBO <span class="glyphicon" ng-class="{'glyphicon-check': filtro=='combo', 'glyphicon-unchecked': filtro!='combo'}"></span>
						  	</button>
						  	<button type="button" class="btn btn-default" ng-click="filtro='precio'">
						  		MENÚ <span class="glyphicon" ng-class="{'glyphicon-check': filtro=='precio', 'glyphicon-unchecked': filtro!='precio'}"></span>
						  	</button>
						</div>
						<div class="form-group" ng-show="ventas.encontrado">
							<div class="text-right">
								<span class="label label-danger titulo-nombre" style="font-size: 20px">
									TOTAL: Q. {{ ventas.total | number: 2 }}
								</span>
							</div>
							<br>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th class="text-center col-sm-3">Descripción</th>
										<th class="text-center col-sm-2">Precio Real</th>
										<th class="text-center col-sm-2">Precio Menu</th>
										<th class="text-center col-sm-2">Tipo de <br>Servicio</th>
										<th class="text-center">Comentario</th>
										<th class="text-center col-sm-3">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="venta in ventas.detalleVentas">
										<td>{{ $index + 1 }}</td>
										<td>{{ venta.descripcion }}</td>
										<td class="text-right">{{ venta.precioReal | number: 2 }}</td>
										<td class="text-right">{{ venta.precioMenu | number: 2 }}</td>
										<td class="text-center">{{ venta.tipoServicio }}</td>
										<td>{{ venta.comentario }}</td>
										<td class="text-right">{{ venta.subTotal | number: 2 }}</td>
									</tr>
									<tr>
										<th colspan="6" class="text-right">
											<h4><b>TOTAL</b></h4>
										</th>
										<th class="text-right">
											<h4><b>{{ ventas.total | number: 2 }}</b></h4>
										</th>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
				</div>
				<!-- PEDIDOS CANCELADO-->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==2}" ng-show="reporteMenu==2">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fromDate" data-max-date="{{ untilDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="untilDate" data-min-date="{{ fromDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-danger">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
					</form>
				</div>
				<!-- INGRESOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==3}" ng-show="reporteMenu==3">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fromDate" data-max-date="{{ untilDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="untilDate" data-min-date="{{ fromDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-danger">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
					</form>
				</div>
				<!-- INVENTARIO DE PRODUCTOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==4}" ng-show="reporteMenu==4">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fromDate" data-max-date="{{ untilDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="untilDate" data-min-date="{{ fromDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-danger">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>