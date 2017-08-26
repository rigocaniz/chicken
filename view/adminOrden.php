<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-8 col-xs-12">
			<div class="btn-orden">
				<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="cambioEstadoOrden( 1 )">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs"><u>P</u>endientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="cambioEstadoOrden( 2 )">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs"><u>E</u>n Preparación</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="cambioEstadoOrden( 3 )">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs"><u>F</u>inalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="cambioEstadoOrden( 10 )">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs"><u>C</u>ancelados</span>
				</button>
			</div>
		</div>
		<div class="col-sm-3 col-xs-12">
			<div class="btn-group" role="group">
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='menu', 'btn-default':tipoVista!='menu'}" 
					ng-click="cambiarVista( 'menu' )" title="Menú: M">
					<span class="glyphicon glyphicon-cutlery"></span>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='dividido', 'btn-default':tipoVista!='dividido'}" 
					ng-click="cambiarVista( 'dividido' )" title="Dividido: D">
					<b>Dividido</b>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='ticket', 'btn-default':tipoVista!='ticket'}" 
					ng-click="cambiarVista( 'ticket' )" title="Ticket: T">
					<span class="glyphicon glyphicon-bookmark"></span>
				</button>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:7px">
		<!-- VISTA POR TICKET -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='menu'}" ng-hide="tipoVista=='ticket'">
			<div class="panel-menu" ng-repeat="menu in lstMenus track by $index" ng-class="{'inactivo':ixMenuActual!=$index&&seleccionMenu.si}">
				<div class="header">
					<div class="col-xs-6">
						<button type="button" class="btn" ng-click="$parent.ixMenuActual = ( seleccionMenu.si ? $parent.ixMenuActual : $index )"
							ng-class="{'danger':difMinutos( menu.primerTiempo )>minutosAlerta}">
							<span class="index" ng-if="$index<=8">{{$index+1}}</span>
							<span class="glyphicon glyphicon-chevron-down"></span>
							<span class="badge">{{menu.numMenus}}</span>
							{{menu.menu}} 
						</button>
					</div>
					<div class="col-xs-6 text-right">
						<button type="button" class="btn btn-default" ng-click="selItemMenu( true )" ng-disabled="ixMenuActual!=$index" title="TODOS">
							<span class="glyphicon glyphicon-check"></span>
						</button>
						<button type="button" class="btn btn-default" ng-click="selItemMenu( false )" ng-disabled="ixMenuActual!=$index" title="NINGUNO">
							<span class="glyphicon glyphicon-unchecked"></span>
						</button>
					</div>
				</div>
				<div class="body" ng-show="ixMenuActual==$index">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Tipo Servicio</th>
										<th>Lapso</th>
										<th>Combo</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in menu.detalle track by $index" style="cursor:pointer" 
										ng-class="{'success':item.selected, 'tr_alert':difMinutos( item.fechaRegistro )>minutosAlerta}"
										ng-click="selItemMenu( !item.selected, $index )">
										<td>
											<span class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
	                                            <span ng-show="item.idTipoServicio==2">R</span>
	                                            <span ng-show="item.idTipoServicio==3">D</span>
	                                            <span ng-show="item.idTipoServicio==1">L</span>
	                                        </span>
											{{item.tipoServicio}}
										</td>
										<td>
											<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
										</td>
										<td>
											<span class="label label-warning" ng-show="item.perteneceCombo">
												<span class="glyphicon glyphicon-gift"></span>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- VISTA POR MENU -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='ticket'}" ng-hide="tipoVista=='menu'">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Menú</th>
							<th>Tipo Servicio</th>
							<th>Lapso</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in lstOrdenes" ng-click="item.selected=!item.selected;accionItems()" ng-class="{'success':item.selected}">
							<td><img ng-src="{{item.imagen}}" style="height:35px"></td>
							<td>
								<span class="glyphicon glyphicon-gift" ng-show="item.perteneceCombo"></span>
								{{item.menu}}
							</td>
							<td>{{item.tipoServicio}}</td>
							<td>
								<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- FIN VISTA POR MENU -->
	</div>

	<!-- SI TIENE SELECCIONADO ALGUN MENU -->
	<div class="acciones" ng-show="seleccionMenu.si">
		<div class="btn-accion">
			<!-- SELECCION POR TIPO DE SERVICIO -->
			<div class="tipo-servicio">
                <span ng-show="seleccionMenu.count.llevar>0" class="label label-primary">
                	L <span class="badge">{{seleccionMenu.count.llevar}}</span>
                </span>
				<span ng-show="seleccionMenu.count.restaurante>0" class="label label-success">
					R <span class="badge">{{seleccionMenu.count.restaurante}}</span>
				</span>
                <span ng-show="seleccionMenu.count.domicilio>0" class="label label-warning">
                	D <span class="badge">{{seleccionMenu.count.domicilio}}</span>
                </span>
			</div>
			<button type="button" class="btn btn-lg btn-success">
				<span class="glyphicon glyphicon-play"></span>
				Preparar
				<span class="badge" style="font-size:16px">{{seleccionMenu.count.total}}</span>
				<b><u>{{seleccionMenu.menu}}</u></b>
			</button>
		</div>
		<img ng-src="{{seleccionMenu.imagen}}">
	</div>
</div>


