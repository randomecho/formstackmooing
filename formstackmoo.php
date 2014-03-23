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

if (isset($_GET['make_moo']))
{
	$form_info = $formstack->get_form($_GET['make_moo']);

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
