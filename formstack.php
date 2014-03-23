<?php
/**
 * Method calls to the Formstack API
 *
 * @package    Formstack Mooing
 * @author     Soon Van - randomecho.com
 * @copyright  2014 Soon Van
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */

class Formstack {

	private $api = 'https://www.formstack.com/api/v2/';

	/**
	 * Connect to the Formstack API
	 *
	 * @param   string   method and/or endpoint
	 * @return  mixed    response from API with details, or false
	 */
	public function connect($method)
	{
		global $formstack_token;
		$endpoint = $this->api.$method.'?oauth_token='.$formstack_token;

		try
		{
			$remote_hook = curl_init();
			curl_setopt($remote_hook, CURLOPT_URL, $endpoint);
			curl_setopt($remote_hook, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($remote_hook, CURLOPT_FAILONERROR, 0);
			curl_setopt($remote_hook, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($remote_hook, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($remote_hook, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($remote_hook, CURLOPT_TIMEOUT, 50000);
			$response = curl_exec($remote_hook);
			curl_close($remote_hook);

			if ( ! is_null($response))
			{
				$response = json_decode($response);
			}
			else
			{
				$response = false;
			}

			return $response;
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Get forms under account, or details of a specific form
	 *
	 * @param   integer  form id
	 * @return  mixed
	 */
	public function get_form($formstack_form_id = '')
	{
		$method = 'form/';

		if ($formstack_form_id != '')
		{
			$method .= $formstack_form_id.'.json';
		}

		return $this->connect($method);
	}

	/**
	 * Get details of a form created at Formstack
	 *
	 * @param   integer  form id
	 * @return  mixed
	 */
	public function get_submissions($formstack_form_id)
	{
		$method = 'form/'.$formstack_form_id.'/submission.json';

		return $this->connect($method);
	}

	/**
	 * Get details of a form submission
	 *
	 * @param   integer  submission id
	 * @return  mixed
	 */
	public function get_details($submission_id)
	{
		$method = 'submission/'.$submission_id.'.json';

		return $this->connect($method);
	}

	/**
	 * Get names from a form submission
	 *
	 * @param   integer  form id
	 * @return  mixed
	 */
	public function get_names($form_id)
	{
		$result = $this->get_submissions($form_id);

		if (isset($_SESSION['attendees']))
		{
			unset($_SESSION['attendees']);
		}

		if (isset($result->total))
		{
			$_SESSION['submissions'] = $result->submissions;

			foreach ($_SESSION['submissions'] as $info)
			{
				$form_data = $this->get_details($info->id);
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

						// If there are other fields we do not need to stick around looking at them
						break;
					}
				}
			}

			if (count($_SESSION['attendees']) > 0)
			{
				return $_SESSION['attendees'];
			}
		}

		return false;
	}
}