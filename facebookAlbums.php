<?php
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.9
 * @package       : apptha
 * @subpackage    : mac-doc-photogallery
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract      : The core file of calling Mac Photo Gallery.
 * @Creation Date : June 20 2011
 * Edited by 	  : kranthi kumar
 * Email          : kranthikumar@contus.in
 * @Modified Date : Jan 05 2012
 * */
require 'sdk/facebook.php';//for facebook sdk 
class facebookAlbumsDetails{
	
				private $user;
				private $logInLogOutLink;
				private $facebook;
	function __construct(){
		
		
				   	   
							$app_secret = 'fdb2654f9a64ed3ca4c8a69477314a0b';//1ceb54c2fa0d15100b7ec870aeab6425';
							$app_id     = '199313036828495';//254991121235149';	   	   
							$facebook = $this->facebook = new Facebook(array(
							                    'appId' => $app_id,
							                    'secret' =>$app_secret,
							                    'cookie' => false,
											));
							
							
							$user = $this->user = $facebook->getUser();
							
							if ($user) {
							  try {
							    // Proceed knowing you have a logged in user who's authenticated.
							    $user_profile = $facebook->api('/me');
							  } catch (FacebookApiException $e) {
							    error_log($e);
							    $user = null;
							  }
							//  $facebook->setAccessToken('AAAC1Ri7CX08BAMw3wPjLZB2dzARF7L0t7xDZAiw11ZA3jJd0WKlAEH26UX7aVfJFufBhThPrpbrZCkZAuMia8rN4eH6Armrg5oNQ4muFNQQZDZD');
							 // echo $accToken =  $facebook->getAccessToken();
							 //  $photos = $facebook->api('/me/albums?access_token='.$accToken); // gets all user photos 
								//echo '<pre>';print_r($photos);
							//	$facAlbId = $photos['data'][0]['id'];
							//   $photos = $facebook->api($facAlbId.'/photos/');
							//   echo '<pre>';print_r($photos);
							}
							
						return  $this->user;			
		
		
	}
	function  __destruct(){
		
		
	}
	function getUserProfile(){
		
		
	}
	function getUserProfileImage(){
		
		  return '<img src="https://graph.facebook.com/'.$this->user.'/picture">';
	}
	function getLoginLogoutUrlLinks(){
		
	// Login or logout url will be needed depending on current user state.
			$facebook = $this->facebook;
				$user = $this->user;
							if ($user) {
							  $this->logInLogOutLink = $facebook->getLogoutUrl();
							  echo ' <strong><em>You are not Connected.</em></strong>';
							} else {
								
								$this->logInLogOutLink   = $facebook->getLoginUrl(
							            array(
							                'scope'         => 'email,offline_access,publish_stream,user_photos,offline_access',
							                'redirect_uri'  => $pageURL
							            )
							    );
							    echo "<a href='$this->logInLogOutLink'>Facebook Login</a>";		 
	   							
							  
							}
				//return $this->logInLogOutLink;			
		
	}
	
	
	
	
	
}



?>