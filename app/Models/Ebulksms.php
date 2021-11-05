<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebulksms extends Model
{
	use HasFactory;

	//EbulkSms
	public $url = "http://api.ebulksms.com:8080/sendsms.json";
	public $apikey = "c06045fd8320808c6d23dedd00f79e05329eaf9c";
	public $username = "hasanahbobi20@gmail.com";
	public $flash = 0;

	//Ebulksms service
	function useJSON($sendername, $messagetext, $recipients)
	{
		$gsm = array();
		$country_code = '234';
		$arr_recipient = explode(',', $recipients);

		foreach ($arr_recipient as $recipient) {
			$mobilenumber = trim($recipient);
			if (substr($mobilenumber, 0, 1) == '0') {
				$mobilenumber = $country_code . substr($mobilenumber, 1);
			} elseif (substr($mobilenumber, 0, 1) == '+') {
				$mobilenumber = substr($mobilenumber, 1);
			}
			$generated_id = uniqid('int_', false);
			$generated_id = substr($generated_id, 0, 30);
			$gsm['gsm'][] = array('msidn' => $mobilenumber, 'msgid' => $generated_id);
		}

		$message = array(
			'sender' => $sendername,
			'messagetext' => $messagetext,
			'flash' => "{$this->flash}",
		);
		$request = array('SMS' => array(
			'auth' => array(
				'username' => $this->username,
				'apikey' => 'c06045fd8320808c6d23dedd00f79e05329eaf9c'
			),
			'message' => $message,
			'recipients' => $gsm
		));

		$json_data = json_encode($request);


		if ($json_data) {
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/json",
					'method'  => 'POST',
					'content' => $json_data
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($this->url, false, $context);
			return $result;
		} else {
			return false;
		}
	}
}
