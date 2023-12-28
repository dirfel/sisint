<?php if ($token <> "") { ?>
	<div class="alert alert-danger fade in">
		<a href="#" class="close" data-dismiss="alert">&times;</a>-
		<strong><?= $token ?></strong>
	</div>
<?php $token = "";
} else if ($token2 <> "") { ?>
	<div class="alert alert-success fade in">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong><?= $token2 ?></strong>
	</div>
<?php $token2 = "";
} else if ($token3 <> "") { ?>
	<div class="alert alert-info fade in">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong><?= $token3 ?></strong>
	</div>
<?php $token3 = ""; }
