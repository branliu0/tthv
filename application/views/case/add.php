<?php if ($errors): ?>
<div class="error">
	<ul>
	<?php foreach($errors as $error): ?>
	<li><?php echo $error; ?> </li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<h2 class="title">Add Case</h2>

<div class="post">
	<div class="entry">
	<?php
	echo form::open();

	echo form::label('patient_name', 'Patient Name');
	echo form::input('patient_name', $post['patient_name']);

	echo form::label('village_name', 'Village Name');
	echo form::input('village_name', $post['village_name']);

	echo form::label('phc_name', 'Primary Health Center');
	echo form::input('phc_name', $post['phc_name']);

	echo form::label('mobile', 'Mobile Number (10 digits)');
	echo form::input('mobile', $post['mobile']);

	echo form::submit('submit', 'Add Case');
	echo form::close();
	?>
	</div>
</div>
