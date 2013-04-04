<?php
class GDriveHelper
{
	/**
	 * 
	 * Insert or update a file in GoogleDrive
	 * @param TMultimedia $modelMultimedia
	 * @return Id_google_drive
	 */
	static public function uploadFile($modelMultimedia)
	{
		$service = self::getService();
		$idGoogleDrive = $modelMultimedia->Id_google_drive;
		
		if(isset($modelMultimedia))
		{
			$mimeType = $modelMultimedia->mimeType;
	
			//prepare file info
			$file = new Google_DriveFile();
			$file->setTitle($modelMultimedia->description);
			$file->setMimeType($mimeType);

			//get file data
			$data = file_get_contents('docs/'.$modelMultimedia->file_name);
	
// 			$parentId = '0B3IgC6E17ly-cnRSdEFFMFpIMzA';

// 			// Set the parent folder.
// 			if ($parentId != null)
// 			{
// 				$parent = new Google_ParentReference();
// 				$parent->setId($parentId);
// 				$file->setParents(array($parent));
// 			}
	
			if(isset($idGoogleDrive))
				self::updateFile($service, $idGoogleDrive, $file, $data, $mimeType);
			else
				$idGoogleDrive = self::insertFile($service, $file, $data, $mimeType);
				
		}
		
		return $idGoogleDrive;
	}
	
	/**
	 * 
	 * Share files.
	 * @param String $Id_google_drive
	 * @param integer $Id_customer
	 * @param String $role The value "owner", "writer" or "reader".
	 */
	static public function shareFile($Id_google_drive, $Id_customer, $role = 'writer')
	{
		$service = self::getService();
		
		$criteria=new CDbCriteria;
		$criteria->join =  	"INNER JOIN user u on u.username = t.username
							INNER JOIN user_group ug on u.Id_user_group = ug.Id";
		$criteria->addCondition('ug.use_technical_docs = 1');		
		$criteria->addCondition('t.Id_customer = '. $Id_customer);		
		$criteria->addCondition('u.username <> "'. User::getCurrentUser()->username .'"');
		
		$userCustomers = UserCustomer::model()->findAll($criteria);
		
		foreach($userCustomers as $modelUserCustomer)
		{
			self::share($service, $modelUserCustomer->user->email, $role, $Id_google_drive);			
		}
	}
	
	/**
	*
	* Share files by id note (all user group related).
	* @param integer $Id_note
	* @param String $role The value "owner", "writer" or "reader".
	*/
	static public function shareFilesByNote($Id_note, $role = 'reader')
	{
		$service = self::getService();
	
		$criteria=new CDbCriteria;
		$criteria->select = 't.Id_user_group, t.Id_project, m.Id_google_drive as Id_google_drive';
		$criteria->join =  	"INNER JOIN multimedia_note mn on t.Id_note = mn.Id_note
							INNER JOIN multimedia m on m.Id = mn.Id_multimedia
							INNER JOIN user_group ug on ug.Id = t.Id_user_group";
		$criteria->addCondition('m.Id_document_type is not null');
		$criteria->addCondition('t.Id_note = '. $Id_note);
		$criteria->addCondition('ug.use_technical_docs = 0');
	
		
		$userGroupNotes = UserGroupNote::model()->findAll($criteria);
	
		foreach($userGroupNotes as $modelUserGroup)
		{
			$criteria=new CDbCriteria;
			$criteria->join =  	"INNER JOIN user_customer uc on uc.username = t.username";			
			$criteria->addCondition('t.Id_user_group = ' . $modelUserGroup->Id_user_group);
			$criteria->addCondition('uc.Id_project = ' . $modelUserGroup->Id_project);
			
			$users = User::model()->findAll($criteria);
			
			foreach($users as $user)
			{
				self::share($service, $user->email, $role, $modelUserGroup->Id_google_drive);				
			}			
		}
	}
	
	/**
	*
	* Share files by a particular id user group.
	* @param integer $Id_note
	* @param integer $Id_user_group
	* @param String $role The value "owner", "writer" or "reader".
	*/
	static public function shareFilesByUserGroup($Id_note, $Id_user_group, $role = 'reader')
	{
		$service = self::getService();
	
		$criteria=new CDbCriteria;
		$criteria->select = 't.Id_project, m.Id_google_drive as Id_google_drive';
		$criteria->join =  	"INNER JOIN multimedia_note mn on t.Id_note = mn.Id_note
								INNER JOIN multimedia m on m.Id = mn.Id_multimedia
								INNER JOIN user_group ug on ug.Id = t.Id_user_group";
		$criteria->addCondition('m.Id_document_type is not null');
		$criteria->addCondition('t.Id_note = '. $Id_note);
		$criteria->addCondition('t.Id_user_group = '. $Id_user_group);
		$criteria->addCondition('ug.use_technical_docs = 0');
	
	
		$userGroupNotes = UserGroupNote::model()->findAll($criteria);
	
		foreach($userGroupNotes as $modelUserGroup)
		{
			$criteria=new CDbCriteria;
			$criteria->join =  	"INNER JOIN user_customer uc on uc.username = t.username";
			$criteria->addCondition('t.Id_user_group = ' . $Id_user_group);
			$criteria->addCondition('uc.Id_project = ' . $modelUserGroup->Id_project);
				
			$users = User::model()->findAll($criteria);
				
			foreach($users as $user)
			{
				self::share($service, $user->email, $role, $modelUserGroup->Id_google_drive);
			}
		}
	}
	
	static public function removeFilePermission($fileId, $permissionId)
	{
		//$service->permissions->delete($fileId, $permissionId);
	}
	
	private function share($service, $email, $role, $Id_google_drive)
	{
		$newPermission = new Google_Permission();
		$newPermission->setValue($email);
		$newPermission->setType('user');
		$newPermission->setRole($role);
		$service->permissions->insert($Id_google_drive, $newPermission);
	}
	
	private function insertFile($service, $file, $data, $mimeType)
	{
		$createdFile = $service->files->insert($file, array(
		      'data' => $data,
		      'mimeType' => $mimeType,
		));
		
		return (string)$createdFile['id'];
	}
	
	private function updateFile($service, $Id_google_drive, $file, $data, $mimeType)
	{
		$updatedFile = $service->files->update($Id_google_drive, $file, array(
					      'data' => $data,
					      'mimeType' => $mimeType,
		));
				
	}
	
	private function getService()
	{
		$clientData = $_SESSION['GOOGLE_DRIVE_CLIENT_DATA'];
		
		$client = new Google_Client();
		
		$client->setClientId($clientData->getClientId());
		$client->setClientSecret($clientData->getClientSecret());
		$client->setScopes($clientData->getScope());
		$client->setRedirectUri($clientData->getRedirectUri());
		
		$service = new Google_DriveService($client);
		
		$client->setAccessToken($_SESSION['GOOGLE_DRIVE_TOKEN']);
		
		return $service;
	}
	

}