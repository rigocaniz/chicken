<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-12">
			<div class="btn-orden">
				<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="idEstadoOrden=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs">Pendientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="idEstadoOrden=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs">En Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="idEstadoOrden=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs">Finalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="idEstadoOrden=10">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs">Cancelados</span>
				</button>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:7px">
		<div class="col-sm-12">
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
	</div>
	<div class="acciones" ng-show="seleccion.si">
		<button type="button" class="btn btn-success">
			<span class="glyphicon glyphicon-play"></span>
			<b>Preparar</b>
			<span class="badge">{{seleccion.count}}</span>
		</button>
	</div>
</div>

<!-- NUEVA ORDEN -->
<script type="text/ng-template" id="dial.orden.nueva.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_nueva">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-plus"></span>
                    Nueva Orden
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-5">
                			<h4># Ticket</h4>
                		</div>
                		<div class="col-xs-7">
							<div class="input-group">
								<input type="text" class="form-control input-lg input-focus" ng-model="$parent.noTicket" id="noTicket" ng-keypress="$event.keyCode==13 && agregarOrden()">
								<span class="input-group-btn">
									<button class="btn btn-lg btn-danger" type="button" ng-click="$parent.noTicket=''">
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</span>
							</div>
                		</div>
                		<div class="col-xs-12 text-center" style="margin-top:4px">
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'0'">0</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'1'">1</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'2'">2</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'3'">3</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'4'">4</button>
                		</div>
                		<div class="col-xs-12 text-center" style="margin-top:4px">
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'5'">5</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'6'">6</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'7'">7</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'8'">8</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'9'">9</button>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="agregarOrden()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>Agregar Ticket</b>
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
