<h2>Appointments Today</h2>
<?php if($cases->count() == 0): ?>
  There are no appointments for today.
<?php else: ?>
<table class="view-table">
	<thead><tr>
		<th>Patient Name</th>
		<th>Primary Health Center</th>
		<th>Village Name</th>
		<th>Treatment</th>
	</tr></thead>
	<tbody>
<?php foreach($cases as $case): ?>
    <tr>
      <td><?php echo html::anchor('case/view/' . $case['id'], $case['patient_name']); ?></td>
      <td><?php echo $case['phc_name']; ?></td>
      <td><?php echo $case['village_name']; ?></td>
      <td><?php echo $case['treatment']; ?></td>
    </tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>

<h2>Appointments Overdue Last Week</h2>
<table cellspacing="0" class="view-table">
  <thead>
    <tr>
      <th>Patient Name</th>
      <th>Primary Health Center</th>
      <th>Village Name</th>
      <th>Treatment</th>
      <th NOWRAP>Date</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($overdue as $case): ?>
    <tr>
      <td><?php echo html::anchor('case/view/' . $case['id'], $case['patient_name']); ?></td>
      <td><?php echo $case['phc_name']; ?></td>
      <td><?php echo $case['village_name']; ?></td>
      <td><?php echo $case['treatment']; ?></td>
      <td NOWRAP><?php echo strftime("%Y-%m-%d", $case['date']); ?></td>
    </tr>
  </tbody>
<?php endforeach; ?>
</table>

<h2>Appointments Upcoming This Week</h2>
<table cellspacing="0" class="view-table">
  <thead>
    <tr>
      <th>Patient Name</th>
      <th>Primary Health Center</th>
      <th>Village Name</th>
      <th>Treatment</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($thisWeek as $case): ?>
    <tr>
      <td><?php echo html::anchor('case/view/' . $case['id'], $case['patient_name']); ?></td>
      <td><?php echo $case['phc_name']; ?></td>
      <td><?php echo $case['village_name']; ?></td>
      <td><?php echo $case['treatment']; ?></td>
      <td NOWRAP><?php echo strftime("%Y-%m-%d", $case['date']); ?></td>
    </tr>
  </tbody>
<?php endforeach; ?>
</table>
