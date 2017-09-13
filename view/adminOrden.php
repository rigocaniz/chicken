<div class="div-fixed">
	<div class="row">
		<div class="col-xs-3">
			<div class="btn-group" role="group">
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='menu', 'btn-default':tipoVista!='menu'}" 
					ng-click="cambiarVista( 'menu' )" title="{ ALT + M }">
					<span class="glyphicon glyphicon-cutlery"></span>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='dividido', 'btn-default':tipoVista!='dividido'}" 
					ng-click="cambiarVista( 'dividido' )" title="{ ALT + D }">
					<b>Dividido</b>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='ticket', 'btn-default':tipoVista!='ticket'}" 
					ng-click="cambiarVista( 'ticket' )" title="{ ALT + T }">
					<span class="glyphicon glyphicon-bookmark"></span>
				</button>
			</div>
		</div>
		<div class="col-xs-5">
			<div class="col-xs-7 text-right">
				<button type="button" class="btn btn-sm btn-default" title="{ ALT + U }" ng-click="dialogoConsultaPersonal( 'menu' )">
					<span class="glyphicon glyphicon-cutlery"></span> 
					<b>{{user.nombres}} {{user.apellidos}}</b>
				</button>
			</div>
			<div class="col-xs-5">
				<select class="form-control input-sm" ng-model="idDestinoMenu">
					<option value="{{item.idDestinoMenu}}" ng-repeat="item in user.lstDestinos">{{item.destinoMenu}}</option>
				</select>
			</div>
		</div>
		<div class="col-xs-3">
			<button type="button" class="btn btn-xs btn-info" ng-click="dAyuda.show()">
				<span class="glyphicon glyphicon-question-sign"></span>
			</button>
			<!--
            <input type="number" class="form-control" ng-model="buscarTicket" id="buscarTicket" ng-class="{'input-focus':buscarTicket>0}"
                placeholder="# Ticket" style="font-size:19px;padding: 1px 14px;font-weight:normal">-->
		</div>
	</div>
	<div class="row" style="margin-top:3px">
		<!-- ############ VISTA POR MENU ############ -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='menu'}" ng-hide="tipoVista=='ticket'">
			<!-- ESTADO DE ORDEN -->
			<div class="col-xs-12 text-center" style="margin-top:4px">
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
		</div>

		<!-- ############ VISTA POR TICKET ############ -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='ticket'}" ng-hide="tipoVista=='menu'">
			<!-- ESTADO DE ORDEN -->
			<div class="col-xs-12 text-center" style="margin-top:4px">
				<div class="btn-orden">
					<button class="bt-info" ng-class="{'active':idEstadoOrdenTk==1}" ng-click="cambioEstadoOrden( 1, 'ticket' )">
						<span class="glyphicon glyphicon-time"></span>
						<span class="hidden-xs">Tic<u>k</u>ets Pendientes</span>
					</button>
					<button class="bt-danger" ng-class="{'active':idEstadoOrdenTk==4}" ng-click="cambioEstadoOrden( 4, 'ticket' )">
						<span class="glyphicon glyphicon-flag"></span>
						<span class="hidden-xs"><u>F</u>inalizados</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- INFORMACION DE: MENU | TICKET -->
<div class="col-xs-12 contenido-admin-orden">
	<div class="row">
		<!-- ############ VISTA POR MENU ############ -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='menu','active-key':keyPanel=='left','inactive-key':keyPanel!='left'}" 
			ng-hide="tipoVista=='ticket'" class="contenido-lst-orden">

			<!-- SI NO SE ENCONTRO INFORMACION -->
			<div class="col-sm-12" ng-hide="lstMenus.length">
				<h4 class="alert alert-info">No existe información</h4>
			</div>

			<div class="panel-menu" ng-repeat="menu in lstMenus track by $index" ng-class="{'inactivo':ixMenuActual!=$index&&seleccionMenu.si}" id="ixm_{{$index}}">
				<div class="encabezado">
					<div class="col-xs-6">
						<button type="button" class="btn" ng-click="$parent.ixMenuActual=($parent.ixMenuActual==$index?-1:$index)" ng-disabled="seleccionMenu.si || seleccionTicket.si"
							ng-class="{'danger':(difMinutos( menu.primerTiempo )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}">
							<span class="glyphicon" ng-class="{'glyphicon-chevron-down':$parent.ixMenuActual==$index, 'glyphicon-chevron-right':$parent.ixMenuActual!=$index}"></span>
							<span class="badge">{{menu.numMenus}}</span>
							{{menu.menu}} 
						</button>
					</div>
					<div class="col-xs-6 text-right">
						<button type="button" class="btn btn-sm btn-default" ng-click="selItemMenu( true )" ng-disabled="ixMenuActual!=$index || seleccionTicket.si" title="TODOS">
							<span class="glyphicon glyphicon-check"></span>
						</button>
						<button type="button" class="btn btn-sm btn-default" ng-click="selItemMenu( false )" ng-disabled="ixMenuActual!=$index || seleccionTicket.si" title="NINGUNO">
							<span class="glyphicon glyphicon-unchecked"></span>
						</button>
					</div>
				</div>
				<div class="body body_lst_menu" ng-show="ixMenuActual==$index">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Tipo Servicio</th>
										<th>Lapso</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in menu.detalle track by $index" style="cursor:pointer;position: relative;" 
										ng-class="{'success':item.selected, 'tr_alert':(difMinutos( item.fechaRegistro )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) ),'tr_focus':$parent.ixMenuFocus==$index}"
										ng-click="selItemMenu( !item.selected, $index )"
										id="ixm_item_{{item.idDetalleOrdenMenu}}">
										<td>
											<span class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
	                                            <span ng-show="item.idTipoServicio==2">R</span>
	                                            <span ng-show="item.idTipoServicio==3">D</span>
	                                            <span ng-show="item.idTipoServicio==1">L</span>
	                                        </span>
											{{item.tipoServicio}}
											<span class="label-ticket">
												<span class="glyphicon glyphicon-bookmark"></span>
												{{item.numeroTicket}}
											</span>
											<span class="label label-warning" ng-show="item.perteneceCombo">
												<span class="glyphicon glyphicon-gift"></span>
											</span>
										</td>
										<td>
											<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div><!-- >>> VISTA POR MENU <<< -->
		
		<!-- ############ VISTA POR TICKET ############ -->
		<div ng-class="{'col-sm-6':tipoVista=='dividido','col-sm-12':tipoVista=='ticket','active-key':keyPanel=='right','inactive-key':keyPanel!='right'}" 
			ng-hide="tipoVista=='menu'" class="contenido-lst-orden">
			<!-- SI NO SE ENCONTRO INFORMACION -->
			<div class="col-sm-12" ng-hide="lstTickets.length">
				<h4 class="alert alert-info" ng-hide="lstTickets.length">No existe información</h4>
			</div>

			<div class="panel-menu" ng-repeat="ticket in lstTickets track by $index"
				ng-class="{'inactivo':ixTicketActual!=$index&&seleccionTicket.si}" id="ixt_{{$index}}">
				<div class="encabezado">
					<div class="col-xs-4">
						<button type="button" class="btn" ng-click="$parent.ixTicketActual=($parent.ixTicketActual==$index?-1:$index)"
							ng-class="{'danger':(difMinutos( ticket.primerTiempo )>ticket.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}"
							ng-disabled="seleccionMenu.si || seleccionTicket.si">
							<span class="glyphicon glyphicon-chevron-down"></span>
							<!--<span class="badge">{{ticket.numMenus}}</span>-->
							{{ticket.numeroTicket}} 
	                        <span class="glyphicon glyphicon-bookmark"></span>
						</button>
					</div>
					<div class="col-xs-8 text-right">
						<span class="estado-menu default">P <span class="badge">{{ticket.total.pendientes}}</span></span>
						<span class="estado-menu info">C <span class="badge">{{ticket.total.cocinando}}</span></span>
						<span class="estado-menu primary">L <span class="badge">{{ticket.total.listos}}</span></span>
						<span class="estado-menu success">S <span class="badge">{{ticket.total.servidos}}</span></span>

						<button type="button" class="btn btn-sm btn-default" ng-click="selItemTicket( true )" ng-disabled="ixTicketActual!=$index || seleccionMenu.si" title="TODOS">
							<span class="glyphicon glyphicon-check"></span>
						</button>
						<button type="button" class="btn btn-sm btn-default" ng-click="selItemTicket( false )" ng-disabled="ixTicketActual!=$index || seleccionMenu.si" title="NINGUNO">
							<span class="glyphicon glyphicon-unchecked"></span>
						</button>
					</div>
				</div>
				<div class="body body_lst_ticket" ng-show="ixTicketActual==$index">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th></th>
										<th>Orden</th>
										<th>Lapso</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in ticket.detalle track by $index" style="cursor:pointer" 
										ng-class="{'success':item.selected,'tr_focus':$parent.ixTicketFocus==$index,
											'tr_alert':(difMinutos( item.fechaRegistro )>ticket.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}"
										ng-click="selItemTicket( !item.selected, $index )"
										id="ixt_item_{{item.idDetalleOrdenMenu}}">
										<td>
											<span class="estado-menu default" ng-show="item.idEstadoDetalleOrden==1">P</span>
											<span class="estado-menu info" ng-show="item.idEstadoDetalleOrden==2">C</span>
											<span class="estado-menu primary" ng-show="item.idEstadoDetalleOrden==3">L</span>
											<span class="estado-menu success" ng-show="item.idEstadoDetalleOrden==4">S</span>
										</td>
										<td>
											<span class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
	                                            <span ng-show="item.idTipoServicio==2">R</span>
	                                            <span ng-show="item.idTipoServicio==3">D</span>
	                                            <span ng-show="item.idTipoServicio==1">L</span>
	                                        </span>
											{{item.descripcion}}
											<span class="label label-warning" ng-show="item.perteneceCombo">
												<span class="glyphicon glyphicon-gift"></span>
											</span>
										</td>
										<td>
											<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div><!-- >>> VISTA POR TICKET <<< -->
	</div>
</div>


<!-- SI TIENE SELECCIONADO ALGUN MENU -->
<div class="acciones" ng-show="seleccionMenu.si">
	<div class="btn-accion">
		<button type="button" class="btn btn-lg btn-success" ng-click="continuarProcesoMenu()">
			<span ng-show="idEstadoOrden==1">
				<span class="glyphicon glyphicon-play"></span>
				Cocinar
			</span>
			<span ng-show="idEstadoOrden==2">
				<span class="glyphicon glyphicon-ok"></span>
				Listo
			</span>
			<span ng-show="idEstadoOrden==3">
				<span class="glyphicon glyphicon-flag"></span>
				Servir
			</span>
			<span class="badge" style="font-size:16px">{{seleccionMenu.count.total}}</span>
			<b><u>{{seleccionMenu.menu}}</u></b>
		</button>
		<!-- SELECCION POR TIPO DE SERVICIO -->
		<div class="tipo-servicio">
            <span class="estado-menu primary" ng-show="seleccionMenu.count.llevar>0">
            	L <span class="badge">{{seleccionMenu.count.llevar}}</span>
            </span>
			<span class="estado-menu success" ng-show="seleccionMenu.count.restaurante>0">
				R <span class="badge">{{seleccionMenu.count.restaurante}}</span>
			</span>
            <span class="estado-menu warning" ng-show="seleccionMenu.count.domicilio>0">
            	D <span class="badge">{{seleccionMenu.count.domicilio}}</span>
            </span>
		</div>
	</div>
	<img ng-src="{{seleccionMenu.imagen}}">
</div>

<!-- SI TIENE SELECCIONADO ALGUN TICKET -->
<div class="acciones" ng-show="seleccionTicket.si">
	<div class="btn-accion">
		<button type="button" class="btn btn-lg btn-success" ng-click="continuarProcesoMenu()">
			Servir
			<span class="badge" style="font-size:16px">{{seleccionTicket.count.total}}</span>
		</button>
		<!-- SELECCION POR TIPO DE SERVICIO -->
		<div class="tipo-servicio">
            <span class="estado-menu primary" ng-show="seleccionTicket.count.llevar>0">
            	L <span class="badge">{{seleccionTicket.count.llevar}}</span>
            </span>
			<span class="estado-menu success" ng-show="seleccionTicket.count.restaurante>0">
				R <span class="badge">{{seleccionTicket.count.restaurante}}</span>
			</span>
            <span class="estado-menu warning" ng-show="seleccionTicket.count.domicilio>0">
            	D <span class="badge">{{seleccionTicket.count.domicilio}}</span>
            </span>
		</div>
	</div>
	<div class="ticket">
		<span class="glyphicon glyphicon-bookmark"></span>
		<span>{{seleccionTicket.numeroTicket}}</span>
	</div>
</div>


<!-- CAMBIO DE PERSONAL -->
<script type="text/ng-template" id="consulta.personal.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-user"></span>
                	Cambiar Usuario
                </div>
                <div class="modal-body">
                	<div class="row">
	                    <div class="col-xs-10 col-xs-offset-2">
		                    <label class="col-xs-3">
								<span class="glyphicon glyphicon-user"></span>
		                    	Código
		                    </label>
		                    <div class="col-xs-4">
		                    	<input type="number" class="form-control" id="codigoPersonal" ng-model="$parent.codigoPersonal" 
		                    		ng-keydown="$event.keyCode==13 && nextElement()">
		                    </div>
		                </div>
		            </div>
                	<div class="row" style="margin-top:5px">
	                    <div class="col-xs-10 col-xs-offset-2">
		                    <label class="col-xs-3">
								<span class="glyphicon glyphicon-lock"></span>
		                    	Clave
		                    </label>
		                    <div class="col-xs-4">
		                    	<input type="password" class="form-control" id="clave" ng-model="$parent.clave" ng-keydown="$event.keyCode==13 && consultarPersonal()">
		                    </div>
		                </div>
                	</div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="consultarPersonal()">
                        <span class="glyphicon glyphicon-log-in"></span>
                        <b>Cambiar Usuario</b>
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

<!-- AYUDA -->
<script type="text/ng-template" id="ayuda.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-question-sign"></span>
                	Atajos de Teclado
                </div>
                <div class="modal-body">
                	<div class="row">
	                    <div class="col-xs-6">
							<legend>Atajos en Ordenes (Menú|Ticket)</legend>
	                    	<ul class="list-group">
								<li class="list-group-item"><kbd>→</kbd> Enfoca Grupo Siguiente</li>
								<li class="list-group-item"><kbd>←</kbd> Enfoca Grupo Anterior</li>
								<li class="list-group-item"><kbd>Retroceso(BACKSPACE)</kbd> Deselecciona Grupo</li>
								<li class="list-group-item"><kbd>T</kbd> Selecciona Todas las Ordenes</li>
								<li class="list-group-item"><kbd>N</kbd> Deselecciona Todas las Ordenes</li>
								<li class="list-group-item"><kbd>+</kbd> Selecciona Asc. la próxima Orden No Seleccionada</li>
								<li class="list-group-item"><kbd>-</kbd> Deselecciona Des. la próxima Orden Seleccionada</li>
								<li class="list-group-item"><kbd>↓</kbd> Enfoca Orden Siguiente</li>
								<li class="list-group-item"><kbd>↑</kbd> Enfoca Orden Anterior</li>
								<li class="list-group-item"><kbd>ESPACIO</kbd> Des/Selecciona Orden Enfocada</li>
								<li class="list-group-item"><kbd>ALT + ENTER</kbd> Confirma Acción de Selección Actual</li>
							</ul>
	                    	<legend>Cambio de Usuario</legend>
		                    <ul class="list-group">
								<li class="list-group-item"><kbd>ALT + U</kbd> Cambio Rápido de Usuario</li>
							</ul>
						</div>
	                    <div class="col-xs-6">
	                    	<legend>Vista Ordenes</legend>
	                    	<ul class="list-group">
								<li class="list-group-item"><kbd>ALT + M</kbd> Muestra Ordenes Por Menú</li>
								<li class="list-group-item"><kbd>ALT + D</kbd> Muestra Ordenes Por Menú/Ticket</li>
								<li class="list-group-item"><kbd>ALT + T</kbd> Muestra Ordenes Por Ticket</li>
								<li class="list-group-item"><kbd>ALT + →</kbd> Enfoca Ordenes Por Ticket (Modo Dividido)</li>
								<li class="list-group-item"><kbd>ALT + ←</kbd> Enfoca Ordenes Por Menú (Modo Dividido)</li>
							</ul>
							<legend>Ordenes Por Estado</legend>
	                    	<ul class="list-group">
								<li class="list-group-item"><kbd>P</kbd> Ordenes Pendientes</li>
								<li class="list-group-item"><kbd>C</kbd> Ordenes Cocinando</li>
								<li class="list-group-item"><kbd>L</kbd> Ordenes Listas</li>
								<li class="list-group-item"><kbd>S</kbd> Ordenes Servidas</li>
							</ul>
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

