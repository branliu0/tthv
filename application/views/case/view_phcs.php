<h2>Primary Health Centers</h2>
<table class="view-table">
	<thead><tr>
		<th>Primary Health Center</th>
		<th>Number of cases</th>
	</tr></thead>
	<tbody>
<?php foreach($phcs as $phc): ?>
		<tr><td><?php echo html::anchor('case/phc/'.$phc['phc_name'], $phc['phc_name']); ?></td>
		<td><?php echo $phc['total']; ?></td></tr>
<?php endforeach; ?>
	</tbody>
</table>

