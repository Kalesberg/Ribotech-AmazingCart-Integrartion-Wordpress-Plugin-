<?php
	class AndroidGCM{

		private $gcmUrl = 'https://android.googleapis.com/gcm/send';
		private $apiKey = '';

		private $logPath = '/usr/local/android/android.log';

		private $showErrors = true;														/*=== Whether to trigger errors ===*/
	
		private $logErrors = true;															/*=== Whether ANDROID PUSH should log errors ===*/
		
		private $logMaxSize = 1048576;

		function __construct( $apikey=NULL, $logpath=NULL) 
		{
			if( strlen($apikey) == 0 ) $this->_triggerError('Missing api key.', E_USER_ERROR);
			
			if(!empty($logpath) && file_exists($logpath))
			{
				$this->logPath = $logpath;
			}

			$this->apiKey = $apikey;
		}

		function _sendGooglePush($registration_id, $data)
		{
			if(strlen($registration_id)==0) $this->_triggerError('Missing registration id.', E_USER_ERROR);
			
			//registration ids
			$registrationIDs = array($registration_id);
			 			
			$fields = array('registration_ids' => $registrationIDs, 'data' => $data);
			 
			//http header
			$headers = array('Authorization: key=' . $this->apiKey, 'Content-Type: application/json');
			 
			//curl connection
			$ch = curl_init();
			 
			curl_setopt($ch, CURLOPT_URL, $this->gcmUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			 
			$result = curl_exec($ch);
			 
			//echo_array($result);
			//exit;
			
			curl_close($ch);
		}

		/**
		 * Trigger Error
		 *
		 * Use PHP error handling to trigger User Errors or Notices.  If logging is enabled, errors will be written to the log as well. 
		 * Disable on screen errors by setting showErrors to false;
		 *
		 * @param string $error Error String
		 * @param int $type Type of Error to Trigger
		 * @access private
		 */	
		private function _triggerError($error, $type=E_USER_NOTICE)
		{
			$backtrace = debug_backtrace();
			$backtrace = array_reverse($backtrace);
			$error .= "\n";
			$i=1;
			foreach($backtrace as $errorcode){
				$file = ($errorcode['file']!='') ? "-> File: ".basename($errorcode['file'])." (line ".$errorcode['line'].")":"";
				$error .= "\n\t".$i.") ".$errorcode['class']."::".$errorcode['function']." {$file}";
				$i++;
			}
			$error .= "\n\n";
			if($this->logErrors && file_exists($this->logPath)){
				if(filesize($this->logPath) > $this->logMaxSize) $fh = fopen($this->logPath, 'w');
				else $fh = fopen($this->logPath, 'a');
				fwrite($fh, $error);
				fclose($fh);
			}
			if($this->showErrors) trigger_error($error, $type);
		}

		public function processQueue($token, $msg, $eid=0, $eflag=true)
		{
			$push_data["message"] = $msg;
			$push_data["eid"] = (int)$eid;
			
			$this->_sendGooglePush($token, $push_data);
		}
	}
?>