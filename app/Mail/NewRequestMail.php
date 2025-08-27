<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuarios;

class NewRequestMail extends Mailable
{
  use Queueable, SerializesModels;

  public $requestData;
  public $link;

  /**
   * Create a new message instance.
   */
  public function __construct($requestData, $link)
  {
    $this->requestData = $requestData;
    $this->link = $link;
  }

  /**
   * Build the message.
   */


  public function build()
  {
    $empleado = Usuarios::where('requestor', $this->requestData->requestor)->first();
    return $this->subject('Pending Approval Request')
      ->markdown('Mails.new_request')
      ->with([
        'requestData' => $this->requestData,
        'link' => $this->link,
        'empleado' => $empleado,
      ]);
  }
}
