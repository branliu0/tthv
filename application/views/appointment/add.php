<h2 class="title">Add Appointment for <?php echo $case['patient_name']; ?></h2>

<?php if ($errors): ?>
<div class="error">
	<ul>
	<?php foreach($errors as $error): ?>
	<li><?php echo $error; ?> </li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<div class="post">
	<div class="entry">
	<?php
	echo form::open();

	echo form::label('child_name', 'Child Name');
	echo form::input('child_name', $post['child_name']);

	echo form::label('message', 'Custom Message');
	echo form::input('message', $post['message']);

	echo form::label('date', 'Appointment Date');
	echo form::input('date', $post['date'], array("id" => "date"));

	echo form::submit('submit', 'Add Appointment');
	echo form::close();
	?>
	</div>
</div>

  <script>
  $("#date").datepicker();
  </script>
