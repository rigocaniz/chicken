<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 2 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="div-fixed">
	<div class="row">
		<div class="col-xs-3">
			<div class="btn-group" role="group">
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='menu', 'btn-default':tipoVista!='menu'}" 
					ng-click="cambiarVista( 'menu' )" title="{ ALT + M }">
					<span class="glyphicon glyphicon-cutlery"></span>
					Cocina
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='ticket', 'btn-default':tipoVista!='ticket'}" 
					ng-click="cambiarVista( 'ticket' )" title="{ ALT + T }">
					<span class="glyphicon glyphicon-bookmark"></span>
					Mesero
				</button>
			</div>
		</div>
		<div class="col-xs-5">
			<div class="col-xs-7">
				<label>Grupo:</label>
				<select class="form-control input-sm" ng-model="numeroGrupo">
					<option value="{{grp.numeroGrupo}}" ng-repeat="grp in lstGrupos">{{grp.grupo}}</option>
				</select>
			</div>
			<div class="col-xs-5">
				<label>Destino:</label>
				<select class="form-control input-sm" ng-model="idDestinoMenu">
					<option value="{{dest.idDestinoMenu}}" ng-repeat="dest in lstDestinoMenu">{{dest.destinoMenu}}</option>
				</select>
			</div>
		</div>
		<div class="col-xs-3">
			<button type="button" class="btn btn-xs btn-info" ng-click="dAyuda.show()">
				<span class="glyphicon glyphicon-question-sign"></span>
			</button>
		</div>
	</div>
	<div class="row" style="margin-top:3px">
		<!-- ############ VISTA POR MENU ############ -->
		<div class="col-sm-12" ng-hide="tipoVista=='ticket'">
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
		<div class="col-sm-12" ng-hide="tipoVista=='menu'">
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
		<div class="col-sm-12 contenido-lst-orden" ng-class="{'active-key':keyPanel=='left','inactive-key':keyPanel!='left'}" ng-hide="tipoVista=='ticket'">

			<!-- SI NO SE ENCONTRO INFORMACION -->
			<div class="col-sm-12" ng-hide="lstMenus.length">
				<h4 class="alert alert-info">Aún No existe ordenes para preparar <span class="glyphicon glyphicon-time"></span></h4>
			</div>

			<div class="panel-menu" ng-repeat="menu in lstMenus track by $index" ng-class="{'inactivo':ixMenuActual!=$index&&seleccionMenu.si}" id="ixm_{{$index}}">
				<div class="encabezado">
					<div class="col-xs-8">
						<button type="button" class="btn btn-lg"
							ng-click="$parent.ixMenuActual=($parent.ixMenuActual==$index?-1:$index)" 
							ng-disabled="seleccionCocina.si && seleccionCocina.index!=$index"
							ng-class="{'danger':(difMinutos( menu.primerTiempo )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) )}">
							<span class="glyphicon" ng-class="{'glyphicon-chevron-down':$parent.ixMenuActual==$index, 'glyphicon-chevron-right':$parent.ixMenuActual!=$index}"></span>
							<span class="badge">{{menu.total}}</span>
							{{menu.menu}} <b>#{{menu.codigoMenu}}</b>
						</button>
						<!-- INGRESO DE MENUS A COCINAR -->
						<input type="number" class="form-control input-lg" ng-model="menu.seleccionados" style="width:150px;display:inline-block" 
							placeholder="# Productos" id="input_{{$index}}" ng-change="cantidadCocinar( menu, $index )"
							ng-disabled="seleccionCocina.si && seleccionCocina.index!=$index" ng-show="idEstadoOrden<4">
					</div>
					<div class="col-xs-4 text-right" ng-show="idEstadoOrden<4">
						<button type="button" class="btn btn-sm btn-default" ng-click="menu.seleccionados=menu.total;cantidadCocinar( menu, $index )" 
							ng-disabled="seleccionCocina.si && seleccionCocina.index!=$index" title="TODOS">
							<span class="glyphicon glyphicon-check"></span>
						</button>
						<button type="button" class="btn btn-sm btn-default" ng-click="menu.seleccionados='';cantidadCocinar( menu, $index )" 
							ng-disabled="seleccionCocina.si && seleccionCocina.index!=$index" title="NINGUNO">
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
										<th>Cantidad</th>
										<th>#Orden</th>
										<th>#Ticket</th>
										<th>Lapso</th>
										<th>Observación</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in menu.lstOrden track by $index" style="cursor:pointer;position: relative;" 
										ng-class="{
											'success':item.seleccionados==item.total,
											'warning':(item.seleccionados>0) && item.seleccionados!=item.total,
											'tr_alert':!(item.seleccionados>0) && difMinutos( item.fechaRegistro )>menu.tiempoAlerta && ( idEstadoOrden==1 || idEstadoOrden==2 ) }"
										id="ixm_{{menu.idMenu}}_{{item.idOrdenCliente}}">
										<td><span class="tdTotal">{{item.total}}</span></td>
										<td><h4>#{{item.idOrdenCliente}}</h4></td>
										<td>
											<span class="label-ticket" ng-show="item.numeroTicket>0" style="font-size:15px">
												<span class="glyphicon glyphicon-bookmark"></span>
												{{item.numeroTicket}}
											</span>
										</td>
										<td><h5>{{tiempoTranscurrido( item.fechaRegistro )}}</h5></td>
										<td>
											<h4 ng-show="item.observacion.length">
												<span class="label label-warning">
													<span class="glyphicon glyphicon-star"></span>
													{{item.observacion}}
												</span>
											</h4>
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
		<div class="col-sm-12 contenido-lst-orden" ng-class="{'active-key':keyPanel=='right','inactive-key':keyPanel!='right'}" ng-hide="tipoVista=='menu'">
			<!-- SI NO SE ENCONTRO INFORMACION -->
			<div class="col-sm-12" ng-hide="lstTickets.length">
				<h4 class="alert alert-info" ng-hide="lstTickets.length">No existe información</h4>
			</div>

			<div class="panel-menu" ng-repeat="ticket in lstTickets track by $index"
				ng-class="{'inactivo':ixTicketActual!=$index&&seleccionTicket.si}" id="ixt_{{$index}}">
				<div class="encabezado">
					<div class="col-xs-5">
						<button type="button" class="btn btn2 btn-lg" ng-click="$parent.ixTicketActual=($parent.ixTicketActual==$index?-1:$index)"
							ng-disabled="seleccionMenu.si || seleccionTicket.si">
							<span class="glyphicon glyphicon-chevron-right" ng-show="$parent.ixTicketActual!=$index"></span>
							<span class="glyphicon glyphicon-chevron-down" ng-show="$parent.ixTicketActual==$index"></span>
							<b>#{{ticket.idOrdenCliente}}</b>
							<span class="label label-primary" ng-show="ticket.numeroTicket>0">
								{{ticket.numeroTicket}} 
		                        <span class="glyphicon glyphicon-bookmark"></span>
							</span>
						</button>
						<strong>{{tiempoTranscurrido( ticket.primerTiempo )}}</strong>
					</div>
					<div class="col-xs-7 text-right">
						<kbd style="font-size:15px;font-weight:bold">TOTAL = {{ticket.total}}</kbd>
						<span class="estado-menu default">P <span class="badge">{{ticket.pendientes}}</span></span>
						<span class="estado-menu info">C <span class="badge">{{ticket.cocinando}}</span></span>
						<span class="estado-menu primary">L <span class="badge">{{ticket.listos}}</span></span>
						<span class="estado-menu success">S <span class="badge">{{ticket.servidos}}</span></span>
						<!-- <button class="btn btn-primary" type="button" ng-show="ixTicketActual==$index">
							<b>Servir TODO</b>
							<span class="glyphicon glyphicon-flag"></span>
						</button> -->
					</div>
				</div>
				<div class="body body_lst_ticket" ng-if="ixTicketActual==$index">
					<!-- DETALLE TICKET -->
					<div class="col-xs-12">
	                    <div class="table-responsive">
	                        <table class="table table-hover">
	                            <thead>
	                                <tr>
	                                    <th></th>
	                                    <th>Cantidad</th>
	                                    <th>Orden</th>
	                                    <th style="width:165px">Acción</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <tr ng-repeat="orden in ticketActual.lstOrden track by $index">
	                                    <td>
	                                        <img ng-src="{{orden.imagen}}" style="height:40px">
	                                    </td>
	                                    <td>
	                                    	<h4><b>{{orden.cantidad}}</b></h4>
	                                    </td>
	                                    <td>
	                                        <button type="button" class="label-border" ng-class="{'btn-success':orden.idTipoServicio==2, 'btn-warning':orden.idTipoServicio==3, 'btn-primary':orden.idTipoServicio==1}">
	                                            <span ng-show="orden.idTipoServicio==2" title="Restaurante" data-toggle="tooltip" data-placement="top" tooltip>R</span>
	                                            <span ng-show="orden.idTipoServicio==3" title="A Domicilio" data-toggle="tooltip" data-placement="top" tooltip>D</span>
	                                            <span ng-show="orden.idTipoServicio==1" title="Para Llevar" data-toggle="tooltip" data-placement="top" tooltip>L</span>
	                                        </button>
	                                        <span style="font-size:16px">
		                                        <span class="glyphicon glyphicon-gift" ng-show="orden.esCombo"></span>
		                                        <span>{{orden.descripcion}}</span>
	                                        </span>
	                                        <span class="estado-menu {{est.css}}" ng-repeat="est in orden.estados" title="{{est.title}}" data-toggle="tooltip" data-placement="top" tooltip>{{est.abr}} <span class="badge">{{est.total}}</span></span>
	                                        <h4 ng-show="orden.observacion.length">
												<span class="label label-warning">
													<span class="glyphicon glyphicon-star"></span>
													{{orden.observacion}}
												</span>
											</h4>
	                                    </td>
	                                    <td>
											<h4>
												<span class="estado-menu success" ng-hide="orden.limite"><b>Completo</b></span>
											</h4>
											<div class="input-group" ng-show="orden.limite">
												<span class="input-group-btn">
													<button class="btn btn-success" type="button" ng-click="servirMenu( $index )">
														<b>Servir</b>
														<span class="glyphicon glyphicon-flag"></span>
													</button>
												</span>
												<input type="number" class="form-control" ng-keyup="$event.keyCode==117 && servirMenu( $index )" 
													ng-model="orden.seleccionados" style="font-weight:bold;font-size:19px;padding:0 7px" 
													ng-max="orden.limite" ng-min="0" focus-enter id="detalle_orden_{{$index}}">
											</div>
	                                    </td>
	                                </tr>
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
					<!-- DETALLE TICKET -->
				</div>
			</div>
		</div><!-- >>> VISTA POR TICKET <<< -->
	</div>
</div>


<!-- SI TIENE SELECCIONADO ALGUN MENU -->
<div class="acciones" ng-show="seleccionCocina.si">
	<div class="btn-accion">
		<button type="button" class="btn btn-lg btn-success" ng-click="guardarEstadoDetalleOrden()">
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
			<span class="badge" style="font-size:19px">{{seleccionCocina.seleccionados}}</span>
			<b><u>{{seleccionCocina.menu}}</u></b>
		</button>
		<!-- SELECCION POR TIPO DE SERVICIO -->
		<div class="tipo-servicio">
			<span class="label label-warning" ng-repeat="orden in seleccionCocina.lstOrden" ng-if="orden.observacion.length">
				<span class="glyphicon glyphicon-star"></span>
				{{orden.observacion}}
			</span>
		</div>
	</div>
	<img ng-src="{{seleccionCocina.imagen}}">
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

