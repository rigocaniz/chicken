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
			<!--
			{{ lstUsuarios	| json }}
	-->
					<button type="button" class="btn btn-sm btn-success" ng-click="agregarUsuario()">
						<span class="glyphicon glyphicon-plus"></span> AGREGAR USUARIO
					</button>

					<div>
						<div class="text-right">
							<strong>CONSULTAR</strong>
							<div class="btn-group btn-group-sm" role="group" aria-label="...">
							  	<button type="button" class="btn btn-default" ng-click="filtroUsuario='todos'">
							  		<span class="glyphicon" ng-class="{'glyphicon-check': filtroUsuario=='todos', 'glyphicon-unchecked': filtroUsuario!='todos'}"></span> TODOS
							  	</button>
							  	<button type="button" class="btn btn-default" ng-click="filtroUsuario='activos'">
							  		<span class="glyphicon" ng-class="{'glyphicon-check': filtroUsuario=='activos', 'glyphicon-unchecked': filtroUsuario!='activos'}"></span> Activos
							  	</button>
							  	<button type="button" class="btn btn-default" ng-click="filtroUsuario='bloqueados'">
							  		<span class="glyphicon" ng-class="{'glyphicon-check': filtroUsuario=='bloqueados', 'glyphicon-unchecked': filtroUsuario!='bloqueados'}"></span> Bloqueados
							  	</button>
							  	<button type="button" class="btn btn-default" ng-click="filtroUsuario='deshabilitados'">
							  		<span class="glyphicon" ng-class="{'glyphicon-check': filtroUsuario=='deshabilitados', 'glyphicon-unchecked': filtroUsuario!='deshabilitados'}"></span> Deshabilitados
							  	</button>
							</div>
						</div>
						<br>
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="text-center col-sm-1">No.</th>
									<th class="text-center col-sm-2">Nombres</th>
									<th class="text-center col-sm-2">Apellidos</th>
									<th class="text-center col-sm-2">Usuario</th>
									<th class="text-center col-sm-2">Código</th>
									<th class="text-center col-sm-2">Nivel</th>
									<th class="text-center col-sm-2">Estado</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="usuario in lstUsuarios" ng-class="{'border-success':usuario.idEstadoUsuario == 1, 'border-danger':usuario.idEstadoUsuario == 2, 'border-warning':usuario.idEstadoUsuario == 3}">
									<td>{{ $index + 1 }}</td>
									<td>{{ usuario.nombres }}</td>
									<td>{{ usuario.apellidos }}</td>
									<td class="text-center">{{ usuario.usuario }}</td>
									<td class="text-center">{{ usuario.codigo }}</td>
									<td class="text-center">{{ usuario.nivel }}</td>
									<td class="text-center">{{ usuario.estadoUsuario }}</td>
									<td class="text-center">
										<div class="menu-contenedor">
											<button type="button" class="btn btn-warning noBorde">
												<span class="glyphicon glyphicon-th"></span>
											</button>
											<div class="menu-horizontal">
												<button type="button" class="btn" ng-click="editarUsuario( usuario )" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
												<button type="button" class="btn" ng-click="resetearClave( usuario.usuario )" title="Resetear Clave" data-toggle="tooltip" data-placement="top" tooltip>
													<span class="glyphicon glyphicon-lock"></span>
												</button>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>


<!-- DIALOGO ADMINISTRACIÓN USUARIO => INSERT / UPDATE -->
<script type="text/ng-template" id="dial.adminUsuario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" ng-class="{'panel-success': accion == 'insert', 'panel-info': accion == 'update'}">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="resetValores( 'usuario' );$hide()">&times;</button>
					<span class="glyphicon glyphicon-user"></span>
					{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} USUARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="col-sm-12 text-right">
								<label>ESTADO</label>
								<div class="btn-group btn-group-sm" role="group"	>
								  	<button type="button" class="btn btn-default" ng-class="{'btn-success': usuario.idEstadoUsuario==1 && usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'btn-danger': usuario.idEstadoUsuario==2 && usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'btn-warning': usuario.idEstadoUsuario==3 && usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario}" ng-repeat="estadoUsuario in lstEstadoUsuario" ng-show="usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': usuario.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'glyphicon-unchecked': usuario.idEstadoUsuario!=estadoUsuario.idEstadoUsuario}"></span>
								  		{{ estadoUsuario.estadoUsuario }}
								  	</button>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-5">
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
							<div class="col-sm-7">
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
									<input type="text" class="form-control" ng-model="usuario.usuario" maxlength="15" placeholder="Usuario" ng-pattern="/^[a-zA-Z0-9]*$/" ng-trim="false">	
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" ng-click="consultaUsuario()" ng-disabled="loading">
						<span class="glyphicon glyphicon-saved"></span> Guardar
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 'usuario' );$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>