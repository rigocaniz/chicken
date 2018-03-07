<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 6 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

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
				<li role="presentation" ng-class="{'active' : menuCaja=='verAjuste'}" ng-click="menuCaja='verAjuste'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-sort"></span> AJUSTES
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<!--  APERTURA / CIERRE DE CAJA -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuCaja=='verCaja'}" ng-show="menuCaja=='verCaja'">
					<div class="text-right">
						<p>
							<button type="button" class="btn btn-warning" ng-click="accionCaja='cierreCaja'" ng-show="caja.idCaja">
								<span class="glyphicon glyphicon-flag"></span> CERRAR CAJA
							</button>
							<button type="button" class="btn btn-success" ng-click="accionCaja='aperturarCaja'" ng-show="!caja.idCaja">
								<span class="glyphicon glyphicon-flag"></span> APERTURAR CAJA
							</button>
							<button type="button" class="btn btn-danger" ng-click="accionCaja=''" ng-show="accionCaja!=''" title="CANCELAR ACCIÓN" data-toggle="tooltip" data-placement="top" tooltip>
								<span class="glyphicon glyphicon-remove"></span>
							</button>
						</p>
					</div>
					<div class="alert alert-danger" role="alert" ng-show="caja.cajaAtrasada">
						<span class="glyphicon glyphicon-info-sign"></span> USTED NO HA REALIZADO EL CIERRE DE SU CAJA DE FECHA/HORA: <strong style="font-size: 18px">{{ caja.fechaHoraApertura }}</strong>
					</div>
					<fieldset class="fieldset">
						<legend class="legend info">APERTURA / CIERRE</legend>
						<form class="form-horizontal" autocomplete="off" novalidate>
							<div class="text-right">
								<label class="label" ng-class="{'label-danger': caja.idEstadoCaja == 2, 'label-success': caja.idEstadoCaja==1}" style="font-size: 17px;">
									ESTADO {{ caja.estadoCaja | uppercase }}
								</label>
							</div>
							<br>
							<div class="form-group">
								<label class="col-sm-3 col-md-2 control-label">CAJERO</label>
								<div class="col-sm-6 col-md-5 col-lg-4">
									<input type="text" class="form-control" ng-model="caja.cajero" placeholder="Cajero" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 col-md-2 control-label">CODIGO OPERADOR</label>
								<div class="col-sm-3 col-md-3 col-lg-2">
									<input type="text" class="form-control" ng-model="caja.codigoUsuario" placeholder="Cajero" disabled>
								</div>
								<label class="col-sm-3 col-md-2 control-label">USUARIO</label>
								<div class="col-sm-3 col-md-3 col-lg-2">
									<input type="text" class="form-control" ng-model="caja.usuario" placeholder="Cajero" disabled>
								</div>
							</div>

							<!-- DENOMINACIONES -->
							<legend class="text-center" ng-show="accionCaja">
								<i class="fa fa-money" aria-hidden="true"></i> DENOMINACIONES
							</legend>
							<div class="form-group" ng-show="accionCaja">
								<div class="col-sm-6" ng-repeat="denominacion in caja.lstDenominaciones">
									<div class="form-group">
										<label class="col-sm-4">{{ denominacion.descripcion }} de {{ denominacion.denominacion }}</label>
										<div class="col-sm-4">
											<input type="number" min="0"  class="form-control" ng-model="denominacion.cantidad" placeholder="Cantidad" ng-pattern="/^[0-9]+?$/" step="0">
										</div>
										<div class="col-sm-4 text-right">
											<kbd class="numEfectivo">
												{{ ( denominacion.cantidad ? (denominacion.cantidad * denominacion.denominacion ) : '0' ) | number:2 }}
											</kbd>
										</div>
									</div>
								</div>
							</div>
							<div class="text-right" ng-show="accionCaja=='cierreCaja'">
								<button type="button" class="btn btn-sm btn-success" ng-click="caja.agregarFaltante=!caja.agregarFaltante">
									<span class="glyphicon glyphicon-plus"></span>
									Agregar Faltante
								</button>
								<br><br>
							</div>
							<div class="form-group" ng-show="accionCaja=='cierreCaja' && caja.agregarFaltante">
								<div class="col-sm-6"></div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-4">EFECTIVO FALTANTE</label>
										<div class="col-sm-4">
											<input type="number" min="0"  class="form-control" ng-model="caja.efectivoFaltante" placeholder="Total Efectivo" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="1">
										</div>
										<div class="col-sm-4 text-right">
											<kbd class="numEfectivo">
												{{ ( caja.efectivoFaltante ? caja.efectivoFaltante : '0' ) | number:2 }}
											</kbd>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-6 col-lg-7"></div>
							<div class="col-sm-8 col-md-6 col-lg-5">
								<h4><b>
								<table class="table table-hover">
									<tr ng-show="accionCaja=='cierreCaja'">
										<td class="col-sm-7 text-right"><b>EFECTIVO INICIAL:</b></td>
										<td class="col-sm-5 text-right">Q. {{ caja.efectivoInicial | number: 2 }}</td>
									</tr>
									<tr ng-show="caja.agregarFaltante && accionCaja=='cierreCaja' && caja.efectivoFaltante > 0">
										<td class="col-sm-7 text-right"><b>EFECTIVO FALTANTE:</b></td>
										<td class="col-sm-5 text-right">Q. {{ caja.efectivoFaltante | number: 2 }}</td>
									</tr>
									<tr ng-show="accionCaja">
										<td class="col-sm-7 text-right"><b>EFECTIVO {{ accionCaja == 'cierreCaja' ? 'FINAL' : 'INICIAL' }}:</b></td>
										<td class="col-sm-5 text-right">Q. {{ retornarTotal() | number: 2 }}</td>
									</tr>
								</table>
								</b></h4>
							</div>
							<div class="form-group text-center">
								<button type="button" class="btn btn-success btn-lg" ng-show="accionCaja=='aperturarCaja'" ng-click="consultaCaja()">
									<span class="glyphicon glyphicon-folder-open"></span> Aperturar Caja (F6)
								</button>
								<button type="button" class="btn btn-warning btn-lg" ng-show="accionCaja=='cierreCaja'" ng-click="consultaCaja()">
									<span class="glyphicon glyphicon-folder-close"></span> Cerrar Caja (F6)
								</button>
							</div>
						</form>
					</fieldset>
				</div>

				<!--  INGRESO / EGRESO CAJA -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuCaja=='verAjuste'}" ng-show="menuCaja=='verAjuste'">
					<fieldset class="fieldset">
						<legend class="legend info">INGRESOS / EGRESOS VARIOS</legend>
						<form class="form-horizontal" autocomplete="off" novalidate>
							<div class="form-group">
								<label class="col-sm-2 control-label">Tipo Movimiento</label>
								<div class="col-sm-4">
									<select ng-model="movimiento.idTipoMovimiento" class="form-control" focus-enter>
										<option value="3">Ingreso</option>
										<option value="4">Egreso</option>
									</select>
								</div>
								<div class="col-sm-2">
									<span class="label label-success" ng-show="movimiento.idTipoMovimiento==3">INGRESO</span>
									<span class="label label-danger" ng-show="movimiento.idTipoMovimiento==4">EGRESO</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Monto</label>
								<div class="col-sm-2">
									<input type="number" class="form-control" ng-model="movimiento.monto" placeholder="Q." focus-enter>
								</div>
								<label class="col-sm-2 control-label">Motivo</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" ng-model="movimiento.motivo" placeholder="Motivo" focus-enter>
								</div>
							</div>

							<!-- DENOMINACIONES -->
							<div class="form-group text-center">
								<button type="button" class="btn btn-success" ng-click="guardarMovimiento()">
									<span class="glyphicon glyphicon-ok"></span> Guardar Movimiento
								</button>
								<button type="button" class="btn btn-default" ng-click="movimiento={idTipoMovimiento:'3',motivo:'',monto:''}">
									<span class="glyphicon glyphicon-remove"></span> Cancelar
								</button>
							</div>
						</form>
					</fieldset>

					<!-- LISTA DE MOVIMIENTOS DEL DIA -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title" style="padding-bottom:9px">
								Movimientos
								<button type="button" class="btn btn-sm btn-success" style="float:right">
									T. Ingresos <span class="badge" style="color:#222">{{totalIngresos | number:2}}</span>
								</button>
								<button type="button" class="btn btn-sm btn-danger" style="float:right;margin-right:3px">
									T. Egresos <span class="badge" style="color:#222">{{totalEgresos | number:2}}</span>
								</button>
							</h3>
						</div>
						<div class="panel-body">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Tipo Movimiento</th>
										<th>Monto</th>
										<th>Motivo</th>
										<th>Usuario Caja</th>
										<th>Fecha Registro</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="mov in lstMovimientos">
										<td>
											<span class="label label-success text-uppercase" ng-show="mov.idTipoMovimiento==3">
												<span class="glyphicon glyphicon-plus"></span>
												{{mov.tipoMovimiento}}
											</span>
											<span class="label label-danger text-uppercase" ng-show="mov.idTipoMovimiento==4">
												<span class="glyphicon glyphicon-minus"></span>
												{{mov.tipoMovimiento}}
											</span>
										</td>
										<td>
											<strong>{{mov.monto | number:2}}</strong>
										</td>
										<td>{{mov.motivo}}</td>
										<td>{{mov.usuarioCaja}}</td>
										<td>{{mov.fechaRegistro}}</td>
									</tr>
								</tbody>
							</table>
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