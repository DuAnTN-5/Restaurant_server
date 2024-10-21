<?php

namespace App\Mail;

use App\Models\Staff; // Đừng quên import mô hình Staff
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Staff $staff; // Thêm thuộc tính để lưu thông tin nhân viên

    /**
     * Create a new message instance.
     */
    public function __construct(Staff $staff)
    {
        $this->staff = $staff; // Gán giá trị nhân viên vào thuộc tính
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Staff Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.staff.created', // Đường dẫn tới file markdown
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
