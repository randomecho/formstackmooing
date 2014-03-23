<?php
/**
 * AJAX helper to build up Moo project from Formstack data
 *
 * @package    Formstack Mooing
 * @author     Soon Van - randomecho.com
 * @copyright  2014 Soon Van
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */

require_once 'config.php';
require_once 'formstack.php';
require_once 'moo.php';

$formstack = new Formstack();

if (isset($_GET['get_count']))
{
	$result = $formstack->get_submissions($formstack_form_id);

	if (isset($result->total))
	{
		$_SESSION['submissions'] = $result->submissions;
		echo $result->total;
	}
}
elseif (isset($_GET['get_names']))
{
	$names_list = '';

	// Only shows list of names after seeing count already submitted
	if (isset($_SESSION['submissions']))
	{
		$submissions = $_SESSION['submissions'];

		foreach ($submissions as $info)
		{
			$form_data = $formstack->get_details($info->id);
			$form_fields = $form_data->data;

			foreach ($form_fields as $form_value)
			{
				// Sniff for field that captured the first name data
				if (stripos($form_value->value, 'first') !== false)
				{
					$attendee = explode("\n", $form_value->value);
					$first_name = trim(substr($attendee[0], strpos($attendee[0], '=') + 1));
					$last_name = trim(substr($attendee[1], strpos($attendee[1], '=') + 1));

					$_SESSION['attendees'][] = array('full' => $first_name.' '.$last_name,
						'first_name' => $first_name,
						'last_name' => $last_name,
					);

					$names_list .= '<li>'.$first_name.' '.$last_name.'</li>';

					break;
				}
			}
		}

		if (trim($names_list) != '')
		{
			echo '<h2>Attendees</h2>';
			echo '<ul>'.$names_list.'</ul>';
		}
	}
}
elseif (isset($_GET['make_moo']))
{
	$form_info = $formstack->get_form($formstack_form_id);

	if (isset($form_info->name))
	{
		$moo = new Moo();
		$moo_pack = $moo->create_pack($form_info->name);

		$_SESSION['moo_url'] = $moo->get_url();
	}

	if ($moo_pack)
	{
		$pack_details = $moo->update_pack($_SESSION['attendees']);
	}
}
elseif (isset($_GET['continue_moo']))
{
	if (isset($_SESSION['moo_url']) && trim($_SESSION['moo_url']) != '')
	{
		echo '<a href="'.$_SESSION['moo_url'].'">Continue editing project at Moo.com</a>';
	}
}
