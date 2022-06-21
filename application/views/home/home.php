<div class="container mt-5 pt-5">
	<div class="row justify-content-center">
		<div class="col-8">
			<div class="card">
				<div class="card-header">
					<h4 class="card-header-title">Face match recognition</h4>
				</div>
				<div class="card-body">
					<?php if($ok == true):?>
					<div class="alert alert-primary">
						<ul class="mb-0">
							<li><b>Job Id</b>: <?= $job_id;?></li>
							<li><b>Analytic Type</b>: <?= $analytic_type;?></li>
							<li><b>Message</b>: <?= $message;?> <?php if($status == 'success'):?>with similarity <?= $result[0]['face_match']['similarity'];?>%<?php endif;?></li>
						</ul>
					</div>
					<div class="text-center">
						<a href="<?= base_url();?>" class="btn btn-info">reset</a>
					</div>
					<?php else:?>
					<?php if(!isset($_SESSION['timestamp']) && !isset($_SESSION['auth_key'])):?>
					<div class="alert alert-info">
						Please set up authorization data first! <a href="<?= site_url('nodeflux');?>" class="btn btn-sm btn-success" style="margin-left: 10px">set up authorization data</a>
					</div>
					<?php else:?>
					<form action="<?= site_url('nodeflux/check_face');?>" method="post" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="inputFoto" class="input-label">Foto utama<small class="text-danger">*</small></label>
							<input type="file" class="form-control" name="image" placeholder="Foto anda" accept="image/jpeg" required>
							<small class="text-danger">Foto yang digunakan sebagai base face recognition</small>
						</div>
						<div class="mb-3">
							<label for="inputFoto" class="input-label">Take picture <small
									class="text-danger">*</small></label>
							<div id="my_camera" style="width:620px; height:440px; margin-left: 12%; margin-top: 15px">
							</div>
							<input type="hidden" name="file" id="result" value="">
						</div>
						<div class="text-center">
							<button type="button" id="take_picture" class="btn btn-primary"
								onclick="take_snapshot()">take picture</button>
							<button type="button" id="retake_picture" class="btn btn-primary d-none"
								onclick="take_snapshot()">retake picture</button>
							<button type="button" id="reset" class="btn btn-secondary d-none"
								onclick="reset_pic()">reset</button>
							<button type="submit" id="submit_picture" class="btn btn-success d-none">check</button>
						</div>
					</form>
					<?php endif;?>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<div id="my_result"></div>
</div>

<?php if(isset($_SESSION['timestamp']) && isset($_SESSION['auth_key']) && $ok == false):?>
<script language="JavaScript">
	Webcam.attach('#my_camera');

	function take_snapshot() {
		Webcam.snap(function (data_uri) {
			$('#result').val(data_uri);
			document.getElementById('my_result').innerHTML = '<img src="'+data_uri+'"/>';
		});
		$('#take_picture').addClass('d-none');
		$('#retake_picture').removeClass('d-none');
		$('#submit_picture').removeClass('d-none');
		$('#reset').removeClass('d-none');
	}

	function reset_pic(){
		$('#result').val();
		$('#take_picture').removeClass('d-none');
		$('#retake_picture').addClass('d-none');
		$('#submit_picture').addClass('d-none');
		$('#reset').addClass('d-none');
		document.getElementById('my_result').innerHTML = '';
	}

</script>
<?php endif;?>
