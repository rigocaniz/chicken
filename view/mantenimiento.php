<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 10 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="#/">
	            	<img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	            </a>
	        </div>

			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : menuTab=='menu'}" ng-click="verListaMenu(); resetValores(); menuTab='menu'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> MENUS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : menuTab=='combo'}" ng-click="verListaCombos(); resetValores(); menuTab='combo'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list-alt"></span> COMBOS
					</a>
				</li>
			</ul>
		</div>

		<!-- TAB PANELES -->
		<div class="col-sm-12">
			<div class="text-right">
				<b>Resultados por pagina</b>
				<div class="btn-group " role="group">
			  		<select class="form-control input-sm" ng-model="filter.limite" ng-change="realizarBusqueda()">
			  			<option value="{{ porPagina.limite }}" ng-repeat="porPagina in lstPorPagina">
			  				{{ porPagina.limite }}
			  			</option>
			  		</select>
				</div>
			</div>
			<div class="tab-content">
				<!--  MENUS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuTab=='menu'}" ng-show="menuTab=='menu'">
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong><span class="glyphicon glyphicon-list-alt"></span> LISTADO DE MENUS</strong>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<p>
									<button type="button" class="btn btn-sm btn-info" ng-click="consultarMenusPrecios()">
										<span class="glyphicon glyphicon-plus"></span> <strong><u>E</u></strong>DITAR PRECIOS
									</button>
									<button type="button" class="btn btn-sm btn-success" ng-click="agregarMenuCombo( 'menu' )">
										<span class="glyphicon glyphicon-plus"></span> <strong><u>A</u></strong>GREGAR MENU
									</button>
								</p>
							</div>
							<div class="row">
							  	<div class="col-sm-5 col-md-6 col-lg-7">
							    </div><!-- /input-group -->
							  	<div class="col-sm-7 col-md-6 col-lg-5">
							    	<div class="input-group">
							      		<input type="text" ng-model="filter.busqueda" ng-change="realizarBusqueda()" class="form-control" placeholder="Buscar Menu" maxlength="75">
							      		<span class="input-group-addon">
							        		<span class="glyphicon glyphicon-search"></span>
							      		</span>
							    	</div>
							  	</div>
							</div>
							<br>
							<div class="row">
							  	<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" ng-repeat="m in lstMenu" ng-show="lstMenu.length">
							    	<div class="thumbnail">
								    	<span class="label label-default destino" 
								    		ng-class="{'label-danger': m.idDestinoMenu == 1, 'label-warning': m.idDestinoMenu == 2}">
								    		{{ m.destinoMenu }}
								    	</span>
								      	<img ng-src="{{ m.imagen }}" alt="{{ m.menu }}" ng-click="asignarValorImagen( m.idMenu, 'menu' )" style="height:65px; margin-top: 8px;" data-title="Click en la imagen para cambiarla" data-placement="top" bs-tooltip>
							      		<div class="text-right">
							      			<label class="label" ng-class="{'label-success': m.idEstadoMenu == 1, 'label-default': m.idEstadoMenu == 2}">
							      				{{ m.estadoMenu }}
							      				<span class="glyphicon" ng-class="{'glyphicon-ok-sign': m.idEstadoMenu == 1, 'glyphicon-remove-sign': m.idEstadoMenu == 2}"></span>
							      			</label>
							      		</div>
								      	<div class="caption">
								      		<span class="badge">CÓD: {{ m.codigo }}</span>
								        	<p class="spread">
								        		<strong class="una-linea">{{ m.menu | uppercase }}</strong>
								        	</p>
								        	<hr>
							        		<button type="button" class="btn btn-info btn-sm" ng-click="actualizarMenuCombo( 'menu', m)">
							        			<span class="glyphicon glyphicon-edit"></span> Editar
							        		</button>
							        		<button type="button" class="btn btn-default btn-sm" ng-click="cargarRecetaMenu( m )">
							        			<span class="glyphicon glyphicon-list"></span> Receta
							        		</button>

								      	</div>
							    	</div>
							  	</div>
							  	<div class="col-sm-12" ng-show="!lstMenu.length">
									<div class="alert alert-warning text-right" role="alert">
										<span class="glyphicon glyphicon-info-sign"></span>
										NO SE ENCONTRARON RESULTADOS
									</div>
							  	</div>
							</div>
						</div>
					</div>
				</div>
				<!-- COMBOS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuTab=='combo'}" ng-show="menuTab=='combo'">
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong><span class="glyphicon glyphicon-list-alt"></span> LISTADO DE COMBOS</strong>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<p>
									<button type="button" class="btn btn-sm btn-info" ng-click="consultarComboPrecios()">
										<span class="glyphicon glyphicon-plus"></span> <strong><u>E</u></strong>DITAR PRECIOS
									</button>
									<button type="button" class="btn btn-sm btn-success" ng-click="agregarMenuCombo( 'combo' )">
										<span class="glyphicon glyphicon-plus"></span> <strong><u>A</u></strong>GREGAR COMBO
									</button>
								</p>
							</div>
							<div class="row">
							  	<div class="col-sm-5 col-md-6 col-lg-7">
							    </div>
							  	<div class="col-sm-7 col-md-6 col-lg-5">
							    	<div class="input-group">
							      		<input type="text" ng-model="filter.busqueda" ng-change="realizarBusqueda()" class="form-control" placeholder="Buscar Combo" maxlength="50">
							      		<span class="input-group-addon">
							        		<span class="glyphicon glyphicon-search"></span>
							      		</span>
							    	</div>
							  	</div>
							</div>
							<br>
							<div class="row">
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" ng-repeat="c in lstCombos">
							    	<div class="thumbnail">
								      	<img ng-src="{{ c.imagen }}" alt="{{ c.combo }}" ng-click="asignarValorImagen( c.idCombo, 'combo' )" style="height:65px; margin-top: 8px;" data-title="Click en la imagen para cambiarla" data-placement="top" bs-tooltip>
							      		<div class="text-right">
							      			<label class="label" ng-class="{'label-success': c.idEstadoMenu == 1, 'label-default': c.idEstadoMenu == 2}">
							      				{{ c.estadoMenu }}
							      				<span class="glyphicon" ng-class="{'glyphicon-ok-sign': c.idEstadoMenu == 1, 'glyphicon-remove-sign': c.idEstadoMenu == 2}"></span>
							      			</label>
							      		</div>
								      	<div class="caption">
								      		<span class="badge">CÓD: {{ c.codigo }}</span>
								      		<p class="spread">
								        		<strong class="una-linea">{{ c.combo | uppercase }}</strong>
								        	</p>
								        	<hr>
							        		<button type="button" class="btn btn-info btn-sm" ng-click="actualizarMenuCombo( 'combo', c)">
							        			<span class="glyphicon glyphicon-edit"></span> Editar
							        		</button>

							        		<button type="button" class="btn btn-primary btn-sm" ng-click="cargarDetalleCombo( c )">
							        			<span class="glyphicon glyphicon-list"></span> Menu
							        		</button>

								      	</div>
							    	</div>
							  	</div>
							  	<div class="col-sm-12" ng-show="!lstCombos.length">
									<div class="alert alert-warning text-right" role="alert">
										<span class="glyphicon glyphicon-info-sign"></span>
										NO SE ENCONTRARON RESULTADOS
									</div>
							  	</div>
							</div>
						</div>
					</div>
				</div>

				<!-- PAGINADOR -->
				<nav>
					<ul class="pagination pagination-lg" ng-show="lstPaginacion.length > 1">
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


<!-- MODAL EDITAR PRECIOS COMBOS -->
<script type="text/ng-template" id="dial.editarPreciosC.html">
	<div class="modal" tabindex="-1" role="dialog" id="dialEditarPreciosC">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-primary">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide();">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-pencil"></span> EDITAR PRECIOS COMBOS
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend primary">EDITAR PRECIOS</legend>
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th class="text-center col-sm-2">Código</th>
									<th class="text-center col-sm-4">Descripción Menú</th>
									<th class="text-center col-sm-2">Para Llevar</th>
									<th class="text-center col-sm-2">Restaurante</th>
									<th class="text-center col-sm-2">Dominicilio</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="(ixCombo, comboPrecio) in lstCombosPrecios">
									<td>{{ $index + 1 }}</td>
									<td class="text-center">{{ comboPrecio.codigoCombo }}</td>
									<td><b>{{ comboPrecio.combo }}</b></td>
									<td class="text-right" ng-repeat="(ixPrecio, precio) in comboPrecio.lstPrecios">
										<input type="number" min="0" max="10000" class="form-control" ng-model="precio.precio" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="1" required ng-focus="ixCombo == 0 && ixPrecio==0" focus-enter>
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-info" ng-click="actualizarPreciosCombo()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
					</button>
					<button type="button" class="btn btn-default" ng-click="$hide();">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- MODAL EDITAR PRECIOS MENU -->
<script type="text/ng-template" id="dial.editarPrecios.html">
	<div class="modal" tabindex="-1" role="dialog" id="dialEditarPrecios">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-info">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide();">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-pencil"></span> EDITAR PRECIOS MENUS
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend info">EDITAR PRECIOS</legend>
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th class="text-center col-sm-2">Código</th>
									<th class="text-center col-sm-4">Descripción Menú</th>
									<th class="text-center col-sm-2">Para Llevar</th>
									<th class="text-center col-sm-2">Restaurante</th>
									<th class="text-center col-sm-2">Dominicilio</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="(ixMenuPrecio, menuPrecio) in lstMenusPrecios">
									<td>{{ $index + 1 }}</td>
									<td class="text-center">{{ menuPrecio.codigoMenu }}</td>
									<td><b>{{ menuPrecio.menu }}</b></td>
									<td class="text-right" ng-repeat="(ixPrecio, precio) in menuPrecio.lstPrecios">
										<input type="number" min="0" max="10000" class="form-control" ng-model="precio.precio" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="1" required ng-focus="ixMenuPrecio == 0 && ixPrecio==0" focus-enter>
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-info" ng-click="actualizarPrecios()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
					</button>
					<button type="button" class="btn btn-default" ng-click="$hide();">
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
	<div class="modal" tabindex="-1" role="dialog" id="dialAdminMenu">
		<div class="modal-dialog modal-lg">
 			<div class="modal-content" ng-class="{'panel-warning': accion == 'insert', 'panel-info': accion == 'update'}">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="resetValores( 'menu' ); $hide();">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
						{{ accion == 'insert' ? 'INGRESAR NUEVO' : 'ACTUALIZAR' }} MENU
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend" ng-class="{'warning': accion == 'insert', 'info': accion == 'update'}">DATOS</legend>
						<!-- FORMULARIO MENU -->
						<form class="form-horizontal" role="form" name="formMenu">
							<div class="text-right" ng-show="accion == 'update'">
								<div class="col-sm-12">
									<label class="control-label">ELIMINAR MENU</label>
								  	<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-danger': estadoMenu.idEstadoMenu == menu.idEstadoMenu}" ng-click="menu.idEstadoMenu = 3" ng-show="estadoMenu.idEstadoMenu==3" ng-repeat="estadoMenu in lstEstadosMenu">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': menu.idEstadoMenu == 3, 'glyphicon-unchecked': menu.idEstadoMenu != 3}"></span>
								  		ELIMINAR
								  	</button>
								</div>
							</div>
							<div class="form-group text-center" ng-show="accion == 'update'">
								<img ng-src="{{ menu.imagen }}" alt="MENU" class="img-circle img-portada">
								<br>
								<span class="badge">Codigo Menú #{{ menu.idMenu }}</span>
							</div>
							<div class="form-group" ng-show="accion == 'update'">
								<label class="col-sm-4 col-md-3 col-lg-2">ESTADO DEL MENÚ</label>
								<div class="col-sm-6">
									<div class="btn-group btn-group-sm" role="group">
									  	<button type="button" class="btn btn-default" ng-class="{'btn-info': estadoMenu.idEstadoMenu == menu.idEstadoMenu}" ng-click="menu.idEstadoMenu = estadoMenu.idEstadoMenu" ng-hide="estadoMenu.idEstadoMenu==3" ng-repeat="estadoMenu in lstEstadosMenu">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': estadoMenu.idEstadoMenu == menu.idEstadoMenu, 'glyphicon-unchecked': estadoMenu.idEstadoMenu != menu.idEstadoMenu}"></span>
									  		{{ estadoMenu.estadoMenu }}
									  	</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-5 col-sm-6 col-md-5">
									<label class="control-label">NOMBRE DEL MENU</label>
									<input type="text" id="nombreMenu" class="form-control" ng-model="menu.menu" maxlength="40" required focus-enter>
								</div>
								<div class="col-xs-4 col-sm-3 col-md-3">
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
								<div class="col-xs-3 col-sm-3 col-md-3">
									<label class="control-label">CÓDIGO DEL MENÚ</label>
									<input type="text" class="form-control" ng-pattern="/^[0-9]+?$/" ng-model="menu.codigo" maxlength="10" ng-trim="false" required focus-enter>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-6 col-sm-6 col-md-4">
									<label class="control-label">TIPO DE MENU</label>
									<div>
										
									<div class="btn-group btn-group-sm" role="group">
									  	<button type="button" class="btn btn-default" ng-class="{'btn-info': tipoMenu.idTipoMenu == menu.idTipoMenu}" ng-click="menu.idTipoMenu = tipoMenu.idTipoMenu" ng-repeat="tipoMenu in lstTipoMenu">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': tipoMenu.idTipoMenu == menu.idTipoMenu, 'glyphicon-unchecked': tipoMenu.idTipoMenu != menu.idTipoMenu}"></span>
									  		{{ tipoMenu.tipoMenu }}
									  	</button>
									</div>
									</div>
								</div>
								<div class="col-xs-5 col-sm-4 col-md-3">
									<label class="control-label">TIEMPO LÍMITE (Aprox.)</label>
									<div class="input-group">
										<input type="number" min="0" class="form-control" ng-pattern="/^[0-9]+?$/" ng-model="menu.tiempoAlerta" max="60" required focus-enter>
									  	<span class="input-group-addon">Minutos</span>
									</div>
								</div>						
							</div>
							<div class="form-group">
								<div class="col-xs-6 col-sm-6 col-md-4">
									<label class="control-label">SE COCINA?</label>
									<div>
										<div class="btn-group btn-group-sm" role="group">
										  	<button type="button" class="btn" ng-class="{'btn-primary':menu.seCocina,'btn-default':!menu.seCocina}" ng-click="menu.seCocina = 1">
										  		<span class="glyphicon" ng-class="{'glyphicon-check':menu.seCocina, 'glyphicon-unchecked':!menu.seCocina}"></span>
										  		SI
										  	</button>
										  	<button type="button" class="btn" ng-class="{'btn-primary':!menu.seCocina,'btn-default':menu.seCocina}" ng-click="menu.seCocina = 0">
										  		<span class="glyphicon" ng-class="{'glyphicon-check':!menu.seCocina, 'glyphicon-unchecked':menu.seCocina}"></span>
										  		NO
										  	</button>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">DESCRIPCIÓN</label>
									<textarea rows="3" class="form-control" placeholder="Ingrese la descripción del menu" ng-model='menu.descripcion' required focus-enter></textarea>
								</div>
							</div>
							<!-- Nav tabs -->
							<div>
							  	<ul class="nav nav-tabs" role="tablist">
							    	<li role="presentation" ng-class="{'active': menu.tabMenu=='precios'}" ng-click="menu.tabMenu='precios'">
							    		<a href="" aria-controls="home" role="tab" data-toggle="tab">PRECIOS</a>
							    	</li>
							    	<li role="presentation" ng-class="{'active': menu.tabMenu=='receta'}" ng-click="menu.tabMenu='receta'" ng-show="accion=='insert'">
							    		<a href="" aria-controls="profile" role="tab" data-toggle="tab">RECETA</a>
							    	</li>
							  	</ul>
							  	<br>
							  	<div class="tab-content">
							    	<div role="tabpanel" class="tab-pane" ng-class="{'active': menu.tabMenu=='precios'}">
										<div class="form-group">
											<legend class="text-center">
												<small>
													<i class="fa fa-money" aria-hidden="true"></i> PRECIOS DEL MENÚ
												</small>
											</legend>
											<table class="table table-hover">
												<thead>
													<tr>
														<th class="text-center col-sm-2">No.</th>
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
															{{ lp.tipoServicio }}
														</td>
														<td class="text-right">
															<input type="number" min="0" max="5000" class="form-control" ng-model="lp.precio" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required focus-enter>
														</td>
														<td class="text-right">
															<kbd class="numEfectivo">Q. {{ ( lp.precio ? lp.precio : 0 ) | number:2 }}</kbd>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
							    	</div>
							    	<div role="tabpanel" class="tab-pane" ng-class="{'active': menu.tabMenu=='receta'}">
										<fieldset class="fieldset">
											<legend class="legend">Ingresar Receta</legend>
											<div class="form-group">
												<div class="col-sm-7 col-md-5">
													<label class="control-label">Producto</label>
													<div ng-show="!prod.seleccionado">
														<input type="text" id="producto" class="form-control" ng-model="prod.producto" maxlength="35" placeholder="Ingrese producto" ng-change="buscarProducto( prod.producto )" ng-keydown="seleccionKeyElemento( $event.keyCode, 'producto' );">
														<ul class="list-group ul-list" ng-show="lstBusqueda.length">

														    <li class="list-group-item" ng-class="{'active': $parent.idxElSeleccionado == ixProducto}" ng-repeat="(ixProducto, producto) in lstBusqueda" ng-click="seleccionarElemento( producto, 'producto' )" ng-mouseenter="$parent.idxElSeleccionado = ixProducto">
														    	<strong>{{ producto.producto | uppercase }}</strong> 
														    	<small>({{ producto.medida }})</small><br>
														    	<small>{{ producto.tipoProducto }}</small>
														    </li>
														</ul>
													</div>
													<div ng-show="prod.seleccionado">
														<input type="text" class="form-control" ng-model="prod.producto" placeholder="Ingrese producto" disabled>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<label class="control-label">Cantidad ({{ prod.medida }}) </label>
													<input type="number" min="0.01" ng-keydown="$event.keyCode == 13 && agregaReceta();" ng-model="prod.cantidad" id="cantidad" class="form-control" placeholder="Cantidad" >
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-7">
													<label class="control-label">Observacion</label>
													<textarea class="form-control" ng-model="prod.observacion"></textarea>
												</div>
												<div class="col-sm-4">
													<br>
													<button type="button" class="btn btn-sm btn-warning" ng-click="agregaReceta();">
														<span class="glyphicon glyphicon-plus"></span> Agregar
													</button>
													<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 'producto' )">
														<span class="glyphicon glyphicon-remove"></span> Cancelar
													</button>
												</div>
											</div>
											<table class="table table-hover" ng-show="menu.lstRecetaMenu.length">
												<thead>
													<tr>
														<th class="text-center col-sm-4">Producto</th>
														<th class="text-center col-sm-2">Tipo de Producto</th>
														<th class="text-center col-sm-3">Cantidad</th>
														<th class="text-center col-sm-1">Medida</th>
														<th class="text-center col-sm-1">Editar</th>
														<th class="text-center col-sm-1">Eliminar</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="(ixReceta, receta) in menu.lstRecetaMenu">
														<td>
															{{ receta.producto }}<br>
															<button type="button" class="btn btn-default btn-xs" ng-click="receta.mostrar=!receta.mostrar">
																<span class="glyphicon" ng-class="{'glyphicon-eye-open': receta.mostrar, 'glyphicon-eye-close': !receta.mostrar}"></span> {{ receta.mostrar ? 'Ocultar' : 'Mostrar' }} 
															</button>
															<div ng-show="receta.mostrar || receta.editar">
																<div ng-show="receta.editar">
																	<textarea rows="2" class="form-control" ng-model="receta.observacion"></textarea>
																</div>
																<div ng-show="receta.mostrar && !receta.editar">
																	{{ receta.observacion }}
																</div>
															</div>
														</td>
														<td class="text-center">
															{{ receta.tipoProducto }}
														</td>
														<td class="text-right">
															<input type="number" min="0" class="form-control" ng-model="receta.cantidad" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required ng-disabled="!receta.editar">
														</td>
														<td class="text-center">
															{{ receta.medida }}
														</td>
														<td class="text-center">
															<button type="button" class="btn btn-info btn-sm" ng-click="receta.editar=!receta.editar">
																<span class="glyphicon glyphicon-pencil"></span>
															</button>
														</td>
														<td class="text-center">
															<button type="button" class="btn btn-danger btn-sm" ng-click="eliminarProductoReceta( ixReceta )">
																<span class="glyphicon glyphicon-trash"></span>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="alert alert-warning text-right" role="alert" ng-show="!menu.lstRecetaMenu.length">
												<span class="glyphicon glyphicon-info-sign"></span> NO SE HA INGRESADO LA RECETA DEL MENÚ
											</div>
										</fieldset>
							    	</div>
							  	</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaMenu()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 'menu' ); $hide();">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- MODAL AGREGAR / EDITAR RECETA MENU -->
<script type="text/ng-template" id="dial.recetaMenu.html">
	<div class="modal" tabindex="-1" role="dialog" id="dialRecetaMenu">
		<div class="modal-dialog modal-xl">
			<div class="modal-content panel-danger">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-book"></span>
						RECETA DEL MENU
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend danger">DATOS</legend>
						<!-- FORMULARIO MENU -->
						<ul class="media-list">
							<li class="media">
								<div class="media-left text-center">
									<img class="media-object" width="125px" height="125px" ng-src="{{ objMenu.imagen }}" alt="MENU">nombre
									<label class="label label-info">
										<b>CÓDIGO #{{ objMenu.idMenu }}</b>
									</label>
								</div>
								<div class="media-body">
									<div class="pull-right">
										<label class="label" ng-class="{'label-success': objMenu.idEstadoMenu == 1, 'label-default': objMenu.idEstadoMenu == 2}"> {{ objMenu.estadoMenu | uppercase }} </label><br>
									</div>
									<h4 class="media-heading">
										{{ objMenu.menu | uppercase }}
									</h4>
									<div class="pull-right">
									</div>
									<p>
										<strong>DESCRIPCIÓN:</strong>
									</p>
									<p>
										{{ objMenu.descripcion }}
									</p>
								</div>
							</li>
						</ul>
						<form class="form-horizontal" role="form" name="formMenu" autocomplete="off">
							<!-- INGRESAR RECETA -->
							<fieldset class="fieldset">
								<legend class="legend">Ingresar Receta</legend>
								<div class="form-group">
									<div class="col-sm-12 text-right">
										<button type="button" class="btn btn-success" ng-click="$hide();dialAdministrar.show()">
											<span class="glyphicon glyphicon-plus"></span>
											<b>Nuevo Producto</b>
										</button>
									</div>
									<div class="col-sm-7 col-md-5">
										<label class="control-label">Producto</label>
										<div ng-show="!prod.seleccionado">
											<input type="text" id="producto" class="form-control" ng-model="prod.producto" maxlength="35" placeholder="Ingrese producto a agregar" ng-change="buscarProducto( prod.producto )" ng-keydown="seleccionKeyElemento( $event.keyCode, 'producto' );">
											<ul class="list-group ul-list" ng-show="lstBusqueda.length">

											    <li class="list-group-item" ng-class="{'active': $parent.idxElSeleccionado == ixProducto}" ng-repeat="(ixProducto, producto) in lstBusqueda" ng-click="seleccionarElemento( producto, 'producto' )" ng-mouseenter="$parent.idxElSeleccionado = ixProducto">
											    	<strong>{{ producto.producto | uppercase }}</strong> 
											    	<small>({{ producto.medida }})</small><br>
											    	<small>{{ producto.tipoProducto }}</small>
											    </li>
											</ul>
										</div>
										<div ng-show="prod.seleccionado">
											<input type="text" class="form-control" ng-model="prod.producto" placeholder="Ingrese producto" disabled>
										</div>
									</div>
									<div class="col-sm-4 col-md-3">
										<label class="control-label">Cantidad ({{ prod.medida }}) </label>
										<input type="number" min="0.01" ng-keydown="$event.keyCode == 13 && agregarIngresoProducto();" ng-model="prod.cantidad" id="cantidad" class="form-control" placeholder="Cantidad" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-7">
										<label class="control-label">Observacion</label>
										<textarea class="form-control" ng-model="prod.observacion"></textarea>
									</div>
									<div class="col-sm-4">
										<br>
										<button type="button" class="btn btn-sm btn-warning" ng-click="agregarIngresoProducto();">
											<span class="glyphicon glyphicon-plus"></span> Agregar
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 'producto' )">
											<span class="glyphicon glyphicon-remove"></span> Cancelar
										</button>
									</div>
								</div>
							</fieldset>
							<br>
							<legend class="text-center legend-red">
								<span class="glyphicon glyphicon-file"></span> RECETA DEL MENU
							</legend>
							<div class="form-group">
								<table class="table table-hover" ng-show="objMenu.lstRecetaMenu.length">
									<thead>
										<tr>
											<th class="text-center col-sm-4">Producto</th>
											<th class="text-center col-sm-2">Tipo de Producto</th>
											<th class="text-center col-sm-3">Cantidad</th>
											<th class="text-center col-sm-1">Medida</th>
											<th class="text-center col-sm-1">Editar</th>
											<th class="text-center col-sm-1">Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="receta in objMenu.lstRecetaMenu">
											<td>
												{{ receta.producto }}<br>
												<button type="button" class="btn btn-default btn-xs" ng-click="receta.mostrar=!receta.mostrar">
													<span class="glyphicon" ng-class="{'glyphicon-eye-open': receta.mostrar, 'glyphicon-eye-close': !receta.mostrar}"></span> {{ receta.mostrar ? 'Ocultar' : 'Mostrar' }} 
												</button>
												<div ng-show="receta.mostrar || receta.editar">
													<div ng-show="receta.editar">
														<textarea rows="2" class="form-control" ng-model="receta.observacion"></textarea>
													</div>
													<div ng-show="receta.mostrar && !receta.editar">
														{{ receta.observacion }}
													</div>
												</div>
											</td>
											<td class="text-center">
												{{ receta.tipoProducto }}
											</td>
											<td class="text-right">
												<input type="number" min="0" class="form-control" ng-model="receta.cantidad" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required ng-disabled="!receta.editar">
											</td>
											<td class="text-center">
												{{ receta.medida }}
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-info btn-sm" ng-click="receta.editar=!receta.editar">
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-sm" ng-click="eliminarProdReceta( receta.idMenu, receta.idProducto )">
													<span class="glyphicon glyphicon-trash"></span>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="alert alert-warning text-right" role="alert" ng-show="!objMenu.lstRecetaMenu.length">
									<span class="glyphicon glyphicon-info-sign"></span> NO SE HA INGRESADO LA RECETA DEL MENÚ
								</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" ng-click="actualizarLstReceta()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 'receta' ); $hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<!-- MODAL AGREGAR / EDITAR DETALLE COMBO -->
<script type="text/ng-template" id="dial.detalleCombo.html">
	<div class="modal" tabindex="-1" role="dialog" id="dialDetalleCombo">
		<div class="modal-dialog modal-xl">
			<div class="modal-content panel-danger">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-book"></span>
						DETALLE DEL COMBO
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend danger">DATOS</legend>
						<!-- FORMULARIO MENU -->
						<ul class="media-list">
							<li class="media">
								<div class="media-left text-center">
									<img class="media-object" width="125px" height="125px" ng-src="{{ objCombo.imagen }}" alt="MENU">
									<label class="label label-info">
										<b>CÓDIGO #{{ objCombo.idCombo }}</b>
									</label>
								</div>
								<div class="media-body">
									<div class="pull-right">
										<label class="label" ng-class="{'label-success': objCombo.idEstadoMenu == 1, 'label-default': objCombo.idEstadoMenu == 2}"> {{ objCombo.estadoMenu | uppercase }} </label><br>
									</div>
									<h4 class="media-heading">
										{{ objCombo.combo | uppercase }}
									</h4>
									<div class="pull-right">
									</div>
									<p>
										<strong>DESCRIPCIÓN:</strong>
									</p>
									<p>
										{{ objCombo.descripcion }}
									</p>
								</div>
							</li>
						</ul>

						<form class="form-horizontal" role="form" name="formMenu" autocomplete="off">
							<!-- INGRESAR MENUS COMBO -->
							<fieldset class="fieldset">
								<legend class="legend">Ingresar Menu</legend>
								<div class="form-group">
									<div class="col-sm-7 col-md-5">
										<label class="control-label">MENU</label>
										<div ng-show="!menuD.seleccionado">

											<input type="text" id="menu" class="form-control" ng-model="menuD.menu" maxlength="35" placeholder="Ingresar Menu" ng-change="buscarMenu( menuD.menu )" ng-keydown="seleccionKeyElemento( $event.keyCode, 'menu' );">
											<ul class="list-group ul-list" ng-show="lstBusqueda.length">

											    <li class="list-group-item" ng-class="{'active': $parent.idxElSeleccionado == ixMenu}" ng-repeat="(ixMenu, menu) in lstBusqueda" ng-click="seleccionarElemento( menu, 'menu' )" ng-mouseenter="$parent.idxElSeleccionado = ixMenu">
											    	<strong>{{ menu.menu }}</strong> 
											    </li>
											</ul>
										</div>
										<div ng-show="menuD.seleccionado">
											<input type="text" class="form-control" ng-model="menuD.menu" placeholder="Ingrese Menu" disabled>
										</div>
									</div>
									<div class="col-sm-4 col-md-3">
										<label class="control-label">CANTIDAD</label>
										<input type="number" min="0" ng-pattern="/^[0-9]+?$/" step="any" ng-keydown="$event.keyCode == 13 && agregarMenuDetalleCombo();" ng-model="menuD.cantidad" id="cantidad" class="form-control" placeholder="Cantidad" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-4">
										<button type="button" class="btn btn-sm btn-warning" ng-click="agregarMenuDetalleCombo();">
											<span class="glyphicon glyphicon-plus"></span> Agregar
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 'comboDetalle' )">
											<span class="glyphicon glyphicon-remove"></span> Cancelar
										</button>
									</div>
								</div>
							</fieldset>
							<br><br>
							<legend class="text-center legend-red">
								<span class="glyphicon glyphicon-file"></span> DETALLE DEL COMBO
							</legend>
							<div class="form-group">
								<table class="table table-hover" ng-show="objCombo.lstDetalleCombo.length">
									<thead>
										<tr>
											<th class="text-center col-sm-1">ID MENU</th>
											<th class="text-center col-sm-3">MENU</th>
											<th class="text-center col-sm-2">ESTADO MENU</th>
											<th class="text-center col-sm-3">Cantidad</th>
											<th class="text-center col-sm-1">Editar</th>
											<th class="text-center col-sm-1">Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="detalleCombo in objCombo.lstDetalleCombo">
											<td class="text-center">
												<img class="img-thumbnail" width="50px" height="50px" ng-src="{{ detalleCombo.imagen }}" alt="Menu">
												<label class="label label-info">
													MENU #{{ detalleCombo.idMenu }}
												</label>
											</td>
											<td class="text-center">
												{{ detalleCombo.menu }}
											</td>
											<td class="text-center" ng-class="{'warning': detalleCombo.idEstadoMenu==2}">
												{{ detalleCombo.estadoMenu }}
											</td>
											<td class="text-right">
												<input type="number" min="0" class="form-control" ng-model="detalleCombo.cantidad" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required ng-disabled="!detalleCombo.editar">
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-info btn-sm" ng-click="detalleCombo.editar=!detalleCombo.editar">
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-sm" ng-click="eliminarDetalleCombo( detalleCombo.idCombo, detalleCombo.idMenu )">
													<span class="glyphicon glyphicon-trash"></span>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="alert alert-warning text-right" role="alert" ng-show="!objCombo.lstDetalleCombo.length">
									<span class="glyphicon glyphicon-info-sign"></span> NO SE HA INGRESADO EL DETALLE DEL COMBO
								</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" ng-click="actualizarLstDetalleCombo()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 'receta' ); $hide()">
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
	<div class="modal" tabindex="-1" role="dialog" id="dialAdminCombo">
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
						<legend class="legend" ng-class="{'warning': accion == 'insert', 'info': accion == 'update'}">DATOS</legend>
						<!-- FORMULARIO COMBO -->
						<form class="form-horizontal" role="form" name="formCombo">
							<div class="text-right" ng-show="accion == 'update'">
								<div class="col-sm-12">
									<label class="control-label">ELIMINAR MENU</label>
								  	<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-danger': estadoMenu.idEstadoMenu == combo.idEstadoMenu}" ng-click="combo.idEstadoMenu = 3" ng-show="estadoMenu.idEstadoMenu==3" ng-repeat="estadoMenu in lstEstadosMenu">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': combo.idEstadoMenu == 3, 'glyphicon-unchecked': combo.idEstadoMenu != 3}"></span>
								  		ELIMINAR
								  	</button>
								</div>
							</div>
							<div class="form-group text-center" ng-show="accion == 'update'">
								<img ng-src="{{ combo.imagen }}" alt="MENU" class="img-circle img-portada">
								<br>
								<span class="badge">Codigo Combo #{{ combo.idCombo }}</span>
							</div>
							<div class="form-group" ng-show="accion == 'update'">
								<label class="col-sm-4">ESTADO DEL MENÚ</label>
								<div class="col-sm-6">
									<div class="btn-group btn-group-sm" role="group">
									  	<button type="button" class="btn btn-default" ng-class="{'btn-info': estadoMenu.idEstadoMenu == combo.idEstadoMenu}" ng-click="combo.idEstadoMenu = estadoMenu.idEstadoMenu" ng-hide="estadoMenu.idEstadoMenu==3" ng-repeat="estadoMenu in lstEstadosMenu">
									  		<span class="glyphicon" ng-class="{'glyphicon-check': estadoMenu.idEstadoMenu == combo.idEstadoMenu, 'glyphicon-unchecked': estadoMenu.idEstadoMenu != combo.idEstadoMenu}"></span>
									  		{{ estadoMenu.estadoMenu }}
									  	</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8 col-md-7">
									<label class="control-label">NOMBRE DEL COMBO</label>
									<input type="text" id="nombreCombo" class="form-control" ng-model="combo.combo" maxlength="45" required focus-enter>
								</div>
								<div class="col-sm-4 col-md-5">
									<label class="control-label">CÓDIGO DEL COMBO</label>
									<input type="text" class="form-control" ng-model="combo.codigo" ng-pattern="/^[0-9]+?$/" maxlength="10" ng-trim="false" required focus-enter>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">DESCRIPCION</label>
									<textarea rows="3" class="form-control" placeholder="Ingrese descripción del Combo" ng-model='combo.descripcion' focus-enter></textarea>
								</div>
							</div>
							<!-- MENU TABS -->
							<div>
							  	<ul class="nav nav-tabs" role="tablist">
							    	<li role="presentation" ng-class="{'active': combo.tabMenu=='precios'}" ng-click="combo.tabMenu='precios'">
							    		<a href="" aria-controls="home" role="tab" data-toggle="tab">PRECIOS</a>
							    	</li>
							    	<li role="presentation" ng-class="{'active': combo.tabMenu=='menus'}" ng-click="combo.tabMenu='menus'" ng-show="accion=='insert'">
							    		<a href="" aria-controls="profile" role="tab" data-toggle="tab">MENUS</a>
							    	</li>
							  	</ul>
							  	<br>
							  	<div class="tab-content">
							    	<div role="tabpanel" class="tab-pane" ng-class="{'active': combo.tabMenu=='precios'}">
										<legend class="text-center">
											<small>
												<i class="fa fa-money" aria-hidden="true"></i> PRECIOS DEL COMBO
											</small>
										</legend>
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-center col-sm-2">No.</th>
													<th class="text-center col-sm-4">Tipo Servicio</th>
													<th class="text-center col-sm-3">Q. Precio</th>
													<th class="text-center col-sm-3"></th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="lp in combo.lstPrecios">
													<td class="text-center">
														{{ $index + 1 }}
													</td>
													<td>
														{{ lp.tipoServicio }}
													</td>
													<td class="text-right">
														<input type="number" min="0" max="5000" class="form-control" ng-model="lp.precio" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required focus-enter>
													</td>
													<td class="text-right">
														<kbd class="numEfectivo">Q. {{ ( lp.precio ? lp.precio : 0 ) | number:2 }}</kbd>
													</td>
												</tr>
											</tbody>
										</table>
							    	</div>
							    	<div role="tabpanel" class="tab-pane" ng-class="{'active': combo.tabMenu=='menus'}">
										<fieldset class="fieldset">
											<legend class="legend">Ingresar Menu</legend>
											<div class="form-group">
												<div class="col-sm-7 col-md-5">
													<label class="control-label">MENU</label>
													<div ng-show="!menuD.seleccionado">

														<input type="text" id="menu" class="form-control" ng-model="menuD.menu" maxlength="35" placeholder="Ingresar Menu" ng-change="buscarMenu( menuD.menu )" ng-keydown="seleccionKeyElemento( $event.keyCode, 'menu' );">
														<ul class="list-group ul-list" ng-show="lstBusqueda.length">

														    <li class="list-group-item" ng-class="{'active': $parent.idxElSeleccionado == ixMenu}" ng-repeat="(ixMenu, menu) in lstBusqueda" ng-click="seleccionarElemento( menu, 'menu' )" ng-mouseenter="$parent.idxElSeleccionado = ixMenu">
														    	<strong>{{ menu.menu }}</strong> 
														    </li>
														</ul>
													</div>
													<div ng-show="menuD.seleccionado">
														<input type="text" class="form-control" ng-model="menuD.menu" placeholder="Ingrese Menu" disabled>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<label class="control-label">CANTIDAD</label>
													<input type="number" min="0" ng-pattern="/^[0-9]+?$/" step="any" ng-keydown="$event.keyCode == 13 && agregarMenuC();" ng-model="menuD.cantidad" id="cantidad" class="form-control" placeholder="Cantidad" >
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
													<button type="button" class="btn btn-sm btn-warning" ng-click="agregarMenuC();">
														<span class="glyphicon glyphicon-plus"></span> Agregar
													</button>
													<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 'comboDetalle' )">
														<span class="glyphicon glyphicon-remove"></span> Cancelar
													</button>
												</div>
											</div>
										</fieldset>
										<br><br>
										<legend class="text-center legend-red">
											<span class="glyphicon glyphicon-file"></span> DETALLE DEL COMBO
										</legend>
										<div class="form-group">
											<table class="table table-hover" ng-show="combo.lstMenusCombo.length">
												<thead>
													<tr>
														<th class="text-center col-sm-1">ID MENU</th>
														<th class="text-center col-sm-3">MENU</th>
														<th class="text-center col-sm-2">ESTADO MENU</th>
														<th class="text-center col-sm-3">Cantidad</th>
														<th class="text-center col-sm-1">Editar</th>
														<th class="text-center col-sm-1">Eliminar</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="(ixMenu, detalleCombo) in combo.lstMenusCombo">
														<td class="text-center">
															<img class="img-thumbnail" width="50px" height="50px" ng-src="{{ detalleCombo.imagen }}" alt="Menu">
															<label class="label label-info">
																MENU #{{ detalleCombo.idMenu }}
															</label>
														</td>
														<td class="text-center">
															{{ detalleCombo.menu }}
														</td>
														<td class="text-center" ng-class="{'warning': detalleCombo.idEstadoMenu==2}">
															{{ detalleCombo.estadoMenu }}
														</td>
														<td class="text-right">
															<input type="number" min="0" class="form-control" ng-model="detalleCombo.cantidad" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" required ng-disabled="!detalleCombo.editar">
														</td>
														<td class="text-center">
															<button type="button" class="btn btn-info btn-sm" ng-click="detalleCombo.editar=!detalleCombo.editar">
																<span class="glyphicon glyphicon-pencil"></span>
															</button>
														</td>
														<td class="text-center">
															<button type="button" class="btn btn-danger btn-sm" ng-click="eliminarMenuCombo( ixMenu )">
																<span class="glyphicon glyphicon-trash"></span>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="alert alert-warning text-right" role="alert" ng-show="!combo.lstMenusCombo.length">
												<span class="glyphicon glyphicon-info-sign"></span> NO SE HA INGRESADO EL DETALLE DEL COMBO
											</div>
										</div>
							    	</div>
							    </div>
							</div>
							<div class="form-group">
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaCombo()">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
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


<!-- MODAL AGREGAR / EDITAR PRODUCTO -->
<script type="text/ng-template" id="dialAdmin.producto.html">
	<div class="modal" tabindex="0" role="dialog" id="dialAdminProducto">
		<div class="modal-dialog">
			<div class="modal-content" ng-class="{'panel-warning': accionProducto == 'insert', 'panel-info': accionProducto == 'update'}">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<strong>
						<span class="glyphicon" ng-class="{'glyphicon-plus': accionProducto == 'insert', 'glyphicon glyphicon-pencil': accionProducto == 'update'}"></span>
						{{ accionProducto == 'insert' ? 'INGRESAR' : 'ACTUALIZAR' }} PRODUCTO
					</strong>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend">DATOS</legend>
						<!-- FORMULARIO PRODUCTO -->
						<form class="form-horizontal" role="form" name="$parent.formProducto">
							<div class="text-right">
								<label>Ubicación</label>
								<div class="btn-group" role="group" aria-label="">
								  	<button type="button" class="btn btn-default" ng-repeat="ubicacion in lstUbicacion" ng-click="producto.idUbicacion=ubicacion.idUbicacion">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': producto.idUbicacion==ubicacion.idUbicacion, 'glyphicon-unchecked': producto.idUbicacion!=ubicacion.idUbicacion}"></span>
								  		{{ ubicacion.ubicacion }}
								  	</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<label class="control-label">Nombre Producto</label>
									<input type="text" id="nombreProducto" class="form-control" ng-model="producto.producto" maxlength="45" required>
								</div>
								<div class="col-sm-3" ng-show="accionProducto!='insert'">
									<label class="control-label">No. Producto</label>
									<input type="text" class="form-control" ng-model="producto.idProducto" disabled>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<label class="control-label">Tipo Producto</label>
									<select class="form-control" ng-model="producto.idTipoProducto" required>
										<option ng-repeat="tp in lstTipoProducto" value="{{ tp.idTipoProducto }}">
											{{ tp.tipoProducto }}
										</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="control-label">Tipo Medida</label>
									<select class="form-control" ng-model="producto.idMedida" required>
										<option ng-repeat="m in lstMedidas" value="{{m.idMedida}}">{{m.medida}}</option>
									</select>
								</div>
								<div class="col-sm-1">
									<label class="control-label">Perecedero</label>
									<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.perecedero, 'btn-warning':!producto.perecedero}" ng-click="producto.perecedero=!producto.perecedero">
										<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.perecedero, 'glyphicon-check' : producto.perecedero}"></span>
										{{ producto.perecedero ? 'SI' : 'NO' }}
									</button>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Cantidad minima</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.cantidadMinima" required>
								</div>
								<label class="control-label col-sm-2">Cantidad maxima</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.cantidadMaxima">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Disponibilidad</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.disponibilidad" ng-disabled="accionProducto!='insert'">
								</div>
								<label class="control-label col-sm-2">Producto Importante</label>
								<div class="col-sm-1">
									<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.importante, 'btn-warning':!producto.importante}" ng-click="producto.importante=!producto.importante">
										<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.importante, 'glyphicon-check' : producto.importante}"></span>
										{{ producto.importante ? 'SI' : 'NO' }}
									</button>
								</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" ng-class="{'btn-success': accionProducto == 'insert', 'btn-info': accionProducto == 'update'}" ng-click="consultaProducto()" ng-disabled="loading">
						<span class="glyphicon glyphicon-saved"></span> Guardar (F6)
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

