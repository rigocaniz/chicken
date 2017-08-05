<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : inventarioMenu==1}" ng-click="resetValores(); inventarioMenu=1">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> MENUS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu==2}" ng-click="resetValores(); inventarioMenu=2">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list-alt"></span> COMBOS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu==3}" ng-click="resetValores(); inventarioMenu=3">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-book"></span> RECETAS
					</a>
				</li>
			</ul>

			<!-- TAB PANELES -->
			<div class="tab-content">
				<!--  PRODUCTOS DEL INVENTARIO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==1}" ng-show="inventarioMenu==1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">LISTADO DE MENUS</h3>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<p>
									<button type="button" class="btn btn-success btn-sm" ng-click="agregarMenuCombo( 'menu' )">
										<span class="glyphicon glyphicon-plus"></span> INGRESAR MENU
									</button>
								</p>
							</div>
							<div class="row">
							  	<div class="col-sm-3" ng-repeat="m in lstMenu">
							    	<div class="thumbnail">
								    	<span class="label label-info">{{ m.destinoMenu }}</span>

								      	<img ng-src="{{ m.imagen }}" alt="{{ m.menu }}" ng-click="asignarValorImagen( m.idMenu, 'menu' )">
								      	<div class="caption">
								        	<p>
								        		<strong>{{ m.menu | uppercase }}</strong> 
								        		<span class="label label-success">{{ m.estadoMenu }}</span>
								        	</p>
								        	<p>
								        		{{ m.descripcion }}
								        	</p>
								        	<hr>
								        	<p>
								        		<a ng-href="#/nuevoEdita/menu/{{m.idMenu}}" type="button" class="btn btn-primary btn-sm">
													<span class="glyphicon glyphicon-edit"></span> Editar
												</a>
												<a href="#" type="button" class="btn btn-info btn-sm">
													<span class="glyphicon glyphicon-list"></span> Receta
												</a>
											</p>
											<div class="btn-group" role="group" aria-label="...">
											  	<button type="button" class="btn btn-default">1</button>
											  	<div class="btn-group" role="group">
											    	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											      		Opciones <span class="caret"></span>
											    	</button>
											    	<ul class="dropdown-menu">
											      		<li><a href="#">Dropdown link</a></li>
											      		<li><a href="#">Dropdown link</a></li>
											    	</ul>
											  	</div>
											</div>
											<div class="btn-group-vertical" role="group" aria-label="...">
											  	<button type="button" class="btn btn-default">
											  		<span class="glyphicon glyphicon-edit"></span> Editar
											  	</button>
											  	<button type="button" class="btn btn-default">
											  		<span class="glyphicon glyphicon-th-list"></span> Receta
											  	</button>
											  	<button type="button" class="btn btn-default">
											  		<span class="glyphicon glyphicon-list-alt"></span> Precios
											  	</button>
											</div>
								      	</div>
							    	</div>
							  	</div>
								<!-- PAGINADOR -->
								<nav>
									<ul class="pagination" ng-show="lstPaginacion.length > 1">
										<li ng-class="{disabled: filter.pagina == 1 }">
											<a href="" ng-click="cargarPaginacion( 1 );" aria-label="Previous">
												<span aria-hidden="true">&laquo;</span>
											</a>
										</li>
										<li ng-repeat="(ixPagina, pagina) in lstPaginacion" ng-class="{'active': filter.pagina == pagina.noPagina}">
											<a href="" ng-click="cargarPaginacion( pagina.noPagina );">
												{{ pagina.noPagina }}
											</a>
										</li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>

				<!-- COMBOS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==2}" ng-show="inventarioMenu==2">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">MENUS</h3>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<p>
									<button type="button" class="btn btn-success btn-sm" ng-click="agregarMenuCombo( 'combo' )">
										<span class="glyphicon glyphicon-plus"></span> INGRESAR COMBO
									</button>
								</p>
							</div>
							<div class="row">
								<br>
							  	<div class="col-xs-6 col-sm-4 col-md-3" ng-repeat="c in lstCombos">
							    	<div class="thumbnail">
							      		<img ng-src="{{ c.imagen }}" class="img-thumbnail" ng-click="asignarValorImagen( c.idCombo, 'combo' )">
							      		<div class="caption">
							        		<h4>
							        			<strong>{{ c.combo }}</strong> 
							        		</h4> 
							        		Estado <span class="label label-success">{{c.estadoMenu}}</span>
							        		<p>{{c.descripcion}}</p>
							        		<p>
							        			<a ng-href="#/nuevoEdita/combo/{{c.idCombo}}" type="button" class="btn btn-primary btn-sm">
													<span class="glyphicon glyphicon-edit"></span> Editar
												</a>
											</p>
							      		</div>
							    	</div>
							  	</div>
							</div>
						</div>
					</div>
				</div>

				<!-- MEDIDA DE PRODUCTO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==3}" ng-show="inventarioMenu==3">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">MEDIDA DE PRODUCTO</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-sm-1 col-md-2">Medida</label>
									<div class="col-sm-6">
										<input type="text" id="medida" ng-keyup="$event.keyCode == 13 && consultaMedida()" class="form-control" ng-model="medidaProd.medida" maxlength="45">
									</div>
									<div class="col-sm-5 col-md-4">
										<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaMedida()">
											<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 6 )">
											Cancelar
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<h4 class="panel-title">
									<span class="glyphicon glyphicon-sort-by-attributes"></span> MEDIDAS REGISTRADOS
								</h4>
								<br>
								<div class="col-sm-4 col-md-6">
								</div>
								<div class="col-sm-8 col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" ng-model="buscarMedida" placeholder="Buscar medida">
										<span class="input-group-addon" id="basic-addon1">
											<span class="glyphicon glyphicon-search"></span> BUSCAR
										</span>
									</div>
								</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center col-sm-1">No.</th>
											<th class="text-center col-sm-8">Tipo de producto</th>
											<th class="text-center col-sm-2">Editar</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="medida in lstResultMedida = (lstMedidas | filter: buscarMedida)">
											<td class="text-center">{{ $index + 1 }}</td>
											<td>{{ medida.medida }}</td>
											<td class="text-center">
												<button class="btn btn-info btn-sm" ng-click="editarMedida( medida )">
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="alert alert-info text-right" role="alert" ng-show="!lstResultMedida.length">
									<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

	
<!-- DIALOGO INGRESO EXISTENCIA -->
<script type="text/ng-template" id="dial.ingreso.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content panel-warning">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-repeat"></span>
					INGRESAR REAJUSTE DEL PRODUCTO
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="pull-right">
								<b>PRODUCTO: </b>
								<label class="label label-warning">
									{{ itemProducto.nombreProducto | uppercase }}
								</label>
							</div>
							<hr>
							<form class="form-horizontal" role="form" name="$parent.formReajuste">
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Disponible</label>
										<input type="text" class="form-control" ng-model="itemProducto.disponibilidad" readonly>
									</div>
									<div class="col-sm-4">
									</div>
									<div class="col-sm-4">
										<label class="control-label">ID PRODUCTO</label>
										<input type="text" class="form-control" ng-model="itemProducto.idProducto" readonly>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Ingresar Cantidad</label>
										<input type="number" min="0" class="form-control" placeholder="Cantidad" ng-model="itemProducto.cantidad">
									</div>
									<div class="col-sm-2">
									</div>
									<div class="col-sm-5">
										<label class="control-label">Nueva Disponibilidad</label>
										<input type="text" class="form-control" placeholder="Cantidad" value="{{ retornarTotal() }}" readonly>											  	
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-2">
										<label class="control-label">DISPONIBILIDAD</label>
									</div>
									<div class="col-sm-10 text-center">
										<button type="button" class="btn btn-default" ng-class="{'btn-success': itemProducto.esIncremento}" ng-click="itemProducto.esIncremento=true">
											<span class="glyphicon" ng-class="{'glyphicon-check': itemProducto.esIncremento, 'glyphicon-unchecked': !itemProducto.esIncremento}"></span> AGREGAR
										</button>

										<button type="button" class="btn btn-default" ng-class="{'btn-danger': !itemProducto.esIncremento}" ng-click="itemProducto.esIncremento=false">
											<span class="glyphicon" ng-class="{'glyphicon-unchecked': itemProducto.esIncremento, 'glyphicon-check': !itemProducto.esIncremento}"></span> DISMINUIR
										</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label">INGRESE OBSERVACIÓN</label>
										<textarea rows="3" class="form-control" ng-model="itemProducto.observacion" placeholder="Ingrese la observación del reajuste"></textarea>
										<label class="label label-info">
											Caracteres <span class="badge">{{ itemProducto.observacion.length }}</span>
										</label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" ng-click="consultaReajusteInventario()">
						<span class="glyphicon glyphicon-saved"></span> Guardar
					</button>
					<button type="button" class="btn btn-default" ng-click="$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- MODAL AGREGAR / EDITAR MENU -->
<script type="text/ng-template" id="dial.adminMenu.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" ng-class="{'panel-warning': accion == 'insert', 'panel-info': accion == 'update'}">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
						{{ accion == 'insert' ? 'INGRESAR NUEVO' : 'ACTUALIZAR' }} MENU
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend">DATOS</legend>
						<!-- FORMULARIO MENU -->
						<form class="form-horizontal" role="form" name="formMenu">
							<div class="form-group" ng-show="accion == 'update'">
								<label class="col-sm-2" ng-show="id>0">Estado Menu</label>
								<div class="col-sm-2" ng-show="id>0">
									<select class="form-control" ng-model="menu.idEstadoMenu" >
										<option ng-repeat="e in lstEstadoMenu" value="{{e.idEstadoMenu}}">{{e.estadoMenu}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-7 col-sm-7 col-md-8">
									<label class="control-label">NOMBRE DEL MENU</label>
									<input type="text" class="form-control" ng-model="menu.menu" maxlength="45" required>
								</div>
								<div class="col-xs-5 col-sm-5 col-md-4">
									<label class="control-label">DESTINO DEL MENU</label>
									<div>
										<div class="btn-group btn-group-sm" role="group">
										  	<button type="button" class="btn btn-default" ng-class="{'btn-info': dm.idDestinoMenu == menu.idDestinoMenu}" ng-click="menu.idDestinoMenu = dm.idDestinoMenu" ng-repeat="dm in lstDestinoMenu">
										  		<span class="glyphicon" ng-class="{'glyphicon-check': dm.idDestinoMenu == menu.idDestinoMenu, 'glyphicon-unchecked': dm.idDestinoMenu != menu.idDestinoMenu}"></span>
										  		{{ dm.destinoMenu }}
										  	</button>
										</div>
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">TIPO DE MENU: </label>
								<div class="col-sm-7">
									<div class="btn-group btn-group-sm" role="group">
									  	<button type="button" class="btn btn-default" ng-class="{'btn-info': tipoMenu.idTipoMenu == menu.idTipoMenu}" ng-click="menu.idTipoMenu = tipoMenu.idTipoMenu" ng-repeat="tipoMenu in lstTipoMenu">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': tipoMenu.idTipoMenu == menu.idTipoMenu, 'glyphicon-unchecked': tipoMenu.idTipoMenu != menu.idTipoMenu}"></span>
									  		{{ tipoMenu.tipoMenu }}
									  	</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">DESCRIPCIÓN</label>
									<textarea rows="3" class="form-control" placeholder="Ingrese la descripción del menu" ng-model='menu.descripcion' required></textarea>
								</div>
							</div>
							<legend class="text-center">
								<small>
									<i class="fa fa-money" aria-hidden="true"></i> AGREGAR PRECIOS
								</small>
							</legend>
							<div class="form-group">
								<div class="col-xs-5 col-sm-6 col-md-5">
									<label class="control-label">Tipo Servicio</label>
									<select class="form-control" ng-model="precios.idTipoServicio">
										<option value="{{ ts.idTipoServicio }}" ng-repeat="ts in lstTipoServicio">
											{{ ts.tipoServicio }}
										</option>
									</select>
								</div>
								<div class="col-xs-4 col-sm-6 col-md-4">
									<label class="control-label">Precio</label>
									<input type="number" min="0" class="form-control" ng-model="precios.precio">
								</div>
								<div class="col-xs-1 col-sm-2 col-md-2">
									<br>
									<button type="button" class="btn btn-sm btn-primary" ng-click="agregaPrecio( 'menu' )">
										<span class="glyphicon glyphicon-plus"></span>
										Agregar
									</button>
								</div>
								<div class="clearfix"></div>
								<div class="col-sm-12">
									<br>
									<div class="col-sm-offset-1 col-sm-10">
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-center col-sm-1">No.</th>
													<th class="text-center col-sm-4">Tipo Servicio</th>
													<th class="text-center col-sm-3">Q. Precio</th>
													<th class="text-center col-sm-3"></th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="lp in menu.lstPrecios">
													<td class="text-center">
														{{ $index + 1 }}
													</td>
													<td>
														{{ returnTipoServicio( lp.idTipoServicio ) }}
													</td>
													<td class="text-right">
														<div ng-show="!lp.editar">
															{{ lp.precio | number:2 }}
														</div>
														<div ng-show="lp.editar">
															<input type="number" min="0" class="form-control" ng-model="lp.precio">
														</div>
													</td>
													<td class="text-center">
														<button type="button" class="btn btn-info btn-xs" ng-click="lp.editar=!lp.editar">
															<span class="glyphicon glyphicon-pencil"></span>
														</button>
														<button type="button" class="btn btn-danger btn-xs" ng-click="removerPrecio( $index )" >
															<span class="glyphicon glyphicon-remove"></span>
														</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaMenu()">
						<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 1 ); $hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- MODAL AGREGAR / EDITAR COMBO -->
<script type="text/ng-template" id="dial.adminCombo.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" ng-class="{'panel-warning': accion == 'insert', 'panel-info': accion == 'update'}">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
						{{ accion == 'insert' ? 'INGRESAR NUEVO' : 'ACTUALIZAR' }} COMBO
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend">DATOS</legend>
						<!-- FORMULARIO COMBO -->
						<form class="form-horizontal" role="form" name="formCombo">
							<div class="form-group">
								<label class="control-label col-sm-2">Nombre Combo</label>
								<div class="col-sm-8 ">
									<input type="text" class="form-control" ng-model="combo.combo" maxlength="45" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2" ng-show="id>0">Estado Menu</label>
								<div class="col-sm-2" ng-show="id>0">
									<select class="form-control" ng-model="combo.idEstadoMenu" >
										<option ng-repeat="e in lstEstadoMenu" value="{{e.idEstadoMenu}}">{{e.estadoMenu}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Descripcion</label>
								<div class="col-sm-10">
									<textarea rows="3" class="form-control" placeholder="Ingrese descripción del Combo" ng-model='combo.descripcion'></textarea>
								</div>
							</div>
							<div class="form-group text-center">
								<button type="button" class="btn btn-lg" ng-click="combo.subirImagen=!combo.subirImagen"  ng-class="{'btn-success': combo.subirImagen, 'btn-warning': !combo.subirImagen}">
									<span class="glyphicon" ng-class="{'glyphicon-check': combo.subirImagen, 'glyphicon-unchecked': !combo.subirImagen}"></span>
									Subir Imagen
								</button>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="registraCombo()">
						<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 'combo' ); $hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>