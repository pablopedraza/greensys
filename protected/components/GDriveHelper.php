<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';

class GDriveHelper
{
	/***
	 * @param string $redirectUri
	 * @param array $getParams 
	 */
	static public function getAuthUrl($redirectUri, $getParams)
	{
		$client = new Google_Client();
		
		// Get your credentials from the APIs Console
		$client->setClientId('740580923589-mnpka332ukf9ilvtgqn0tvgfcci4v0n3.apps.googleusercontent.com');
		$client->setClientSecret('zhD-rtPm_EnLtT_fu50BIfjZ');
		
		$client->setRedirectUri($redirectUri);
		
		$stringParam = '';		
		foreach($getParams as $key => $value)
		{
			if($stringParam == '')
				$stringParam = $stringParam . $key. '='. $value;
			else 
				$stringParam = $stringParam . '&'.$key. '='. $value;
		}
		$client->setState($stringParam);
		
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));
		$service = new Google_DriveService($client);
			
		$authUrl = $client->createAuthUrl();
		return $authUrl; 
	}

	static public function uploadFile($code, $state)
	{
		parse_str($state, $params);
		
		$client = new Google_Client();
			
		// Get your credentials from the APIs Console
		$client->setClientId('740580923589-mnpka332ukf9ilvtgqn0tvgfcci4v0n3.apps.googleusercontent.com');
		$client->setClientSecret('zhD-rtPm_EnLtT_fu50BIfjZ');		
		$redirectUri = 'http://localhost/workspace/Green/index.php?r=review/index';
		$client->setRedirectUri($redirectUri);
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		$service = new Google_DriveService($client);
		
		$accessToken = $client->authenticate($code);
		$client->setAccessToken($accessToken);
		
		$multimedia = TMultimedia::model()->findByPk($params['Id_multimedia']);
		
		if(isset($multimedia))
		{
			//$mimeType = mime_content_type(Yii::app()->baseUrl.'/docs/'.$multimedia->file_name);
			$mimeType = $multimedia->mimeType;
			
			//prepare file info
			$file = new Google_DriveFile();
			$file->setTitle($multimedia->description);			
			$file->setMimeType($mimeType);

			//get file data			
			//$data = file_get_contents(Yii::app()->baseUrl.'/docs/'.$multimedia->file_name);
			$data = file_get_contents('http://localhost/workspace/Green/docs/'.$multimedia->file_name);
			
// 			$parentId = '0B3IgC6E17ly-cnRSdEFFMFpIMzA';
				
// 			// Set the parent folder.
// 			if ($parentId != null) 
// 			{
// 				$parent = new Google_ParentReference();
// 				$parent->setId($parentId);
// 				$file->setParents(array($parent));
// 			}
			
			$Id_google_drive = null;
			if(isset($multimedia->Id_google_drive))
				$Id_google_drive = self::updateFile($service, $multimedia->Id_google_drive, $file, $data, $mimeType);
			else
				$Id_google_drive = self::insertFile($service, $file, $data, $mimeType, $params['Id_multimedia']);
			
			if($Id_google_drive != null)
				self::shareFile($service, $Id_google_drive);
				
				
			return true;
		}
		
		return false;
	}
	
	private function shareFile($service, $Id_google_drive)
	{
		$newPermission = new Google_Permission();
		$newPermission->setValue('wensel84@gmail.com');
		$newPermission->setType('user');
		$newPermission->setRole('reader');
		$service->permissions->insert($Id_google_drive, $newPermission);
	}
	
	private function insertFile($service, $file, $data, $mimeType, $Id_multimedia)
	{
		$createdFile = $service->files->insert($file, array(
		      'data' => $data,
		      'mimeType' => $mimeType,
		));
		
		$multimedia = TMultimedia::model()->findByPk($Id_multimedia);
		$multimedia->Id_google_drive = (string)$createdFile['id'];
		$multimedia->save();
		
		return $multimedia->Id_google_drive;
	}
	
	private function updateFile($service, $Id_google_drive, $file, $data, $mimeType)
	{
		$updatedFile = $service->files->update($Id_google_drive, $file, array(
					      'data' => $data,
					      'mimeType' => $mimeType,
		));
		
		return $Id_google_drive;
	}

}