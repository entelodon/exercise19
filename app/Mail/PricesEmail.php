<?php

namespace App\Mail;

use App\DTOs\interfaces\iRetrievedData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricesEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var iRetrievedData $retrievedData
     */
    private $retrievedData;

    /**
     * Create a new message instance.
     *
     * @param iRetrievedData $retrievedData
     */
    public function __construct(iRetrievedData $retrievedData)
    {
        $this->retrievedData = $retrievedData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('retrievedData')->with('retrievedData', $this->retrievedData);
    }
}
