<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $content;

    /**
     * Tạo một thể hiện mới của lớp mailable.
     *
     * @param $name
     * @param $email
     * @param $content
     */
    public function __construct($name, $email, $content)
    {
        $this->name = $name;
        $this->email = $email;
        $this->content = $content;
    }

    /**
     * Xây dựng email để gửi.
     *
     * @return $this
     */
    public function build()
{
    return $this->subject('Liên hệ từ khách hàng')
                ->view('emails.contact')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'content' => $this->content
                ]);
}
}
