<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Formstack Mooing</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Formstack to Moo cards">
<meta name="author" content="Soon Van - randomecho.com">
<style>
body, h1, h2, h3, p, ul, ol {
  margin: 0;
  padding: 0;
}

body {
  background: #fff;
  color: #000;
  font-size: 0.9em;
  font-family: sans-serif;
}

a {
  text-decoration: none;
}

h1, h2, h3, p {
  margin: 0 0 1em 0;
}

h1 {
  font-size: 1.5em;
}

h2 {
  font-size: 1.3em;
}

ul {
  margin: 0 0 1em 2em;
}

li {
  margin: 0 0 0.5em 0;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

th {
  text-align: left;
}

th, td {
  padding: 0.5em 1em 0.5em 0.3em;
}

tr:nth-child(2n) {
  background: #f0f0f0;
}

.infobox {
  border: 1px solid #555;
  border-radius: 5px;
  margin: 1em auto;
  padding: 1em;
  width: 500px;
}

.ghost {
  display: none;
}
</style>
</head><body>

<div class="infobox">
<?php

require_once 'config.php';
require_once 'formstack.php';

$formstack = new Formstack();
$forms = $formstack->get_form();

if (isset($forms->forms))
{
	echo '<table>';
	echo '<tr>';
	echo '<th>Name</th>';
	echo '<th>Submissions</th>';
	echo '<th>Last submitted</th>';
	echo '</tr>';

	foreach ($forms->forms as $info)
	{
		echo '<tr>';
		echo '<td><a href="./index.php?id='.$info->id.'">'.$info->name.'</a></td>';
		echo '<td>'.$info->submissions.'</td>';
		echo '<td>'.$info->last_submission_time.'</td>';
		echo '<td><a href="'.$info->url.'">View form</a></td>';
		echo '</tr>';
	}

	echo '</table>';
}

?>
</div>

<?php

if (isset($_GET['id']))
{
	$form_name = $formstack->get_form($_GET['id']);
	$attendees = $formstack->get_names($_GET['id']);

	if (isset($form_name->name))
	{
?>
<div id="namebox" class="infobox">
<h2><?php echo $form_name->name ?> attendees</h2>
<?php
		if ($attendees !== false)
		{
			echo '<ul>';

			foreach ($attendees as $attendee)
			{
				echo '<li>'.$attendee['full'].'</li>';
			}

			echo '</ul>';
		}
		else
		{
			echo '<p>No attendees have yet registered.</p>';
		}
?>
</div>
<?php
	}
}

?>

<div id="imagebox" class="infobox">
<form action="./index.php" method="post" enctype="multipart/form-data">
<input type="file" >
<input type="submit" value="Upload image and send to Moo">
</form>
</div>

</body></html>