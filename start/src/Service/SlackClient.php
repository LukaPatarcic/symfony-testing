<?php


namespace App\Service;

use App\Helper\LoggerTrait;
use Nexy\Slack\Client;

class SlackClient
{

    use LoggerTrait;

    /**
     * @var Client
     */
    private $slack;

    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    public function sendMessage(string $from, string $message)
    {
        $this->logger->info ('Sending message to slack',[
            'extra' => 'This is an extra message',
        ]);

        $message = $this->slack->createMessage ()
            ->from ($from)
            ->setText ($message);

        $this->slack->sendMessage ($message);

    }
}