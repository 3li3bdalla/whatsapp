<?php
	
	namespace AliAbdalla\Whatsapp\Core;
	
	use Carbon\Carbon;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\Storage;
	use Symfony\Component\HttpClient\Exception\ClientException;
	use Symfony\Component\HttpClient\HttpClient;
	use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
	
	class WhatsappCore
	{
		
		private $token;
		private $baseUrl;
		private $shouldMadeRequestEvery = 6;
		private $response;
		private $error = null;
		private $targetLink;
		private $filename = "";
		private $requestMethod = "GET";
		
		
		public function __construct()
		{
			$this->token = config('services.whatsapp.token');
			$this->baseUrl = config('services.whatsapp.base_url');
		}
		
		
		public function sendMessage($message, $phoneNumber)
		{
			$this->targetLink = "sendMessage";
		
			
			if($this->isList($phoneNumber)) {
				return $this->sendList($message, $phoneNumber);
			} else {
				return $this->sendSingle($message, $phoneNumber);
			}
		}
		
		private function isList($phone)
		{
			return is_array($phone);
		}
		
		private function sendList($message, $phoneNumber)
		{
			
			foreach($phoneNumber as $phone) {
				$this->run($message, $phone);
			}
			
			return $this;
		}
		
		private function run($message, $phone)
		{
			return $this->perform($message, $phone);
		}
		
		private function perform($message, $phone)
		{
			$client = $this->getClient();
		
			if($this->requestMethod == 'GET')
			{
				$data = [
					'query' => [
						'body' => $message,
						'filename' => $this->filename,
						'phone' => $phone,
						'token' => $this->token
					]
				];
			}else{
				$data = [
					'body' => [
						'body' => $message,
						'filename' => $this->filename,
						'phone' => $phone,
						
					]
				];
			}
			try {
				$this->response = $client->request(
					$this->requestMethod, $this->baseUrl . $this->targetLink . "?token=" .  $this->token, $data
				);

//				return $this;
			} catch(TransportExceptionInterface $e) {
				$this->error = $e;
			} catch(ClientException $e) {
				$this->error = $e;
			}
			return $this;
		}
		
		private function getClient()
		{
			return HttpClient::create();
		}
		
		private function sendSingle($message, $phoneNumber)
		{
			return $this->run($message, $phoneNumber);
		}
		
		public function sendFile($storagePath, $phoneNumber, $filename = '')
		{
			$this->targetLink = "sendFile";
			$this->requestMethod = "POST";
			$this->filename = $filename;
			$message = Storage::url($storagePath);
			if($this->isList($phoneNumber)) {
				return $this->sendList($message, $phoneNumber);
			} else {
				return $this->sendSingle($message, $phoneNumber);
			}
		}
		
		/**
		 * @return mixed
		 */
		public function getResponse()
		{
			return $this->response;
		}
		
		/**
		 * @return null
		 */
		public function getError()
		{
			return $this->error;
		}
		
		private function handleMaxRequestPerSeconds()
		{
			$lastMessageTime = Carbon::parse($this->getLastMessageTime());
			if($lastMessageTime->diffInSeconds(Carbon::now()) < $this->shouldMadeRequestEvery) {
				sleep($this->shouldMadeRequestEvery);
			}
			
		}
		
		private function getLastMessageTime()
		{
			return Cache::store('file')->get('whatsapp_last_message_time', Carbon::now()->subMinute()->toDateTimeString());
//			return Carbon::parse(Cache::get('whatsapp_last_message_time', Carbon::now()->subMinute()->toDateTimeString()));
		}
		
	}