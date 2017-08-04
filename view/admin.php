<div class="row">
	<div class="col-sm-12">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tabs-title" role="tablist">
			<li role="presentation" ng-class="{'active' : inventarioMenu==1}" ng-click="resetValues(); inventarioMenu=1">
				<a href="" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-list"></span> MENUS
				</a>
			</li>
			<li role="presentation" ng-class="{'active' : inventarioMenu==2}" ng-click="resetValues(); inventarioMenu=2">
				<a href="" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-list-alt"></span> COMBOS
				</a>
			</li>
			<li role="presentation" ng-class="{'active' : inventarioMenu==3}" ng-click="resetValues(); inventarioMenu=3">
				<a href="" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-book"></span> RECETAS
				</a>
			</li>
		</ul>

		{{ accion }}
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
								<button type="button" class="btn btn-success btn-sm" ng-click="agregarMenuCombo( null, 'menu' )">
									<span class="glyphicon glyphicon-plus"></span> INGRESAR MENU
								</button>
							</p>
						</div>
						<!-- TABLA -->
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th class="col-sm-3 text-center">Producto</th>
									<th class="col-sm-2 text-center">Tipo Producto</th>
									<th class="col-sm-1 text-center">Medida</th>
									<th class="col-sm-1 text-center">Perecedero</th>
									<th class="col-sm-2 text-center">Disponibilidad</th>
									<th class="text-center">Reajustar Cantidad</th>
									<th class="text-center">Editar</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="inv in lstInventario" ng-class="{'danger': !inv.disponibilidad, 'warning':  inv.disponibilidad <= (inv.cantidadMinima + 5) }">
									<td class="text-right">
										{{ inv.idProducto }}
									</td>
									<td>
										{{ inv.producto }}
									</td>
									<td class="text-center">
										{{ inv.tipoProducto }}
									</td>
									<td class="text-center">
										{{ inv.medida }}
									</td>
									<td class="text-center">
										{{ inv.esPerecedero }}
									</td>
									<td class="text-center">
										{{ inv.disponibilidad }}
									</td>
									<td class="text-center">
										<button type="button" ng-click="ingresarReajuste( inv.idProducto, inv.producto, inv.disponibilidad )" class="btn btn-primary btn-sm">
											<span class="glyphicon glyphicon-plus"></span>
										</button>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-info btn-sm" ng-click="editarAccion( inv.idProducto, 'update', producto )">
											<span class="glyphicon glyphicon-pencil"></span>
										</button>
										<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMninima">Pronto a agotarse</span>
									</td>
								</tr>
							</tbody>
						</table>
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

			<!-- TIPOS DE PRODUCTO -->
			<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==2}" ng-show="inventarioMenu==2">
				<div class="col-sm-offset-1 col-sm-10">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">TIPOS DE PRODUCTO</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<label class="col-sm-1 col-md-2">Tipo</label>
								<div class="col-sm-6">
									<input type="text" id="tipoProducto" ng-keyup="$event.keyCode == 13 && consultaTipoProducto()" class="form-control" ng-model="tp.tipoProducto" maxlength="45">
								</div>
								<div class="col-sm-5 col-md-4">
									<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaTipoProducto()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
									</button>
									<button type="button" class="btn btn-sm btn-default" ng-click="resetValues( 4 )">
										Cancelar
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-body">
							<h4 class="panel-title">
								<span class="glyphicon glyphicon-sort-by-attributes"></span> TIPOS REGISTRADOS
							</h4>
							<br>
							<div class="col-sm-4 col-md-6">
							</div>
							<div class="col-sm-8 col-md-6">
								<div class="input-group">
									<input type="text" class="form-control" ng-model="buscarTipoProducto" placeholder="Buscar tipo">
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
									<tr ng-repeat="tp in lstResultTipos = (lstTipoProducto | filter: buscarTipoProducto)">
										<td class="text-center">{{ $index + 1 }}</td>
										<td>{{ tp.tipoProducto }}</td>
										<td class="text-center">
											<button class="btn btn-info btn-sm" ng-click="editarTipoProducto( tp )">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="alert alert-info text-right" role="alert" ng-show="!lstResultTipos.length">
								<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
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
									<button type="button" class="btn btn-sm btn-default" ng-click="resetValues( 6 )">
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


<!-- MODAL AGREGAR / EDITAR PRODUCTO -->
<script type="text/ng-template" id="dialAdmin.ingreso.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" ng-class="{'panel-success': accion == 'insert', 'panel-info': accion == 'update'}">
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
								<div class="col-sm-7">
									<label class="control-label">Nombre Menu</label>
									<input type="text" class="form-control" ng-model="menu.menu" maxlength="45" required>
								</div>
								<div class="col-sm-5">
									<label class="control-label">Destino Menu</label>
									<select class="form-control" ng-model="menu.idDestinoMenu" required>
										<option ng-repeat="dm in lstDestinoMenu" value="{{dm.idDestinoMenu}}">{{dm.destinoMenu}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">Descripcion</label>
									<textarea rows="3" class="form-control" ng-model='menu.descripcion' required></textarea>
								</div>
							</div>
							<legend class="text-center">
								<small>
									<i class="fa fa-money" aria-hidden="true"></i> AGREGAR PRECIOS
								</small>
							</legend>
							<div class="form-group">
								<div class="col-sm-5">
									<label class="control-label">Tipo Servicio</label>
									<select class="form-control" ng-model="tipoServicio">
										<option ng-repeat="ts in lstTipoServicio" value="{{ ts.idTipoServicio }}">
											{{ ts.tipoServicio }}
										</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="control-label">Precio</label>
									<input type="number" min="0" class="form-control" ng-model="precioMenu">
								</div>
								<div class="col-sm-3">
									<br>
									<button type="button" class="btn btn-sm btn-primary" ng-click="agregaPrecio(tipoServicio,precioMenu)">
										<span class="glyphicon glyphicon-plus"></span>
										Agregar
									</button>
								</div>
								<div class="col-sm-12">
									<br>
									<div class="col-sm-offset-1 col-sm-10">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>No.</th>
													<th>Tipo Servicio</th>
													<th>Q. Precio</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="lp in menu.lstPrecios">
													<td>{{lp.tipoServicio}}</td>
													<td>{{lp.precio}}</td>
													<td>
														<button type="button" class="btn btn-danger btn-xs" ng-click="removerPrecio( $index )">X</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="button" class="btn btn-success" ng-click="registraMenu()">Guardar</button>
								<button type="button" class="btn btn-danger" ng-click="menu={}">Cancelar</button>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaProducto()">
						<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValues( 1 ); $hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>


<div class="container">
	<div class="row">

		<!--  menus -->
		<div class="col-sm-12" ng-show="btnMenu==1">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Menus</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7"></div>
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/menu">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
					<div class="row">
						<br>
					  <div class="col-sm-3" ng-repeat="m in lstMenus |filter:filtro">
					    <div class="thumbnail">
					    	<span class="label label-info">{{m.destinoMenu}}</span>
					      <img src="upload/Logo.png" alt="...">
					      <div class="caption">
					        <p>
					        	<strong>{{m.menu}}</strong> 
					        	<span class="label label-success">{{m.estadoMenu}}</span>
					        </p> 
					        <p>{{m.descripcion}}</p>
					        <p>
					        	<a ng-href="#/nuevoEdita/menu/{{m.idMenu}}" type="button" class="btn btn-primary btn-sm">
									<span class="glyphicon glyphicon-edit"></span> Editar
								</a>
								<a href="#" type="button" class="btn btn-info btn-sm">
									<span class="glyphicon glyphicon-list"></span> Receta
								</a>
							</p>
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<!-- combos -->
		<div class="col-sm-12" ng-show="btnMenu==2">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Menus</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7"></div>
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/combo">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
					<div class="row">
						<br>
					  <div class="col-sm-3" ng-repeat="c in lstCombos |filter:filtro">
					    <div class="thumbnail">
					      <img src="upload/Logo.png" alt="...">
					      <div class="caption">
					        <p>
					        	<strong>{{c.combo}}</strong> 
					        	<span class="label label-success">{{c.estadoMenu}}</span>
					        </p> 
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
	</div>
</div>