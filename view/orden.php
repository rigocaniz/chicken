<div class="container">
	<div class="row">
		<br>
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="col-sm-12">
					<label class="col-sm-2">Tipo Orden</label>
					<div class="col-sm-2">
						<select class="form-control">
							<option value="1">Restaurante</option>
							<option value="2">LLevar</option>
						</select>
					</div>
					<label class="col-sm-1">Producto</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" placeholder="codigo / Producto">
					</div>
					<button type="button" class="btn btn-primary">Buscar</button>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Menus mas pedidos</h3>
					</div>
					<div class="panel-body">
						
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Orden</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="form-group">
								<label class="col-sm-2"># ticket</label>
								<div class="col-sm-3">
									<input type="number" class="form-control">
								</div>
							</div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Producto</th>
										<th >Cantidad</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>001</td>
										<td>Menu1</td>
										<td>
											<input type="number" class="form-control" style="width: 7em;">
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-xs">X</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>