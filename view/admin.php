<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 9 ) ):
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

			<!-- MENU TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : adminMenu=='usuarios'}" ng-click="resetValores(); adminMenu='usuarios'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> USUARIOS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : adminMenu=='perfiles'}" ng-click="resetValores(); adminMenu='perfiles'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-briefcase"></span> PERFILES
					</a>
				</li>

			</ul>
			<!-- INGRESO NUEVO PRODUCTO -->
			<div class="tab-content">

				<!--  PANEL USUARIOS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : adminMenu=='usuarios'}" ng-show="adminMenu=='usuarios'">
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
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="glyphicon glyphicon-th-list"></span>
								<STRONG>LISTA DE USUARIOS</STRONG>
							</div>
							<div class="panel-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center">No.</th>
											<th class="text-center col-sm-2">Nombres</th>
											<th class="text-center col-sm-2">Apellidos</th>
											<th class="text-center col-sm-2">Usuario</th>
											<th class="text-center col-sm-2">Código</th>
											<th class="text-center col-sm-2">Perfil</th>
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
											<td class="text-center">{{ usuario.perfil }}</td>
											<td class="text-center">{{ usuario.estadoUsuario }}</td>
											<td class="text-center">
												<div class="menu-contenedor">
													<button type="button" class="btn btn-warning noBorde">
														<span class="glyphicon glyphicon-th"></span>
													</button>
													<div class="menu-horizontal">
														<button type="button" class="btn" ng-click="cambiarEstadoUsuario( usuario )" title="Cambiar Estado del Usuario" data-toggle="tooltip" data-placement="top" tooltip>
															<span class="glyphicon glyphicon-flag"></span>
														</button>
														<button type="button" class="btn" ng-click="editarUsuario( usuario )" title="Editar Usuario" data-toggle="tooltip" data-placement="top" tooltip>
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

				<!--  PANEL PERFILES -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : adminMenu=='perfiles'}" ng-show="adminMenu=='perfiles'">
					<div class="col-sm-offset-2 col-sm-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="glyphicon glyphicon-th-list"></span>
								<STRONG>LISTA DE USUARIOS</STRONG>
							</div>
							<div class="panel-body">
								<button type="button" class="btn btn-sm btn-success" ng-click="agregarPerfil()">
									<span class="glyphicon glyphicon-plus"></span> AGREGAR PERFILES
								</button>
								<br><br>
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center col-sm-1">No.</th>
											<th class="text-center col-sm-3">PERFIL</th>
											<th class="text-center col-sm-1"></th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="perfil in lstPerfiles">
											<td>{{ $index + 1 }}</td>
											<td>{{ perfil.perfil }}</td>
											<td class="text-center">
												<div class="menu-contenedor">
													<button type="button" class="btn btn-warning noBorde">
														<span class="glyphicon glyphicon-th"></span>
													</button>
													<div class="menu-horizontal">
														<button type="button" class="btn" ng-click="editarPerfil( perfil )" title="Editar Perfil" data-toggle="tooltip" data-placement="top" tooltip>
															<span class="glyphicon glyphicon-pencil"></span>
														</button>
														<button type="button" class="btn" ng-click="datosPerfil( perfil )" title="Asignar Módulos" data-toggle="tooltip" data-placement="top" tooltip>
															<span class="glyphicon glyphicon-th-large"></span>
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

	</div>
</div>

<!-- DIALOGO MODULOS PERFIL -->
<script type="text/ng-template" id="dial.modulosPerfil.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-info">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-th-large"></span>
					MODULOS DEL PERFIL
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="col-sm-12">
								<label>
									<span class="glyphicon glyphicon-briefcase"></span>
									PERFIL
								</label>
								{{ dataPerfil.perfil | uppercase }}
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4" ng-repeat="modulo in dataPerfil.lstModulosPerfil">
								<button type="button" class="btn btn-sm" ng-class="{'btn-success': modulo.asignado, 'btn-default': modulo.asignado}" ng-click="asignarModulo( dataPerfil.idPerfil, modulo.idModulo, modulo.asignado );">
									<span class="glyphicon" ng-class="{'glyphicon-check': modulo.asignado, 'glyphicon-unchecked': !modulo.asignado}"></span>
								</button>
								{{ modulo.modulo }}
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


<!-- DIALOGO CAMBIAR ESTADO USUARIO -->
<script type="text/ng-template" id="dial.cambiarEstado.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" ng-class="{'panel-success': user.idEstadoUsuario == 1, 'panel-danger': user.idEstadoUsuario == 2, 'panel-warning': user.idEstadoUsuario == 3}">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="resetValores( 'usuario' );$hide()">&times;</button>
					<span class="glyphicon glyphicon-user"></span>
					CAMBIAR ESTADO DEL USUARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="col-sm-4">
								<label>USUARIO</label>
								<div>
									{{ user.usuario }}
								</div>
							</div>
							<div class="col-sm-3">
								<label>CODIGO</label>
								<div>
									{{ user.codigo }}
								</div>
							</div>
							<div class="col-sm-3">
								<label>PERFIL</label>
								<div>
									{{ user.perfil }}
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6">
								<label>NOMBRES</label>
								<div>
									{{ user.nombres }}
								</div>
							</div>
							<div class="col-sm-6">
								<label>APELLIDOS</label>
								<div>
									{{ user.apellidos }}
								</div>
							</div>
						</div>
						<br>
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<label>SELECCIONE EL ESTADO DEL USUARIO</label>
								<br>
								<div class="btn-group btn-group-sm" role="group">
								  	<button type="button" class="btn btn-default" ng-class="{'btn-success': user.idEstadoUsuario==1 && user.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'btn-danger': user.idEstadoUsuario==2 && user.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'btn-warning': user.idEstadoUsuario==3 && user.idEstadoUsuario==estadoUsuario.idEstadoUsuario}" ng-repeat="estadoUsuario in lstEstadoUsuario" ng-click="user.idEstadoUsuario = estadoUsuario.idEstadoUsuario">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': user.idEstadoUsuario==estadoUsuario.idEstadoUsuario, 'glyphicon-unchecked': user.idEstadoUsuario!=estadoUsuario.idEstadoUsuario}"></span>
								  		{{ estadoUsuario.estadoUsuario | uppercase }}
								  	</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" ng-click="actualizarEstadoUsuario()" ng-disabled="loading">
						<span class="glyphicon glyphicon-saved"></span> Cambiar Estado
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



<!-- DIALOGO ADMINISTRACIÓN USUARIO => INSERT / UPDATE -->
<script type="text/ng-template" id="dial.adminUsuario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
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
							<div class="col-sm-10">
								<label>SELECCIONE PERFIL</label>
								<div class="btn-group btn-group-sm" role="group"	>
								  	<button type="button" class="btn btn-default" ng-click="usuario.idPerfil = perfil.idPerfil" ng-repeat="perfil in lstPerfiles">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': usuario.idPerfil == perfil.idPerfil, 'glyphicon-unchecked': usuario.idPerfil != perfil.idPerfil}"></span>
								  		{{ perfil.perfil }}
								  	</button>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">USUARIO</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" ng-model="usuario.usuario" maxlength="15" placeholder="Usuario" ng-pattern="/^[a-zA-Z0-9]*$/" ng-trim="false">	
							</div>
							<label class="col-sm-2 control-label">CÓDIGO</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" ng-model="usuario.codigo" placeholder="Código" ng-pattern="/^[0-9]+?$/" minlength="1" maxlength="4" ng-trim="false" required>
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
						<div class="form-group">
							<div class="col-sm-6">
								<label>DESTINO</label>
								<div>
									<select ng-model="usuario.idDestinoMenu" class="form-control">
										<option value="1">Cocina</option>
										<option value="2">Barra</option>
									</select>
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