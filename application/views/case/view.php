<h2>Patient Details</h2>
<div class="post">
	<div class="entry">
		<label>Patient Name: <b><?php echo $case['patient_name']; ?></b></label>
		<label>Village Name: <b><?php echo $case['village_name']; ?></b></label>
		<label>Primary Health Center: <b><?php echo $case['phc_name']; ?></b></label>
		<label>Mobile: <b><?php echo $case['mobile']; ?></b></label>
	</div>

	<div class="entry">
		<h3 class="title">Add Child</h3>
		<?php if ($errors): ?>
		<div class="error">
			<ul>
			<?php foreach($errors as $error): ?>
			<li><?php echo $error; ?> </li>
			<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>

		<?php
		echo form::open();

		echo form::label('child_name', 'Child Name');
		echo form::input('child_name', $post['child_name'] OR "");

		echo form::label('birth_date', 'Birth Date (Actual or Expected)');
		echo form::input('birth_date', NULL, array('id' => 'birth_date'));

		echo form::submit('submit', 'Add Child');
		echo form::close();
		?>
	</div>
</div>

<script>
	$(function() {
		$("#birth_date").datepicker();
	});
</script>
