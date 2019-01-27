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
							<button type="button" class="btn btn-info noBorde" ng-click="abrirDialogoHistorial()">
								<span class="glyphicon glyphicon-th-list"></span> HISTORIAL CAJA(S)
							</button>
							<button type="button" class="btn btn-warning" ng-click="accionCaja='cierreCaja'" ng-show="caja.idCaja">
								<span class="glyphicon glyphicon-flag"></span> CERRAR CAJA
							</button>
							<button type="button" class="btn btn-success" ng-click="accionCaja='aperturarCaja'" ng-show="!caja.idCaja">
								<span class="glyphicon glyphicon-flag"></span> APERTURAR CAJA
							</button>
							<button type="button" class="btn btn-danger" ng-click="accionCaja=''" ng-show="accionCaja!=''" data-title="<b>CANCELAR ACCIÓN</b>" data-placement="top" bs-tooltip>
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
							<div class="text-right" ng-show="accionCaja=='cierreCaja' && caja.efectivoFaltante">
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
											<input type="number" min="0" class="form-control" ng-model="caja.efectivoFaltante" placeholder="Total Efectivo" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="1">
										</div>
										<div class="col-sm-4 text-right">
											<kbd class="numEfectivo">
												{{ ( caja.efectivoFaltante ? caja.efectivoFaltante : '0' ) | number:2 }}
											</kbd>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-5"></div>
							<div class="col-sm-8 col-md-8 col-lg-7">
								<h4><b>
								<table class="table table-hover table-condensed">
									<tr ng-show="accionCaja=='cierreCaja'">
										<td class="col-sm-5 col-lg-4 text-right"><b>EFECTIVO INICIAL</b></td>
										<td class="col-sm-4 text-right">Q. {{ caja.efectivoInicial | number: 2 }}</td>
									</tr>
									<tr ng-show="accionCaja=='cierreCaja'">
										<td class="col-sm-5 col-lg-4 text-right"><b>EGRESOS CAJA</b></td>
										<td class="col-sm-4 text-right">Q. {{ caja.totalEgresos | number: 2 }}</td>
									</tr>
									<tr ng-show="caja.agregarFaltante && accionCaja=='cierreCaja' && caja.efectivoFaltante > 0">
										<td class="col-sm-5 col-lg-4 text-right"><b>EFECTIVO FALTANTE</b></td>
										<td class="col-sm-4 text-right">Q. {{ caja.efectivoFaltante | number: 2 }}</td>
									</tr>
									<tr ng-show="accionCaja">
										<td class="col-sm-5 col-lg-4 text-right"><b>EFECTIVO {{ accionCaja == 'cierreCaja' ? 'FINAL' : 'INICIAL' }}</b></td>
										<td class="col-sm-4 text-right">Q. {{ retornarTotal() | number: 2 }}</td>
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
									<div class="btn-group" role="group" aria-label="">
									  	<button type="button" class="btn" ng-click="movimiento.idTipoMovimiento=3" ng-class="{'btn-success': movimiento.idTipoMovimiento==3, 'btn-default': movimiento.idTipoMovimiento!=3}">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': movimiento.idTipoMovimiento==3, 'glyphicon-unchecked': movimiento.idTipoMovimiento!=3}"></span>
									  		Ingreso
									  	</button>
									  	<button type="button" class="btn" ng-click="movimiento.idTipoMovimiento=4" ng-class="{'btn-danger': movimiento.idTipoMovimiento==4, 'btn-default': movimiento.idTipoMovimiento!=4}">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': movimiento.idTipoMovimiento==4, 'glyphicon-unchecked': movimiento.idTipoMovimiento!=4}"></span>
									  		Egreso
									  	</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Monto</label>
								<div class="col-sm-2">
									<input type="number" class="form-control" ng-model="movimiento.monto" min="0" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" placeholder="Q." focus-enter>
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
										<th>ID Caja</th>
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
										<td class="text-right">
											<strong>{{mov.monto | number:2}}</strong>
										</td>
										<td>{{mov.motivo}}</td>
										<td>{{mov.usuarioCaja}}</td>
										<td>
											{{ formatoFecha( mov.fechaRegistro, 'ddd D [de] MMM YYYY HH:mm [horas]' ) }}
										</td>
										<td>{{mov.idCaja}}</td>
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

<!-- DIALOGO CONSULTAR APERTURA/CIERRE CAJA -->
<script type="text/ng-template" id="dial.historialCaja.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-primary">
				<div class="panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-list-alt"></span> <strong>HISTORIAL APERTURA/ CIERRE CAJA</strong>
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
    								<input type="text" class="form-control" ng-model="fechaCaja" ng-change="cargarHistorialCaja( fechaCaja )" data-date-format="dd/MM/yyyy" data-max-date="today" placeholder="dd/mm/yyyy" data-autoclose="1" bs-datepicker>
								</div>
							</div>	
						</div>
						<hr>
						<table class="table table-hover" ng-show="historialCaja.encontrado">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th class="col-sm-2 text-center">Cajero</th>
									<th class="col-sm-2 text-center">Efectivo <br>Inicial</th>
									<th class="col-sm-2 text-center">Efectivo <br>Faltante</th>
									<th class="col-sm-2 text-center" ng-show="<?= $sesion->getIdPerfil() == 1 ? TRUE : FALSE ?>">Efectivo <br>Sobrante</th>
									<th class="col-sm-2 text-center">Efectivo <br>Final</th>
									<th class="col-sm-2 text-center">Estado</th>
									<th class="text-center"></th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="caja in historialCaja.lstCajas" ng-class="{'border-success':inv.importante}">
									<td class="text-right">
										{{ $index + 1 }}
									</td>
									<td>
										{{ caja.usuario }}
									</td>
									<td class="text-right">
										{{ caja.efectivoInicial | number: 2 }}
									</td>
									<td class="text-right" ng-class="{'danger': caja.efectivoFaltante > 0}">
										{{ caja.efectivoFaltante | number: 2 }}
									</td>
									<td class="text-right" ng-class="{'success': caja.efectivoSobrante  > 0}" ng-show="<?= $sesion->getIdPerfil() == 1 ? TRUE : FALSE ?>">
										{{ caja.efectivoSobrante | number: 2 }}
									</td>
									<td class="text-right">
										{{ caja.efectivoFinal | number: 2 }}
									</td>
									<td class="text-center">
										{{ caja.estadoCaja }}
									</td>
									<td class="text-center">
										<label class="label" ng-class="{'label-success': caja.idEstadoCaja == 1, 'label-danger': caja.idEstadoCaja == 2}">
											
											<span class="glyphicon" ng-class="{'glyphicon-folder-open': caja.idEstadoCaja == 1, 'glyphicon-folder-close': caja.idEstadoCaja == 2}""></span>
										</label>
									</td>
								</tr>
							</tbody>
						</table>
						<div ng-show="!historialCaja.encontrado && fechaCaja">
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