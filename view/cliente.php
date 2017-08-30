<div class="contenedor">
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
				<li role="presentation" ng-class="{'active' : menuCliente=='ingresar'}" ng-click="menuCliente='ingresar';">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> NUEVO CLIENTE
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : menuCliente=='clientes'}" ng-click="menuCliente='clientes'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> CLIENTES
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!-- BUSCAR CLIENTE -->
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="row">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">BUSCAR CLIENTE</label>
							<div class="col-xs-9 col-sm-6 col-md-5">
								<input type="text" class="form-control" ng-model="txtCliente" ng-keypress="$event.keyCode == 13 && buscarCliente( txtCliente, 'principal' )" placeholder="NIT / DPI / NOMBRE">
							</div>
							<div class="col-xs-3 col-sm-3 col-md-2">
								<button type="button" class="btn btn-sm btn-primary" ng-click="buscarCliente( txtCliente, 'principal' )">
									<span class="glyphicon glyphicon-search"></span> Buscar
								</button>
							</div>
						</div>
					</div>
				</div>

				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuCliente=='ingresar'}" ng-show="menuCliente=='ingresar'">
					<div class="panel panel-primary">
						<div class="panel-body">
							<form class="form-horizontal" autocomplete="off" novalidate>
								<div class="form-group">
									<label class="col-sm-2 control-label">NIT</label>
									<div class="col-sm-3">
										<input type="text" ng-model="cliente.nit" class="form-control" id="nit" maxlength="15" autofocus>
									</div>
									<div class="col-sm-6">
										<div class="text-right">
											<label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}" style="font-size: 14px;">
												{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} CLIENTE
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Nombre</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control" ng-model="cliente.nombre" maxlength="65">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Cui (DPI)</label>
									<div class="col-sm-4 col-md-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="13" ng-model="cliente.cui">
									</div>
									<label class="col-sm-2 control-label">Correo</label>
									<div class="col-sm-4 col-md-3">
										<input type="email" class="form-control"  maxlength="65" ng-model="cliente.correo">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Telefono</label>
									<div class="col-sm-4 col-md-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="8" ng-model="cliente.telefono">
									</div>
									<label class="col-sm-2 control-label">Tipo Cliente</label>
									<div class="col-sm-4">
										<div class="btn-group" role="group" aria-label="">
										  	<button type="button" class="btn btn-default" ng-repeat="tc in lstTipoCliente" ng-click="cliente.idTipoCliente = tc.idTipoCliente" ng-class="{'btn-warning': tc.idTipoCliente == cliente.idTipoCliente}">
										  		<span class="glyphicon" ng-class="{'glyphicon-check': tc.idTipoCliente == cliente.idTipoCliente, 'glyphicon-unchecked': tc.idTipoCliente != cliente.idTipoCliente}"></span>
										  		{{ tc.tipoCliente }}
										  	</button>
										</div>
									</div>	
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Dirección</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control"  maxlength="95" ng-model="cliente.direccion" ng-keypress="$event.keyCode==13 && consultaCliente()">
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button type="button" class="btn btn-success" ng-click="consultaCliente()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} cliente
									</button>
									<button type="button" class="btn btn-default" ng-click="resetValores( 'cliente' )"> 
										<span class="glyphicon glyphicon-log-out"></span> Cancelar
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuCliente=='clientes'}" ng-show="menuCliente=='clientes'">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</div>
			</div>
		</div>
	</div>
</div>



<!-- DIALOGO BUSCAR / LISTAR CLIENTES -->
<script type="text/ng-template" id="dial.buscarCliente.html">
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading text-center">
                    <button type="button" class="close" ng-click="resetValores( 'lstClientes' ); $hide();">&times;</button>
                    <span class="glyphicon glyphicon-user"></span>
                    LISTADO DE CLIENTES
                </div>
                <div class="modal-body">
                    <!-- BUSCAR CLIENTE -->
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <label class="col-xs-12 col-sm-3 col-md-2">BUSCAR CLIENTE</label>
                                <div class="col-xs-9 col-sm-6 col-md-5">
                                    <input type="text" class="form-control" id="buscador" ng-model="$parent.txtCliente" ng-keypress="$event.keyCode == 13 && buscarCliente( $parent.txtCliente )" ng-change="$parent.lstClientes=[]" placeholder="NIT / DPI / NOMBRE">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-2">
                                    <button class="btn btn-sm btn-primary" ng-click="buscarCliente( txtCliente, 1 )">
                                        <span class="glyphicon glyphicon-search"></span> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- LST CLIENTES -->
                    <div class="panel-body">
                        <div class="row" ng-show="lstClientes.length >= 5">
                            <div class="col-sm-5 col-md-6 col-lg-7"></div>
                            <div class="col-sm-7 col-md-6 col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </span>
                                    <input type="text" class="form-control" id="buscarCliente" ng-model="filtroCliente" maxlength="75" placeholder="Buscar cliente">
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center col-sm-4">CLIENTE</th>
                                    <th class="text-center col-sm-2">NIT</th>
                                    <th class="text-center col-sm-2 ">DPI</th>
                                    <th class="text-center col-sm-4">DIRECCION</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="cliente in lstClientes | filter: filtroCliente" ng-class="{'border-info': cliente.idTipoCliente == 1, 'border-warning': cliente.idTipoCliente == 2}" ng-show="cliente.idCliente > 1">
                                    <td>{{ cliente.nombre}}</td>
                                    <td class="text-center">{{ cliente.nit }}</td>
                                    <td class="text-center">{{ cliente.cui }}</td>
                                    <td>{{ cliente.direccion }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" ng-click="seleccionarCliente( cliente )">
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="resetValores( 'lstClientes' ); $hide();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>