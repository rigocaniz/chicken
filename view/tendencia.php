
<div class="contenedor">
    <div class="row">
        <div class="col-sm-12">
            <div class="pull-right">
                <a href="#/" >
                    <img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
                </a>
            </div>

            <ul class="nav nav-tabs tabs-title" role="tablist">
                <li role="presentation">
                    <a href="#/">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li role="presentation" ng-class="{'active' : menuTen=='fechaMenu'}" ng-click="menuTen='fechaMenu'">
                    <a href="" role="tab" data-toggle="tab">
                        <span class="glyphicon glyphicon-up"></span> Top Menú
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- TOP MENU MAS VENDIDO -->
                <div class="col-sm-12" ng-show="menuTen=='fechaMenu'">
                    <div class="row">
                        <label class="col-sm-2">De Fecha</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" ng-model="deFecha" data-date-format="dd/MM/yyyy" data-autoclose="1" bs-datepicker placeholder="De Fecha">
                        </div>
                        <label class="col-sm-2">Para Fecha</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" ng-model="paraFecha" data-date-format="dd/MM/yyyy" data-autoclose="1" bs-datepicker placeholder="Para Fecha">
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px">
                        <label class="col-sm-2">Tipo</label>
                        <div class="col-sm-3">
                            <select ng-model="tipoMenu" class="form-control" ng-change="menu.descripcion='';todos='1'">
                                <option value="menu">Menú</option>
                                <option value="combo">Combo</option>
                            </select>
                        </div>
                        <div class="col-sm-2" ng-show="todos==1">
                            <select ng-model="todos" class="form-control">
                                <option value="1">Todos</option>
                                <option value="0">Especificar</option>
                            </select>
                        </div>
                        <div class="col-sm-5" ng-show="todos==0&&menu.descripcion.length==0">
                            <input type="text" autocomplete="off" class="form-control" ng-model="busqueda" placeholder="Especifique">
                            <div class="lstResultado">
                                <ul ng-show="tipoMenu=='menu'">
                                    <li ng-repeat="item in lstMenu | filter:busqueda | limitTo:10" ng-click="sel( item.idMenu, null, item.menu, item.codigoMenu )" ng-mouseenter="item.active=1" ng-mouseleave="item.active=0" ng-class="{'active':item.active}">
                                        {{item.codigoMenu}} - <b>{{item.menu}}</b>
                                    </li>
                                </ul>
                                <ul ng-show="tipoMenu=='combo'">
                                    <li ng-repeat="item in lstCombo | filter:busqueda | limitTo:10" ng-click="sel( null, item.idCombo, item.combo, item.codigoCombo )" ng-mouseenter="item.active=1" ng-mouseleave="item.active=0" ng-class="{'active':item.active}">
                                        {{item.codigoCombo}} - <b>{{item.combo}}</b>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-5" ng-show="menu.descripcion.length">
                            <div class="alert alert-info" 
                                ng-click="todos='1';menu.descripcion='';menu.idMenu=null;menu.idCombo=null;">
                                {{menu.codigo}} - <b>{{menu.descripcion | uppercase}}</b>
                                <button type="button" class="btn btn-xs btn-info" style="float:right" 
                                    ng-click="todos='1';menu.descripcion='';menu.idMenu=null;menu.idCombo=null;">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary" ng-click="topFechaMenu()">
                                <span class="glyphicon glyphicon-ok"></span>
                                <b>Consultar</b>
                            </button>
                        </div>
                    </div>
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
    </div>
</div>
