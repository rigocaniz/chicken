{{}}
	<div class="row">
		<!-- Definir botones de inventario menu -->
		<div class="col-sm-12 text-center">
			<div class="btn-group">
				<button class="btn btn-default" ng-click="inventarioMenu=1;inventario();">
					<span class="glyphicon glyphicon-list"></span> Inventario
				</button>
				<button class="btn btn-default" ng-click="inventarioMenu=2;catTipoProducto();">
					<span class="glyphicon glyphicon-share-alt"></span> Tipo Producto
				</button>
				<button class="btn btn-default" ng-click="inventarioMenu=3;catMedidas();"> 
					<span class="glyphicon glyphicon-star-empty"></span> Medidas
				</button>
			</div>
		</div>
		<!--  tabla de porductos existentes -->
		<div class="col-sm-12" ng-show="inventarioMenu==0 || inventarioMenu==1">
			<br>

			<nav>
			  <ul class="pagination">
			
			<!--
			    <li ng-repeat="(ixPagina, pagina) in lstPaginacion">
			      	<a href="" aria-label="Previous">
			        	<span aria-hidden="true">&laquo;</span>
			      	</a>
			    </li>
			-->
			    <li ng-repeat="(ixPagina, pagina) in lstPaginacion">
			    	<a href="" ng-click="cargarPaginacion( pagina.noPagina );">
			    		{{ pagina.noPagina }}
			    	</a>
			    </li>
			    <!--
			    <li>
			      <a href="" aria-label="Next">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			    -->
			</ul>
			</nav>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Inventario de productos</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7">
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/producto">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
						
					</div>
					
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="col-sm-3 text-center">Producto</th>
								<th class="col-sm-3 text-center">Tipo Producto</th>
								<th class="col-sm-1 text-center">Medida</th>
								<th class="col-sm-1 text-center">Perecedero</th>
								<th class="col-sm-2 text-center">Disponibilidad</th>
								<th class="col-sm-2 text-center"></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="inv in lstInventario" ng-class="{'danger': !inv.disponibilidad, 'warning': (inv.cantidadMinima + 5) == inv.disponibilidad}">
								<td>
									{{ inv.idProducto }}
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
									<a ng-click="ingresar(inv.idProducto,inv.producto)" type="button" class="btn btn-success btn-sm">
										<span class="glyphicon glyphicon-plus"></span>
									</a>
									<a ng-href="#/nuevoEdita/producto/{{inv.idProducto}}" type="button" class="btn btn-primary btn-sm">
										<span class="glyphicon glyphicon-edit"></span>
									</a>
									<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMninima">Pronto a agotarse</span>
								</td>
							</tr>
						</tbody>
					</table>
					<nav>
					  	<ul class="pagination">
						    <li ng-repeat="(ixPagina, pagina) in lstPaginacion">
						    	<a href="" ng-click="cargarPaginacion( pagina.noPagina );">
						    		{{ pagina.noPagina }}
						    	</a>
						    </li>
						</ul>
					</nav>

				</div>
			</div>
		</div>
		<!-- tipo de producto -->
		<div class="col-sm-6 col-sm-offset-3" ng-show="inventarioMenu==2">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Tipos de producto</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-sm-1">Tipo</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" ng-model="tipoProducto" maxlength="45">
						</div>
						<div class="col-sm-5">
							<button type="button" class="btn btn-success" ng-click="registraTipoProdcuto(tipoProducto)">Guradar</button>
							<button type="button" class="btn btn-default" ng-click="tipoProducto=''">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<b>Tipos Registrados</b>
					<ul class="list-group">
						<li class="list-group-item" ng-repeat="tp in lstTipoProducto">{{tp.tipoProducto}}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- medida de producto -->
		<div class="col-sm-6 col-sm-offset-3" ng-show="inventarioMenu==3">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Medida de producto</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-sm-2">Medida</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="medidaProducto" maxlength="45">
						</div>
						<div class="col-sm-5">
							<button type="button" class="btn btn-success" ng-click="registraMedidaProducto(medidaProducto)">Guradar</button>
							<button type="button" class="btn btn-default" ng-click="medidaProducto='';">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<b>Medidas Registradas</b>
					<ul class="list-group">
						<li class="list-group-item" ng-repeat="m in lstMedidas">{{m.medida}}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- ingreso de productos a inventario -->
		<div class="col-sm-12" ng-show="inventarioMenu==4">
			<br>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Ingreso de productos</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-1">Producto</label>
							<div class="col-sm-4">
								<input type="text" class="form-control">
							</div>
							<label class="col-sm-1">Cantidad</label>
							<div class="col-sm-2">
								<input type="number" class="form-control">
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary">Ingresar</button>
								<button type="button" class="btn btn-default">Cancelar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<!-- dialogo ingreso a existencias -->
<script type="text/ng-template" id="dial.ingreso.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                    Ingresar existencia de {{nombreProducto}}
                </div>
                <div class="modal-body">
                	<div class="form-group">
	                    <label class="col-sm-2">Cantidad</label>
	                    <div class="col-sm-3">
	                    	<input type="number" class="form-control" ng-model="$parent.cantidad">
	                    </div>
                   </div>
                   <br>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" ng-click="guardaIngreso(idProducto,$parent.cantidad)">
                        <span class="glyphicon glyphicon-ok"></span> Guardar
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