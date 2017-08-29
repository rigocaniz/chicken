<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="#/" >
	            	<img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	            </a>
	        </div>

			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : menuCaja=='verCaja'}" ng-click="menuCaja='verCaja'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> CAJA
					</a>
				</li>
			</ul>

			<!-- INGRESO NUEVO PRODUCTO -->
			<div class="tab-content">

				<!--  PRODUCTOS DEL INVENTARIO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuCaja=='verCaja'}" ng-show="menuCaja=='verCaja'">
					<div class="panel" ng-class="{'panel-warning' : !caja.idCaja, 'panel-success': caja.idCaja }">
						<div class="panel-heading">
							<h3 class="panel-title">
								APERTURA / CIERRE CAJA
							</h3>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<p>
								<!--
									<button type="button" class="btn btn-success" ng-click="accionCaja='aperturarCaja'" ng-show="!caja.idCaja">
										<span class="glyphicon glyphicon-plus"></span> APERTURAR CAJA
									</button>
								-->
									<button type="button" class="btn btn-warning" ng-click="accionCaja='cierreCaja'" ng-show="caja.idCaja">
										<span class="glyphicon glyphicon-plus"></span> CERRAR CAJA
									</button>
								</p>
							</div>
							<fieldset class="fieldset">
								<legend class="legend info">APERTURA DE CAJA</legend>
								<form class="form-horizontal" autocomplete="off" novalidate>
									<div class="text-right">
										<strong>ESTADO:</strong>
										<label class="label" ng-class="{'label-danger': caja.idEstadoCaja == 4, 'label-success': caja.idEstadoCaja==1}" style="font-size: 18px;">
											 {{ caja.estadoCaja }}	
										</label>
									</div>
									<br>
									<div class="form-group" ng-show="accionCaja=='aperturarCaja' || accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">CAJERO</label>
										<div class="col-sm-6 col-md-5 col-lg-4">
											<input type="text" class="form-control" ng-model="caja.cajero" placeholder="Cajero" disabled>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='aperturarCaja' || accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">OPERADOR</label>
										<div class="col-sm-3 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.cajero" placeholder="Cajero" disabled>
										</div>
										<label class="col-sm-3 col-md-2 control-label">USUARIO</label>
										<div class="col-sm-3 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.usuario" placeholder="Cajero" disabled>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='aperturarCaja' || accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">FECHA DE APERTURA</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.fechaApertura" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker disabled>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='aperturarCaja' || accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">EFECTIVO INICIAL</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.efectivoInicial" placeholder="Efectivo Inicial" ng-disabled="accionCaja=='cierreCaja'">
										</div>
										<div class="col-sm-4 col-md-3 col-lg-2" ng-show="caja.efectivoInicial">
											<kbd class="numEfectivo">Q. {{ caja.efectivoInicial | number:2 }}</kbd>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">EFECTIVO FINAL</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<input type="number" class="form-control" ng-model="caja.efectivoFinal" placeholder="Efectivo Inicial">
										</div>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<kbd class="numEfectivo">Q. {{ caja.efectivoFinal | number:2 }}</kbd>
										</div>
										<label class="col-sm-2 control-label">EFECTIVO</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<label class="label label-success numEfectivo">Q. {{ caja.efectivoInicial + caja.efectivoFinal | number:2 }}</label>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">EFECTIVO SOBRANTE</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.efectivoSobrante" placeholder="Efectivo Sobrante">
										</div>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<kbd class="numEfectivo">Q. {{ caja.efectivoSobrante | number:2 }}</kbd>
										</div>
									</div>
									<div class="form-group" ng-show="accionCaja=='cierreCaja'">
										<label class="col-sm-3 col-md-2 control-label">EFECTIVO FALTANTE</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<input type="text" class="form-control" ng-model="caja.efectivoFaltante" placeholder="Efectivo Flotante">
										</div>
										<div class="col-sm-4 col-md-3 col-lg-2">
											<kbd class="numEfectivo">Q. {{ caja.efectivoFaltante | number:2 }}</kbd>
										</div>
									</div>
									<div class="form-group text-center">
										<button type="button" class="btn btn-success btn-lg" ng-show="caja.idCaja == 0" ng-click="consultaCaja()">
											<span class="glyphicon glyphicon-folder-open"></span> REALIZAR APERTURA
										</button>

										<button type="button" class="btn btn-warning btn-lg" ng-show="accionCaja=='cierreCaja'" ng-click="consultaCaja()">
											<span class="glyphicon glyphicon-folder-close"></span> REALIZAR CIERRE
										</button>
									</div>
								</form>
							</fieldset>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>






<!-- DIALOGO CONSULTAR CIERRE DIARIO -->
<script type="text/ng-template" id="dial.verCierreDiario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-primary">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-list-alt"></span>
					HISTORIAL CIERRE DIARIO DE INVENTARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form" name="$parent.formCierre">
						<div class="form-group">
							<div class="col-md-4 col-sm-5 col-xs-8">
								<label class="control-label">SELECCIONE LA FECHA DE CIERRE</label>
								<div class="input-group">
								  	<span class="input-group-addon">
    									<span class="fa fa-calendar"></span>
								  	</span>
    								<input type="text" class="form-control" ng-model="fechaCierre" ng-change="cargarFechaCierre( fechaCierre )" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker>
								</div>
							</div>	
						</div>
						<hr>
						<div ng-show="fechaCierreP.encontrado">
							<div class="form-group">
								<div class="col-sm-3 col-xs-6">
									<label class="control-label">FECHA DE CIERRE</label>
									<div class="input-group">
									  	{{ fechaCierreP.fechaCierre }}
									</div>
								</div>s
								<div class="col-sm-3 col-xs-6">
									<label class="control-label">REALIZADO POR:</label>
									<div>
										<kbd class="numEfectivo">Q. {{ fechaCierreP.usuario | uppercase }}</kbd>
									</div>
								</div>
								<div class="col-sm-3 col-xs-6">
									<label class="control-label">FECHA / HORA:</label>
									<div>
										<kbd class="numEfectivo">Q. {{ fechaCierreP.fechaHora }}</kbd>
									</div>
								</div>
								<div class="col-sm-3 col-xs-6">
									<label class="control-label">TIPO DE CIERRE:</label>
									<div>
										<kbd class="numEfectivo">Q. {{ fechaCierreP.todos ? 'TODOS LOS PRODUCTOS' : 'PRODUCTOS IMPORTANTES' }}</kbd>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">COMENTARIO:</label>
									<div>
										{{ fechaCierreP.comentario }}
									</div>
								</div>
							</div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="col-sm-1 text-center">No.</th>
										<th class="col-sm-3 text-center">Producto</th>
										<th class="col-sm-2 text-center">Tipo Producto</th>
										<th class="col-sm-1 text-center">Perecedero</th>
										<th class="col-sm-2 text-center">Cantidad</th>
										<th class="col-sm-2 text-center">Medida</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="inv in fechaCierreP.lstProductos" ng-class="{'border-success':inv.importante}">
										<td class="text-right">
											{{ $index + 1 }}
										</td>
										<td>
											{{ inv.producto }}
										</td>
										<td class="text-center">
											{{ inv.tipoProducto }}
										</td>
										<td class="text-center">
											{{ inv.perecedero ? 'SI' : 'NO' }}
										</td>
										<td class="text-center success">
											{{ inv.cantidadCierre }}
										</td>
										<td class="text-center">
											{{ inv.medida }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div ng-show="!fechaCierreP.encontrado && fechaCierre">
							<div class="alert alert-warning" role="alert">
								<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
							</div>
						</div>
					</form>
			
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" ng-click="$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>
