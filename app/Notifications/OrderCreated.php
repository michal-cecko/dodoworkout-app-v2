<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\FormSubmission;
use App\Models\Order;
use Exception;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification
{
    protected Event $event;
    protected ?FormSubmission $formSubmission;

    /**
     * Create a new notification instance.
     * @throws Exception
     */
    public function __construct(protected Order $order)
    {
        $this->formSubmission = FormSubmission::where("order_id", $order->id)->with(["priceable", "formFields.formField"])->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = (new MailMessage)
            ->subject(__('ord_email_subject') . " - " . $this->order->fullOrderNumber)
            ->markdown("email.order.order-details", [
                'order' => $this->order,
                'formSubmission' => $this->formSubmission,
            ]);

        $this->addAttachments($message);

        return $message;
    }

    private function addAttachments(MailMessage $message): void
    {
        $attachments = $this->formSubmission?->priceable?->getMedia("confirmation_email_attachments") ?? [];
        if (empty($attachments)) return;

        foreach ($attachments as $attachment) {
            if (file_exists($attachment->getPath())) {
                $message->attach($attachment->getPath(), [
                    'as' => $attachment->file_name,
                    'mime' => $attachment->mime_type,
                ]);
            }
        }
    }
}
