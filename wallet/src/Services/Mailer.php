<?php
namespace App\Services;

class Mailer
{
    private $templating;

    public function __construct(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }

    public function sendMail(\Swift_Mailer $mailer,string $subject,string $from,string $to,string $view,array $data)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->templating->render ($view,$data),
                'text/html'
            );

        return boolval ($mailer->send($message));
    }

}