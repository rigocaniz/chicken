<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<!-- TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : reporteMenu==1}" ng-click="reporteMenu=1;">
					<a href="" role="tab" data-toggle="tab">
						VENTAS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==2}" ng-click="reporteMenu=2;">
					<a href="" role="tab" data-toggle="tab">
						PEDIDO CANCELADO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==3}" ng-click="reporteMenu=3;">
					<a href="" role="tab" data-toggle="tab">
						INGRESOS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : reporteMenu==4}" ng-click="reporteMenu=4;">
					<a href="" role="tab" data-toggle="tab">
						INVENTARIO
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!--  VENTAS -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==1}" ng-show="reporteMenu==1">
					
				</div>
				<!-- PEDIDOS CANCELADO-->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==2}" ng-show="reporteMenu==2">
					
				</div>
				<!-- INGRESOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==3}" ng-show="reporteMenu==3">
				<!-- INVENTARIO DE PRODUCTOS -->
				<div  role="tabpanel" class="tab-pane" ng-class="{'active' : reporteMenu==4}" ng-show="reporteMenu==4">
				</div>
			</div>
		</div>
	</div>
</div>