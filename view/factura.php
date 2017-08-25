<div class="container">
	<div class="row">
		<br>
		<div class="col-sm-10 col-sm-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-list-alt"></span> Facturar
					</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-1">NIT</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" ng-model="$parent.facturaCliente.nit">
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-info" ng-click="$parent.buscarCliente($parent.facturaCliente.nit,2);">
									<span class="glyphicon glyphicon-search"></span>
								</button>
								<button type="button" class="btn btn-primary" ng-click="nuevoCliente($parent.facturaCliente,1);">
									<span class="glyphicon glyphicon-user"></span>
								</button>
								<button type="button" class="btn btn-warning" ng-click="nuevoCliente($parent.facturaCliente.nit,2);">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1">NOMBRE</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" ng-model="$parent.facturaCliente.nombre">
							</div>
							<label class="col-sm-2">DIRECCION</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" ng-model="$parent.facturaCliente.direccion">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1">Ticket</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" ng-model="ticket">
							</div>
							<button type="button" class="btn btn-primary" ng-click="detalleTicket(ticket)">Agregar</button>
						</div>
					</form>
					<!-- tabla de datos según ticket -->
					<table class="table table-hover">
						<thead>
							<tr>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
							</tr>
						</tbody>
					</table>
					<button type="button" class="btn btn-info">Descripción Personalizada</button>
					<!-- tabla de descripción personalizada -->
					<table class="table table-hover">
						<thead>
							<tr>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
							</tr>
						</tbody>
					</table>
					<div class="text-center">
						<button type="button" class="btn btn-success">Facturar</button>
						<button type="button" class="btn btn-info">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- DIALOGO NUEVO CLIENTE -->
<script type="text/ng-template" id="dial.nuevo.cliente.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-plus"></span> NUEVO CLIENTE
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-sm-12">
	                		<form class="form-horizontal" autocomplete="off" novalidate>
								<div class="form-group">
									<div class="pull-right">
										<label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}" style="font-size: 15px;">
											{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} CLIENTE
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nit</label>
									<div class="col-sm-3">
										<input type="text" ng-model="$parent.cliente.nit" class="form-control" id="nit" maxlength="15" autofocus>
									</div> 
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nombre</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65" capitalize>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Cui (DPI)</label>
									<div class="col-sm-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="13" ng-model="$parent.cliente.cui" capitalize>
									</div>
									<label class="col-sm-2">Correo</label>
									<div class="col-sm-3">
										<input type="email" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo" capitalize>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Telefono</label>
									<div class="col-sm-3">
										<input type="number" class="form-control"  max="99999999" ng-model="$parent.cliente.telefono" capitalize>
									</div>
									<label class="col-sm-2">Tipo Cliente</label>
									<div class="col-sm-4">
										<div class="btn-group" role="group" aria-label="">
										  	<button type="button" class="btn btn-default" ng-repeat="tc in lstTipoCliente" ng-click="$parent.cliente.idTipoCliente = tc.idTipoCliente" ng-class="{'btn-warning': tc.idTipoCliente == $parent.cliente.idTipoCliente}">
										  		<span class="glyphicon" ng-class="{'glyphicon-check': tc.idTipoCliente == $parent.cliente.idTipoCliente, 'glyphicon-unchecked': tc.idTipoCliente != $parent.cliente.idTipoCliente}"></span>
										  		{{ tc.tipoCliente }}
										  	</button>
										</div>
									</div>	
								</div>
								<div class="form-group">
									<label class="col-sm-2">Dirección</label>
									<div class="col-sm-8">
										<input type="text" class="form-control"  maxlength="95" ng-model="$parent.cliente.direccion" capitalize>
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button type="button" class="btn btn-success" ng-click="$parent.guardarCliente()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} cliente
									</button>
									<button type="button" class="btn btn-default" ng-click="$parent.resetValores( 'cliente' )"> 
										<span class="glyphicon glyphicon-log-out"></span> Cancelar
									</button>
								</div>
							</form>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="$parent.guardarCliente()">
                        <span class="glyphicon glyphicon-ok"></span>
                        <b>Guardar</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide();$parent.cancelarCliente();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 