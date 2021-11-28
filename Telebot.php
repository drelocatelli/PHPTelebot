<?php

  class TeleBot {

    public $webservice = "https://api.telegram.org/bot";
    private $chatId; // id do chat
    private $botToken; // token do bot
    

    function __construct($chatId, $botToken) {
      $this->chatId = $chatId;
      $this->botToken = $botToken;
    }

    // request pelo telegram
    public function requestTelegram($request){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_URL => $this->webservice . $this->botToken . '/'. $request .'?chat_id='. $this->chatId
        ]);

        $response = curl_exec($curl);

        return $response;
    }

    // post by telegram methods
    public function sendTextTelegram($request, $text){
        echo "Enviando mensagem para " . $this->chatId . "\n";

        $url = "https://api.telegram.org/bot" . $this->botToken . "/sendMessage?chat_id=" . $this->chatId;
        $url = $url . "&text=" . urlencode($text);
        $ch = curl_init();
        $optArray = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

  }

$teleBot = new TeleBot("ID DO CHAT", "TOKEN DO BOT");

// caso queira enviar mensagem de texto
// $teleBot->sendTextTelegram('sendMessage', "Olá mundo!");

// bot rodando
while(true) {
  // obtem updates do chat
  $updates = json_decode($teleBot->requestTelegram('getUpdates'), true);
  foreach($updates["result"] as $update) {
    print("\n".$update['message']['from']['username']. " disse: " . $update['message']['text']);
  }
  sleep(3); // delay
}
