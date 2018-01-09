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