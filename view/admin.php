<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="#/" >
	            	<img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	            </a>
	        </div>

			<!-- MENU TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : adminMenu=='usuarios'}" ng-click="resetValores(); adminMenu='usuarios'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> USUARIOS
					</a>
				</li>

			</ul>
					<!-- INGRESO NUEVO PRODUCTO -->
			<div class="tab-content">

				<!--  PRODUCTOS DEL INVENTARIO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : adminMenu=='usuarios'}" ng-show="adminMenu=='usuarios'">
					<button type="button" class="btn btn-sm btn-success" ng-click="agregarUsuario()">
						<span class="glyphicon glyphicon-plus"></span> AGREGAR USUARIO
					</button>
				</div>
			</div>

		</div>

	</div>
</div>


<!-- DIALOGO LST FACTURAS COMPRAS -->
<script type="text/ng-template" id="dial.addUsuario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-edit"></span>
					{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} USUARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="text-right">
								<div class="btn-group" role="group"	>
								  	<button type="button" class="btn btn-default" ng-click="usuario.idEstadoUsuario = estadoUsuario.idEstadoUsuario" ng-repeat="estadoUsuario in lstEstadoUsuario">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'glyphicon-unchecked': usuario.idEstadoUsuario!=estadoUsuario.idEstadoUsuario}"></span>
								  		{{ estadoUsuario.estadoUsuario }}
								  	</button>
								  	<button type="button" class="btn btn-default"></button>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>SELECCIONE NIVEL</label>
								<div>
									<div class="btn-group btn-group-sm" role="group"	>
									  	<button type="button" class="btn btn-default" ng-click="usuario.idNivel = nivel.idNivel" ng-repeat="nivel in lstNiveles">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': usuario.idNivel == nivel.idNivel, 'glyphicon-unchecked': usuario.idNivel != nivel.idNivel}"></span>
									  		{{ nivel.nivel }}
									  	</button>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<label>SELECCIONE PERFIL</label>
								<div>
									<div class="btn-group btn-group-sm" role="group"	>
									  	<button type="button" class="btn btn-default" ng-click="usuario.idPerfil = perfil.idPerfil" ng-repeat="perfil in lstPerfiles">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': usuario.idPerfil == perfil.idPerfil, 'glyphicon-unchecked': usuario.idPerfil != perfil.idPerfil}"></span>
									  		{{ perfil.perfil }}
									  	</button>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-4">
								<label>USUARIO</label>
								<div>
									<input type="text" class="form-control" ng-model="usuario.usuario" maxlength="15" placeholder="Usuario">	
								</div>
							</div>
							<div class="col-sm-4">
								<label>CODIGO</label>
								<div>
									<input type="number" class="form-control" ng-model="usuario.codigo" placeholder="Código" ng-pattern="/^[0-9]+?$/" min="1" max="9999" step="any">	
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>NOMBRES</label>
								<div>
									<input type="text" class="form-control" maxlength="65" ng-model="usuario.nombres" placeholder="Ingrese nombres">	
								</div>
							</div>
							<div class="col-sm-6">
								<label>APELLIDOS</label>
								<div>
									<input type="text" class="form-control" maxlength="65" ng-model="usuario.apellidos" placeholder="Ingrese apellidos">	
								</div>
							</div>
						</div>
					</form>

					idEstadoUsuario : 1,
					idNivel         : 1,
					idPerfil        : 1,
					clave           : '',

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" ng-click="consultaFactura( 'update' )">
						<span class="glyphicon glyphicon-saved"></span> Guardar
					</button>

					<button type="button" class="btn btn-default" ng-click="facturaCompra={};$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>