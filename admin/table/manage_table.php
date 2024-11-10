<?php
if (isset($_GET['id'])) {
	$table = $conn->query("SELECT * FROM rest_table WHERE tableid ='{$_GET['id']}' ");
	$meta = [];
	foreach ($table->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-indigo">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-table">
				<input type="hidden" name="tableid" value="<?= isset($meta['tableid']) ? $meta['tableid'] : '' ?>">
				<div class="form-group">
					<label for="tablename">Nombre de la mesa</label>
					<input type="text" name="tablename" id="tablename" class="form-control" value="<?php echo isset($meta['tablename']) ? $meta['tablename'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="person_capicity">Cantidad de personas</label>
					<input type="number" name="person_capicity" id="person_capicity" class="form-control" value="<?php echo isset($meta['person_capicity']) ? $meta['person_capicity'] : '' ?>">
				</div>
				<div class="form-group">
					<label for="" class="control-label">Imagen</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input rounded-circle" id="customFile" name="table_icon" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
						<label class="custom-file-label" for="customFile">Examinar</label>
					</div>
				</div>
				<div class="form-group d-flex justify-content-center">
					<img src="<?php echo isset($meta['table_icon']) ? $meta['table_icon'] : '' ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary rounded-0 mr-3" form="manage-table">Guardar Información de Usuario</button>
				<a href="./?page=table" class="btn btn-sm btn-default border rounded-0" form="manage-user"><i class="fa fa-angle-left"></i> Cancelar</a>
			</div>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>");
		}
	}
	$('#manage-table').submit(function(e) {
		e.preventDefault();
		start_loader()
		$.ajax({
			url: _base_url_ + 'classes/RestTable.php?f=save_table',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					location.href = './?page=table'
				} else {
					$('#msg').html('<div class="alert alert-danger">Ocurrio un error</div>')
					end_loader()
				}
			}
		})
	})
</script>