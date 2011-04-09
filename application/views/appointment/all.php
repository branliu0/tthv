<?php echo html::anchor("case/view/{$case['id']}", "Back to Case"); ?>
<h2>All Appointments for <?php echo $case['patient_name']; ?></h2>
<table class="view-table">
	<thead><tr>
		<th>Scheduled Date</th>
		<th>Child Name</th>
		<th>Treatment</th>
		<th>Status</th>
	</tr></thead>
	<tbody>
<?php foreach($appts as $appt): ?>
  <tr>
    <td><?php echo strftime("%Y-%m-%d", $appt['date']); ?></td>
    <td><?php echo $appt['child_name']; ?></td>
    <td><?php echo $appt['treatment']; ?></td>
    <td><?php echo $appt['status']; ?></td>
  </tr>
<?php endforeach; ?>
	</tbody>
</table>
