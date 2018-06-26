<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 9 ) AND $sesion->getIdPerfil() != 1 ):
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
				<li role="presentation" ng-class="{'active' : adminMenu=='ajustes'}" ng-click="adminMenu='ajustes'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-cog"></span> AJUSTES
					</a>
				</li>
			</ul>
			<div class="tab-content">

				<!--  PANEL USUARIOS -->
				<div role="tabpanel" class="tab-pane active" ng-show="adminMenu=='usuarios'">
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
				<div role="tabpanel" class="tab-pane active" ng-show="adminMenu=='perfiles'">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">PERFILES</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-xs-1 col-sm-2 col-md-2 control-label">Perfil</label>
									<div class="col-xs-6 col-sm-6">
										<input type="text" id="medida" ng-keyup="$event.keyCode == 13 && consultaPerfil()" class="form-control" ng-model="perfil.perfil" maxlength="45" ng-disabled="loading">
									</div>
									<div class="col-xs-5 col-sm-4 col-md-4">
										<button type="button" class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaPerfil()" ng-disabled="loading">
											<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} (F6)
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 'perfil' )">
											Cancelar
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="glyphicon glyphicon-th-list"></span>
								<STRONG>LISTA DE USUARIOS</STRONG>
							</div>
							<div class="panel-body">

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

				<!--  PANEL AJUSTES -->
				<div role="tabpanel" class="tab-pane active" ng-show="adminMenu=='ajustes'">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">No. de Grupos de Cocina <span class="glyphicon glyphicon-cutlery"></span></h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-xs-1 col-sm-2 col-md-2 control-label"># Grupos</label>
									<div class="col-xs-6 col-sm-6">
										<input type="text" class="form-control" ng-model="parametro.valor">
										<kbd>{{parametro.parametro}}</kbd>
									</div>
									<div class="col-xs-5 col-sm-4 col-md-4">
										<button type="button" class="btn btn-success" ng-click="guardarParametro()">
											<span class="glyphicon glyphicon-ok"></span> Guardar
										</button>
									</div>
								</div>
								<div class="col-sm-12" style="margin-top:7px">
									<mark>Configuración para definir <b>Número de grupos</b> donde se <b>distribuirá</b> las ordenes de clientes en cocina</mark>
								</div>
							</div>
						</div>
					</div>
					<!-- AJUSTE IMPRESION -->
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4 class="panel-title">Ajuste de Página <span class="glyphicon glyphicon-print"></span></h4>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label class="col-sm-2"><b>Documento</b></label>
									<div class="col-sm-4">
										<select ng-model="idDocumento" class="form-control">
											<option value="{{doc.idDocumento}}" ng-repeat="doc in catDocumentos">{{doc.documento}}</option>
										</select>
									</div>
								</div>
								<!-- CAMPOS DE DOCUMENTO -->
								<div class="form-group">
									<div class="col-sm-12">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>Campo</th>
													<th>T. Fuente</th>
													<th>Mostrar Tit.</th>
													<th>Posición X</th>
													<th>Posición Y</th>
												</tr>
											</thead>
											<tbody ng-repeat="(ixCmp, cmp) in documento">
												<tr>
													<td><b>{{cmp.label}}</b></td>
													<td>
														<input type="number" ng-min="1" min="1" ng-model="cmp.fontSize" style="width:80px">
													</td>
													<td>
														<button type="button" class="btn btn-sm" 
															ng-class="{'btn-default':!cmp.mostrarTitulo, 'btn-info':cmp.mostrarTitulo}"
															ng-click="cmp.mostrarTitulo=!cmp.mostrarTitulo">
															{{cmp.mostrarTitulo?"SI":"NO"}}
														</button>
													</td>
													<td>
														<input type="number" ng-min="1" min="1" ng-model="cmp.x" style="width:80px">
													</td>
													<td>
														<input type="number" ng-min="1" min="1" ng-model="cmp.y" style="width:80px">
													</td>
												</tr>
												<tr ng-show="cmp.encabezado.length">
													<td></td>
													<td colspan="4">
														<table class="table table-hover">
															<thead>
																<tr>
																	<th ng-repeat="item in cmp.encabezado">{{item.campo}}</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td ng-repeat="item in cmp.encabezado">
																		<input type="number" ng-min="1" min="1" ng-model="item.width" style="width:70px">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<!-- CAMPOS DE DOCUMENTO -->
								<!-- VISTA PREVIA -->
								<div class="form-group">
									<div class="col-sm-6">
										<button type="button" class="btn btn-sm btn-default" ng-click="showView=!showView" ng-init="showView=true">
											<b><span class="glyphicon glyphicon-eye-open"></span> <i>Vista Previa</i></b>
										</button>
									</div>
									<div class="col-sm-6 text-right">
										<button type="button" class="btn btn-sm btn-success" ng-click="guardarDocumento()">
											<span class="glyphicon glyphicon-ok"></span>
											<b>Guardar Cambios Documento</b>
										</button>
									</div>
									<div class="col-sm-12" style="height:300px;border: solid 1px #ccc;margin-top:7px" ng-show="showView">
										<span ng-repeat="(ixCmp, cmp) in documento" 
											style="display: block;position: absolute;left:{{cmp.x+'px'}};top:{{cmp.y+'px'}};font-size:{{cmp.fontSize+'px'}};">
											<b ng-show="cmp.idTipoItem==1">{{cmp.label}}</b>
											<b ng-repeat="col in cmp.encabezado" style="display: inline-block;width:{{col.width+'px'}};border:solid 1px #1085e4">{{col.campo}}</b>
										</span>
									</div>
								</div>
								<!-- VISTA PREVIA -->
							</div>
						</div>
					</div>
					<!-- AJUSTE IMPRESION -->
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