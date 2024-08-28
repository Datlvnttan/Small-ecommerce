<?php

namespace Modules\Mailer\Jobs;

use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailables\Address;
use Modules\Mailer\Emails\AutomaticMassEmail;

class AutomaticMassEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subject, $contentView, $data;
    private $email;
    private $fullname;
    public $tries = 3;
    protected $successCallback;
    protected $failedCallback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $fullname, $subject, $contentView, mixed $data, $successCallback = null, $failedCallback = null)
    {
        $this->email = $email;
        $this->fullname = $fullname;
        $this->subject = $subject;
        $this->contentView = $contentView;
        $this->data = $data;
        $this->successCallback = $successCallback;
        $this->failedCallback = $failedCallback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            Mail::to(new Address($this->email, $this->fullname))->send(new AutomaticMassEmail($this->subject, $this->contentView, $this->data));
            if (isset($this->successCallback)) {
                ($this->successCallback)($this);
            }
        } catch (\Exception $e) {
            if (isset($this->failedCallback)) {
                ($this->failedCallback)($this);
            }
            throw $e;
        }
    }
}
