<h2><?php echo html::entities($phc_name); ?></h2>
<table class="view-table">
	<thead><tr>
		<th>Patient Name</th>
		<th>Village Name</th>
		<th>Mobile Number</th>
	</tr></thead>
	<tbody>
<?php foreach($cases as $case): ?>
		<tr><td><?php echo html::anchor('case/view/'.$case['id'], $case['patient_name']); ?></td>
		<td><?php echo $case['village_name']; ?></td>
		<td><?php echo $case['mobile']; ?></td></tr>
<?php endforeach; ?>
	</tbody>
</table>
