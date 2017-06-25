<?php
namespace Craft;

class BeaconController extends BaseController
{

	protected $allowAnonymous = array('actionCheck');

	public function actionCheck()
	{
		$siteSecret = craft()->request->getRequiredParam('siteSecret');

		if( $siteSecret !== craft()->plugins->getPlugin('beacon')->getSettings()->siteSecret )
		{
			// Token is not valid
			throw new HttpException(400);
		}

		$monitorUrl = rtrim(craft()->plugins->getPlugin('beacon')->getSettings()->monitorSiteUrl,"/");

		HeaderHelper::setHeader([
			'Access-Control-Allow-Origin' => $monitorUrl
			]);

		$this->returnJson( craft()->beacon->check() );
	}

}
