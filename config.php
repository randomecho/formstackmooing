<?php
/**
 * Configuration settings
 *
 * @package    Formstack Mooing
 * @author     Soon Van - randomecho.com
 * @copyright  2014 Soon Van
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */

/**
 * Formstack form ID can be found in the URL when editing its settings
 *
 * E.g. https://www.formstack.com/admin/form/overview/XXX
 */
$formstack_form_id = '';

// Formstack API credentials - https://www.formstack.com/developers/api
$formstack_token = '';

// Moo API credentials - https://secure.moo.com/account/api/application/
$moo_key = '';
$moo_secret = '';


date_default_timezone_set('America/New_York');
setlocale(LC_ALL, 'en_US.utf-8');
session_start();