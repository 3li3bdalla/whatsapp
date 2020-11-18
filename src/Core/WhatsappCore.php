<?php
	
	namespace AliAbdalla\Whatsapp\Core;
	
	use Carbon\Carbon;
	use Illuminate\Filesystem\Cache;
	use Symfony\Component\HttpClient\HttpClient;
	use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
	
	class WhatsappCore
	{
		
		private $token;
		private $baseUrl;
		private $shouldMadeRequestEvery = 6;
		private $response;
		private $error = null;
		
		
		public function __construct()
		{
			$this->token = config('services.whatsapp.token');
			$this->token = config('services.whatsapp.base_url');
		}
		
		
		public function sendMessage($message, $phoneNumber) // [2332,235235,]
		{
			if($this->isList($phoneNumber)) {
				$this->sendList($message, $phoneNumber);
			} else {
				$this->sendSingle($message, $phoneNumber);
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
		}
		
		private function run($message, $phone)
		{
			$this->handleMaxRequestPerSeconds();
			$this->perform($message, $phone);
			$this->setLastMessageTime();
		}
//
//		public function sendFile($body, $phoneNumber)
//		{
//			return "test facades";
//		}
//
//		public function sendLocation($location, $phoneNumber)
//		{
//			return "test facades";
//		}
		
		private function handleMaxRequestPerSeconds()
		{
			$lastMessageTime = $this->getLastMessageTime();
			if($lastMessageTime->diffInSeconds(Carbon::now()) < $this->shouldMadeRequestEvery) {
				sleep($this->shouldMadeRequestEvery);
			}
			
		}
		
		private function getLastMessageTime()
		{
			return Carbon::parse(Cache::get('whatsapp_last_message_time', Carbon::now()->subMinute()));
		}
		
		private function perform($message, $phone)
		{
			$client = $this->getClient();
			try {
				$this->response = $client->request('GET', $this->getUrl());
			} catch(TransportExceptionInterface $e) {
				$this->error = $e;
			}
		}
		
		private function getClient()
		{
			return HttpClient::create();
		}
		
		private function getUrl()
		{
			return $this->baseUrl . "?token=" . $this->token;
		}
		
		private function setLastMessageTime()
		{
			return Carbon::parse(Cache::put('whatsapp_last_message_time', Carbon::now(), 1440));
		}
		
		private function sendSingle($message, $phoneNumber)
		{
			$this->run($message, $phoneNumber);
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
		
	}