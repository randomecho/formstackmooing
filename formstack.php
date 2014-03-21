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
	 * Get details of a form created at Formstack
	 *
	 * @param   integer  form id
	 * @return  mixed
	 */
	public function get_form($formstack_form_id)
	{
		$method = 'form/'.$formstack_form_id.'.json';

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

}