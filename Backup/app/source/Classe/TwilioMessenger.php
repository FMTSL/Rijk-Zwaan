<?php

namespace Source\Classe;

use Twilio\Rest\Client;


class TwilioMessenger
{
  private $sid;
  private $token;
  private $from;

  /**
   *
   * Metodo responsavel pela definição dos dados API
   */
   public function __construct($sid = 'AC790aacc2ca669041ea060d894f500999', $token = "df2fa3e9ce564169581e36edac551bed", $from = "+13609269775")
  //public function __construct($sid = 'AC790aacc2ca669041ea060d894f500999', $token = "df2fa3e9ce564169581e36edac551bed", $from = "+14155238886")
  
  {
    $this->sid      = $sid;
    $this->token  = $token;
    $this->from  = $from;
  }

  /**
   * Metodo responsavel por enviar as mensagens WhatsApp
   */
  public function send($to, $body)
  {
    //dd($to);
    $twilio = new Client($this->sid, $this->token);

    $message = $twilio->messages
      ->create(
        "whatsapp:{$to}", // to 
        array(
          "from" => "whatsapp:{$this->from}",
          "body" => $body
        )
      );

    return $message->status;
  }
}