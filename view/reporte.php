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
						COMPRAS
					</a>
				</li>
				<!--
				<li role="presentation" ng-class="{'active' : reporteMenu==4}" ng-click="reporteMenu=4;">
					<a href="" role="tab" data-toggle="tab">
						INVENTARIO
					</a>
				</li>
			-->
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!--  VENTAS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==1}" ng-show="reporteMenu==1">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fechaInicio" data-max-date="{{ fechaFinal }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="fechaFinal" data-min-date="{{ fechaInicio }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-warning" ng-click="consultarVentas( 'consultar' )">
						    		CONSULTAR <span class="glyphicon glyphicon-ok"></span>
						    	</button>
						    	<button type="button" class="btn btn-sm btn-danger" ng-click="consultarVentas( 'descargar' )">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
						<hr>
						<div class="form-group" ng-show="ventas.encontrado">
							<div class="text-right">
								<span class="label label-warning titulo-nombre" style="font-size: 18px; padding: 4px 10px">
									Estimado Q. {{ ventas.totalEstimado | number: 2 }}
								</span>
								<span class="label label-danger titulo-nombre" style="font-size: 18px; padding: 4px 10px">
									Total Q. {{ ventas.totalVentas | number: 2 }}
								</span>
							</div>
							<br>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th class="text-center col-sm-1">Codigo<br> Menu</th>
										<th class="text-center col-sm-4">Descripción</th>
										<th class="text-center col-sm-2">Cantidad</th>
										<th class="text-center col-sm-2">Total Estimado</th>
										<th class="text-center col-sm-3">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="venta in ventas.detalleVentas">
										<td>{{ $index + 1 }}</td>
										<td class="text-center">{{ venta.codigoMenu }}</td>
										<td>{{ venta.descripcionOrden }}</td>
										<td class="text-center">{{ venta.cantidad }}</td>
										<td class="text-right">{{ venta.totalEstimado | number: 2 }}</td>
										<td class="text-right">{{ venta.totalVenta | number: 2 }}</td>
									</tr>
									<tr>
										<th colspan="4" class="text-right" ng-class="{'success': ventas.totalVentas == ventas.totalEstimado}">
											<b>TOTAL</b>
										</th>
										<th class="text-right" ng-class="{'success': ventas.totalVentas == ventas.totalEstimado}">
											<b>{{ ventas.totalEstimado | number: 2 }}</b>
										</th>
										<th class="text-right"  ng-class="{'danger': ventas.totalVentas < ventas.totalEstimado, 'success': ventas.totalVentas == ventas.totalEstimado}">
											<b>{{ ventas.totalVentas | number: 2 }}</b>
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
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fechaInicioOC" data-max-date="{{ fechaFinalOC }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="fechaFinalOC" data-min-date="{{ fechaInicioOC }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-warning" ng-click="consultarOrdenesC( 'consultar' )">
						    		CONSULTAR <span class="glyphicon glyphicon-ok"></span>
						    	</button>
						    	<button type="button" class="btn btn-sm btn-danger" ng-click="consultarOrdenesC( 'descargar' )">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
						<hr>
						<div class="form-group" ng-show="ordenesCanceladas.encontrado">
							<div class="text-right">
								<span class="label label-danger titulo-nombre" style="font-size: 18px; padding: 4px 10px">
									Encontrados {{ ordenesCanceladas.detalleOrdenesC.length }}
								</span>
							</div>
							<br>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th class="text-center">Cantidad</th>
										<th class="text-center col-sm-3">Descripción</th>
										<th class="text-center col-sm-2">Tipo</th>
										<th class="text-center col-sm-1">Responsable</th>
										<th class="text-center col-sm-1">Cancelación</th>
										<th class="text-center col-sm-5">Comentarios</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="ordenCancelada in ordenesCanceladas.detalleOrdenesC">
										<td>{{ ordenCancelada.cantidad }}</td>
										<td>{{ ordenCancelada.menu }}</td>
										<td class="text-center">{{ ordenCancelada.tipo }}</td>
										<td class="text-center">{{ ordenCancelada.usuarioResponsable }}</td>
										<td class="text-center">{{ ordenCancelada.usuarioCancelacion }}</td>
										<td>{{ ordenCancelada.comentarioCancelacion }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
				</div>
				<!-- INGRESOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==3}" ng-show="reporteMenu==3">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fechaInicioC" data-max-date="{{ fechaFinalC }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> A FECHA:</label>
						      	<input class="form-control" ng-model="fechaFinalC" data-min-date="{{ fechaInicioC }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div>
						    	<br>
						    	<button type="button" class="btn btn-sm btn-warning" ng-click="consultarCompras( 'consultar' )">
						    		CONSULTAR <span class="glyphicon glyphicon-ok"></span>
						    	</button>
						    	<button type="button" class="btn btn-sm btn-danger" ng-click="consultarCompras( 'descargar' )">
						    		GENERAR REPORTE <span class="glyphicon glyphicon-download-alt"></span>
						    	</button>
						    </div>
						</div>
						<hr>
						<div class="form-group" ng-show="compras.encontrado">
							<div class="text-right">
								<span class="label label-danger titulo-nombre" style="font-size: 18px; padding: 4px 10px">
									Total Q. {{ compras.totalCompras | number: 2 }}
								</span>
							</div>
							<br>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th class="text-center col-sm-4">Descripción</th>
										<th class="text-center col-sm-2">Medida</th>
										<th class="text-center col-sm-2">Tipo</th>
										<th class="text-center col-sm-2">Cantidad</th>
										<th class="text-center col-sm-3">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="compra in compras.detalleCompras">
										<td>{{ $index + 1 }}</td>
										<td>{{ compra.producto }}</td>
										<td class="text-center">{{ compra.medida }}</td>
										<td class="text-center">{{ compra.tipoProducto }}</td>
										<td class="text-right">{{ compra.cantidad | number: 2 }}</td>
										<td class="text-right">{{ compra.costoTotal | number: 2 }}</td>
									</tr>
									<tr>
										<th colspan="5" class="text-right success">
											<b>TOTAL</b>
										</th>
										<th class="text-right success">
											<b>{{ compras.totalCompras | number: 2 }}</b>
										</th>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
				</div>
				<!-- INVENTARIO DE PRODUCTOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==4}" ng-show="reporteMenu==4">
					<form class="form-horizontal">
						<div class="form-group">
						    <div class="col-xs-4 col-sm-6 col-md-4 col-lg-3">
						    	<label><i class="fa fa-calendar"></i> DE FECHA:</label>
						      	<input class="form-control" ng-model="fromDate" data-max-date="{{ untilDate }}" placeholder="dd/mm/aaaa" bs-datepicker type="text">
						    </div>
						    <div class="col-xs-4 col-sm-6 col-md-4 col-lg-3">
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