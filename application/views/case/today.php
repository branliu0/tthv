<h2>Appointments Today</h2>
<table class="view-table">
	<thead><tr>
		<th>Patient Name</th>
		<th>PHC</th>
		<th>Village</th>
	</tr></thead>
	<tbody>
<?php foreach($cases as $case): ?>
    <tr>
      <td><?php echo html::anchor('case/view/' . $case['id'], $case['patient_name']); ?></td>
      <td><?php echo $case['phc_name']; ?></td>
      <td><?php echo $case['village_name']; ?></td>
    </tr>
<?php endforeach; ?>
	</tbody>
</table>

