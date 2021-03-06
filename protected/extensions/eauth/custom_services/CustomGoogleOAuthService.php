<?php

//Para usar esta clase hace falta previamente hacer in import del google-api-php-client
class CustomGoogleOAuthService extends GoogleOAuthService
{
	protected $scope = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/userinfo.email';
	
	protected function fetchAttributes() {
		$this->attributes = (array) $this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');		
		
	}
	
	protected function getAccessToken($code) {
						
		$client = new Google_Client();
		$client->setClientId($this->client_id);
		$client->setClientSecret($this->client_secret);
		$client->setScopes($this->scope);					
		$client->setRedirectUri($this->getState('redirect_uri'));
				
		$service = new Google_DriveService($client);
				
		$accessToken = $client->authenticate($code);
		$client->setAccessToken($accessToken);		
		
		$_SESSION['GOOGLE_DRIVE_CLIENT_DATA'] = $this;
		$_SESSION['GOOGLE_DRIVE_TOKEN'] = $accessToken;
		return $this->parseJson($accessToken);
	}
	
	/**
	 * Save access token to the session.
	 * @param stdClass $token access token array.
	 */
	protected function saveAccessToken($token) {	
		$this->setState('auth_token', $token->access_token);
		$this->setState('expires', time() + $token->expires_in - 60);
		$this->access_token = $token->access_token;
	}
	
	public function getClientId()
	{
		return $this->client_id;	
	}
	
	public function getScope()
	{
		return $this->scope;
	}
	
	public function getClientSecret()
	{
		return $this->client_secret;
	}

	public function getAccessType()
	{
		return $this->access_type;
	}
	
	public function getRedirectUri()
	{
		return $this->getState('redirect_uri');
	}
}
