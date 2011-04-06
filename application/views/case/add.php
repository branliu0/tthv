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
	echo form::input('village_name', $post['village_name'], array("id" => "village_name"));

	echo form::label('phc_name', 'Primary Health Center');
	echo form::input('phc_name', $post['phc_name'], array("id" => "phc_name"));

	echo form::label('mobile', 'Mobile Number (10 digits)');
	echo form::input('mobile', $post['mobile']);

  echo form::label('clinic_access', 'Can access the clinic?');
  echo form::checkbox('clinic_access', 'yes', true);

	echo form::submit('submit', 'Add Case');
	echo form::close();
	?>
	</div>
</div>

<script>
  $(function() {
    $("#village_name").autocomplete({
      source: function(req, add) {
        $.getJSON("<?= url::site('ajax/get_villages/') ?>", req, function(data) {
          add(data);
        });
      }
    });
    $("#phc_name").autocomplete({
      source: function(req, add) {
        $.getJSON("<?= url::site('ajax/get_phcs/') ?>", req, function(data) {
          add(data);
        });
      }
    });
  });
</script>
