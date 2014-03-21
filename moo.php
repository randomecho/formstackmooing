<?php
/**
 * Method calls to the Moo API
 *
 * @package    Formstack Mooing
 * @author     Soon Van - randomecho.com
 * @copyright  2014 Soon Van
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */

class Moo {

	private $api = 'http://www.moo.com/api/service/';
	private $card_type = 'minicard';
	private $pack_name = 'Formstack Mooing - ';
	private $pack_id = '';
	private $dropin_url = '';

	/**
	 * Connect with Moo API via OAuth
	 *
	 * @param   array    method and any related details to customise project
	 * @return  mixed
	 */
	public function connect($params)
	{
		global $moo_key, $moo_secret;

		try
		{
			$oauthClient = new OAuth($moo_key, $moo_secret);
			$oauthClient->fetch($this->api, $params, OAUTH_HTTP_METHOD_POST);

			$response = $oauthClient->getLastResponse();

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

			$response = $oauthClient->getLastResponse();

			if ( ! is_null($response))
			{
				$message = json_decode($response);

				if (isset($message->exception->message))
				{
					echo "<br><br>\n\n".$message->exception->message;
				}
			}

			return false;
		}
	}

	/**
	 * Create a new Moo card project
	 * Sets the pack_id and dropin_url variables on success
	 *
	 * @param   string   Formstack form name
	 * @return  boolean  success or fail on creating a new project
	 */
	public function create_pack($form_name)
	{
		$api_params = array(
			'method' => 'moo.pack.createPack',
			'product' => $this->card_type,
			'friendlyName' => $this->pack_name.$form_name.' '.date("Y-m-d"),
		);

		$response = $this->connect($api_params);

		if (isset($response->packId))
		{
			$this->pack_id = $response->packId;
			$this->dropin_url = $response->dropIns->imageChooser;

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Update current card project with attendee name details
	 *
	 * @param   array    details of attendees
	 * @return  mixed    response from the API
	 */
	public function update_pack($attendees)
	{
		foreach ($attendees as $attendee_name)
		{
			$card_info[] = array(
				'type' => 'multiLineTextData',
				'text' => $attendee_name['first_name']."\n".$attendee_name['last_name'],
				'alignment' => 'left'
			);
		}

		$side_data['sides'] = array(array(
			'type' => 'details',
			'templateCode' => 'minicard_full_image_landscape',
			'data' => array()
		));

		$api_params = array(
			'method' => 'moo.pack.updatePack',
			'packId' => $this->pack_id,
			'pack' => json_encode($side_data)
		);

		return $this->connect($api_params);
	}

	/**
	 * Get the Moo dropin URL where user can continue editing project at moo.com
	 *
	 * @return  string   URL to continue Moo project
	 */
	public function get_url()
	{
		return $this->dropin_url;
	}

}