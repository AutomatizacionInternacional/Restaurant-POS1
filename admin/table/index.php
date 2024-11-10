<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<style>
	.user-avatar {
		width: 3rem;
		height: 3rem;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<div class="card card-outline rounded-0 card-indigo">
	<div class="card-header">
		<h3 class="card-title">Mesas</h3>
		<div class="card-tools">
			<a href="./?page=table/manage_table" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Crear Nuevo</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="25%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Nombre de la mesa</th>
						<th>cantidad de personas</th>
						<th>Imagen</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM rest_table");
					foreach ($qry as $row) :
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['tablename']?></td>
							<td><?php echo $row['person_capicity'] ?></td>
							<td class="text-center">
								<img src="<?= base_url . $row['table_icon'] ?>" alt="" class="img-thumbnail rounded	 user-avatar">
							</td>
							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Acción
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="./?page=table/manage_table&id=<?= $row['tableid'] ?>"><span class="fa fa-edit text-dark"></span> Editar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['tableid'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("¿Desea eliminar esta mesa de forma permanente?", "delete_table", [$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [4]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})

	function delete_table($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/RestTable.php?f=delete",
			method: "POST",
			data: {
				id: $id
			},
			error: err => {
				console.log(err)
				alert_toast("Ocurrió un error", 'error');
				end_loader();
			},
			success: function(resp) {
				if (resp == 1) {
					location.reload();
				} else {
					alert_toast("Ocurrió un error", 'error');
					end_loader();
				}
			}
		})
	}
</script>