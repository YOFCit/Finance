<?php

namespace App\Mail;

use App\Models\UrgentPaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestStatusMail extends Mailable
{
  use Queueable, SerializesModels;

  public $requestModel;

  public function __construct(UrgentPaymentRequest $requestModel)
  {
    $this->requestModel = $requestModel;
  }

  public function build()
  {
    return $this->subject('Updating your payment request')
      ->markdown('Mails.request_status');
  }
}
