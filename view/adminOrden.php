<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-xs-4">
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
		<div class="col-xs-4">
			<p title="Cocinero { ESPACIO }" ng-click="dialogoConsultaPersonal( 'menu' )" style="cursor:pointer">
				<span class="glyphicon glyphicon-cutlery"></span> <b>{{cocinero.nombre}}</b>
			</p>
			<p title="Mesero { alt + ESPACIO }" style="margin-bottom:0;cursor:pointer" ng-click="dialogoConsultaPersonal( 'ticket' )">
				<span class="glyphicon glyphicon-user"></span> <b>{{mesero.nombre}}</b>
			</p>
		</div>
		<div class="col-xs-3">
            <input type="number" class="form-control" ng-model="buscarTicket" id="buscarTicket" ng-class="{'input-focus':buscarTicket>0}"
                placeholder="# Ticket" style="font-size:19px;padding: 1px 14px;font-weight:normal">
		</div>
	</div>
	<div class="row" style="margin-top:7px">
		
		<!-- VISTA POR MENU -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='menu'}" ng-hide="tipoVista=='ticket'">
			<!-- ESTADO DE ORDEN -->
			<div class="col-xs-12">
				<div class="btn-orden">
					<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="cambioEstadoOrden( 1 )">
						<span class="glyphicon glyphicon-time"></span>
						<span class="hidden-xs"><u>P</u>endiente</span>
					</button>
					<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="cambioEstadoOrden( 2 )">
						<span class="glyphicon glyphicon-play"></span>
						<span class="hidden-xs"><u>C</u>ocinando</span>
					</button>
					<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="cambioEstadoOrden( 3 )">
						<span class="glyphicon glyphicon-ok"></span>
						<span class="hidden-xs"><u>L</u>isto</span>
					</button>
					<button class="bt-danger" ng-class="{'active':idEstadoOrden==4}" ng-click="cambioEstadoOrden( 4 )">
						<span class="glyphicon glyphicon-flag"></span>
						<span class="hidden-xs"><u>S</u>ervido</span>
					</button>
				</div>
			</div>

			<div class="panel-menu" ng-repeat="menu in lstMenus track by $index" ng-class="{'inactivo':ixMenuActual!=$index&&seleccionMenu.si}">
				<div class="encabezado">
					<div class="col-xs-6">
						<button type="button" class="btn" ng-click="$parent.ixMenuActual = ( seleccionMenu.si ? $parent.ixMenuActual : $index )"
							ng-class="{'danger':(difMinutos( menu.primerTiempo )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}">
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
										ng-class="{'success':item.selected, 'tr_alert':(difMinutos( item.fechaRegistro )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}"
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
		
		<!-- VISTA POR TICKET -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='ticket'}" ng-hide="tipoVista=='menu'">
			<!-- ESTADO DE ORDEN -->
			<div class="col-xs-12">
				<div class="btn-orden">
					<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="cambioEstadoOrden( 1 )">
						<span class="glyphicon glyphicon-time"></span>
						<span class="hidden-xs"><u>P</u>endiente</span>
					</button>
					<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="cambioEstadoOrden( 2 )">
						<span class="glyphicon glyphicon-play"></span>
						<span class="hidden-xs"><u>C</u>ocinando</span>
					</button>
					<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="cambioEstadoOrden( 3 )">
						<span class="glyphicon glyphicon-ok"></span>
						<span class="hidden-xs"><u>L</u>isto</span>
					</button>
					<button class="bt-danger" ng-class="{'active':idEstadoOrden==4}" ng-click="cambioEstadoOrden( 4 )">
						<span class="glyphicon glyphicon-flag"></span>
						<span class="hidden-xs"><u>S</u>ervido</span>
					</button>
				</div>
			</div>

			<div class="panel-menu" ng-repeat="ticket in lstTickets track by $index" ng-class="{'inactivo':ixTicketActual!=$index&&seleccionMenu.si}">
				<div class="encabezado">
					<div class="col-xs-6">
						<button type="button" class="btn" ng-click="$parent.ixTicketActual = ( seleccionMenu.si ? $parent.ixTicketActual : $index )"
							ng-class="{'danger':(difMinutos( ticket.primerTiempo )>ticket.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}">
							<span class="glyphicon glyphicon-chevron-down"></span>
							<span class="badge">{{ticket.numMenus}}</span>
	                        <span class="glyphicon glyphicon-bookmark"></span>
							{{ticket.numeroTicket}} 
						</button>
					</div>
					<div class="col-xs-6 text-right">
						<button type="button" class="btn btn-default" ng-click="selItemTicket( true )" ng-disabled="ixTicketActual!=$index" title="TODOS">
							<span class="glyphicon glyphicon-check"></span>
						</button>
						<button type="button" class="btn btn-default" ng-click="selItemTicket( false )" ng-disabled="ixTicketActual!=$index" title="NINGUNO">
							<span class="glyphicon glyphicon-unchecked"></span>
						</button>
					</div>
				</div>
				<div class="body" ng-show="ixTicketActual==$index">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Cantidad</th>
										<th>Orden</th>
										<th>Combo</th>
										<th>Tipo Servicio</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in ticket.lstMenu track by $index" style="cursor:pointer" 
										ng-class="{'success':item.selected, 'tr_alert':(difMinutos( item.fechaRegistro )>ticket.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}"
										ng-click="selItemTicket( !item.selected, $index )">
										<td>{{item.cantidad}}</td>
										<td>
											<span class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
	                                            <span ng-show="item.idTipoServicio==2">R</span>
	                                            <span ng-show="item.idTipoServicio==3">D</span>
	                                            <span ng-show="item.idTipoServicio==1">L</span>
	                                        </span>
											{{item.perteneceCombo?item.combo:item.menu}}
										</td>
										<td>
											<span class="label label-warning" ng-show="item.perteneceCombo">
												<span class="glyphicon glyphicon-gift"></span>
											</span>
										</td>
										<td>{{item.tipoServicio}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
		</div>
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


<!-- CAMBIO DE PERSONAL -->
<script type="text/ng-template" id="consulta.personal.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span ng-show="agruparPor=='menu'">
						<span class="glyphicon glyphicon-cutlery"></span>
                    	Cambiar Cocinero
                    </span>
                    <span ng-show="agruparPor=='ticket'">
						<span class="glyphicon glyphicon-user"></span>
                    	Cambiar Mesero
                    </span>
                </div>
                <div class="modal-body">
                	<div class="row">
	                    <label class="col-xs-2">Destino</label>
	                    <div class="col-xs-4">
	                    	<select ng-model="idDestinoMenu" class="form-control">
	                    		<option value="{{item.idDestinoMenu}}" ng-repeat="item in lstDestinoMenu">{{item.destinoMenu}}</option>
	                    	</select>
	                    </div>
	                    <label class="col-xs-2">Código</label>
	                    <div class="col-xs-3">
	                    	<input type="text" class="form-control" id="codigoPersonal" ng-model="$parent.codigoPersonal" ng-keydown="$event.keyCode==13 && consultarPersonal()">
	                    </div>
                	</div>
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

