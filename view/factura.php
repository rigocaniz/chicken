<div class="container">
	<div class="row">
		<br>
		<div class="col-sm-10 col-sm-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Facturar</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<lable class="col-sm-1">NIT</lable>
							<div class="col-sm-4">
								<input type="text" class="form-control">
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-info" ng-click="$parent.buscarCliente(modelo,2);">
									<span class="glyphicon glyphicon-search"></span>
								</button>
								<button type="button" class="btn btn-primary" ng-click="nuevoCliente();">
									<span class="glyphicon glyphicon-user"></span>
								</button>
							</div>
						</div>
						<div class="form-group">
							<lable class="col-sm-1">NOMBRE</lable>
							<div class="col-sm-4">
								<input type="text" class="form-control">
							</div>
							<lable class="col-sm-2">DIRECCION</lable>
							<div class="col-sm-5">
								<input type="text" class="form-control">
							</div>
						</div>
					</form>
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
                	<span class="glyphicon glyphicon-plus"></span>
                    Nuevo cliente
                </div>
                <div class="modal-body">
                	<div class="container">
	                	<div class="row">
	                		<form class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-2">Nit</label>
									<div class="col-sm-2 has-success">
										<input type="text"  ng-model="$parent.cliente.nit" class="form-control"  maxlength="15" autofocus>
									</div> 
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nombre</label>
									<div class="col-sm-6 has-success">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65">
									</div>
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Cui(DPI)</label>
									<div class="col-sm-2">
										<input type="number" class="form-control" max="9999999999999" ng-model="$parent.cliente.cui">
									</div>
									<label class="col-sm-1">Correo</label>
									<div class="col-sm-3">
										<input type="text" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo">
									</div>
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Telefono</label>
									<div class="col-sm-2">
										<input type="number" class="form-control"  max="99999999" ng-model="$parent.cliente.telefono">
									</div>
									<label class="col-sm-1">Tipo Cliente</label>
									<div class="col-sm-3">
										<select class="form-control" ng-model="$parent.cliente.idTipoCliente">
											<option ng-repeat="tc in lstTipoCliente" value="{{tc.idTipoCliente}}">{{tc.tipoCliente}}</option>
										</select>
									</div>	
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Dirección</label>
									<div class="col-sm-6">
										<input type="text" class="form-control"  maxlength="95" ng-model="$parent.cliente.direccion">
									</div>
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