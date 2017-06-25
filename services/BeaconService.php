<?php
namespace Craft;

class BeaconService extends BaseApplicationComponent
{

	public function check()
	{
		return craft()->updates->getUpdates();   
	}

	public function pingMonitor($settings)
	{
		$url = $settings['monitorSiteUrl'] . 'beaconmonitor/ping';
		$fields = array(
			  'monitorSecret' => urlencode($settings['monitorSecret']),
					'siteName'    => urlencode($settings['siteName']),
					'siteSecret'  => urlencode($settings['siteSecret']),
					'autoupdate'  => urlencode($settings['autoupdate']),
					'siteUrl'   => urlencode(craft()->getSiteUrl())
				);
		$fields_string = '';

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		$fields_string = rtrim($fields_string,'&');

		$url .= '?' . $fields_string;

		//open connection
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$data = curl_exec($ch);  
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if( $httpcode === 200 )
		{
			BeaconPlugin::log(Craft::t('Pinging monitor success'));
		} else {
			BeaconPlugin::log(Craft::t('Pinging monitor failed: Error code: {code}', array('code' => $httpcode)), LogLevel::Error, true);
			craft()->userSession->setError(Craft::t('Updated details could not be sent to monitor!'));
		}

		
		curl_close($ch);
	}
}
