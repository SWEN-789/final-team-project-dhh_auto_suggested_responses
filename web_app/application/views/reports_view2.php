<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
<!-- Export Data -->
<a href='<?= base_url() ?>index.php/Reports/exportCSV'>Export</a><br><br>

<!-- User Records -->
<table border='1' style='border-collapse: collapse;'>
	<thead>
	<tr>
		<th>ID</th>
		<th>Chat ID</th>
		<th>User ID</th>
		<th>Context</th>
		<th>API</th>
		<th>Message_Content</th>
		<th>Created</th>
        <th>Timestamp</th>
        <th>Elapsed Time</th>
	</tr>
	</thead>
	<tbody>
	<?php
    $last_time = 0;
	foreach($messages as $message){
		echo "<tr>";
		echo "<td>".$message->id."</td>";
		echo "<td>".$message->chat_id."</td>";
		echo "<td>".$message->user_id."</td>";
		echo "<td>".$message->context."</td>";
		if ($message->mode === '1') echo "<td>Yes</td>";
		else echo "<td>No</td>";
		echo "<td>".$message->message_content."</td>";
		echo "<td>".$message->create_date."</td>";
		$timestamp = strtotime($message->create_date);
		echo "<td>".$timestamp."</td>";
		$elapsedTime = date("H:i:s", ($timestamp-$last_time));
		echo "<td>".$elapsedTime."</td>";
		echo "</tr>";
		$last_time = strtotime($message->create_date);

	}
	?>
	</tbody>
</table>
</div>