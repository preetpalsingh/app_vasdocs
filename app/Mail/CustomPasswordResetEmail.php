<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SpForms.
 */
class CustomPasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    //public $subject, $content, $replyTo, $images;
    public $subject, $content, $images;

    /**
     * SpForms constructor.
     *
     * @param Request $request
     */
    //public function __construct($subject, $content, $replyTo = null, array $images = [])
    public function __construct($subject, $content, array $images = [])
    {
        $this->subject = $subject;
        $this->content = $content;
        //$this->replyTo = $replyTo;
        $this->images = is_array($images) ? $images : [];
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* return $this->text('emails.sp_custom_mail_plain')
                ->subject($this->subject)
                ->with([
                    'content' => $this->content,
                ]); */

                

                //$mail = $this->to('preetpalsingh1992@gmail.com', config('mail.from.name'))
                $mail = $this->view('emails.sp_custom_mail_plain')
            //->text('frontend.mail.contact-text')
            ->subject(__($this->subject, ['app_name' => app_name()]))
            //->from('develop@propertyhomes.in', 'Chatha & CO')
            //->replyTo('develop@propertyhomes.in', 'Chatha & CO')
            ->with([
                'content' => $this->content,
            ])
            ;

                //echo $this->subject;die();

        /* $mail = $this->markdown('emails.sp_custom_mail_plain')
                 ->subject($this->subject)
                 ->with([
                     'content' => $this->content,
                 ])

                 //->with('content',$this->content)
                 
                 ; */
                 
        if ( is_array(  $this->images ) ) {
            foreach ($this->images as $image) {
                //$mail->attach($image);
                // Get the original file name
                $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // Get the file extension
                $extension = $image->getClientOriginalExtension();

                // Generate a new attachment name using the original name and current timestamp
                $newAttachmentName = "{$fileName}_" . time() . ".{$extension}";

                // Attach the file data with the new name
                $mail->attachData($image->get(), $newAttachmentName);
            }
        } 

        /* if ($this->replyTo) {

            $mail->replyTo($this->replyTo);

        } else {

            $mail->replyTo('info@bsc-icc.co', 'info');
        } */

        return $mail;
    }
}
