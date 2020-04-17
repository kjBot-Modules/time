<?php
namespace kjBotModule\kj415j45\time;

use kjBot\SDK\CQCode;
use kjBot\Framework\Module;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Event\MessageEvent;

class Main extends Module{
    public function process(array $args, MessageEvent $event){
        date_default_timezone_set('Asia/Tokyo');

        $minute=(int)date('i');
        $hour=(int)date('H');
        if($minute>=45)$hour++;
        if($hour==24)$hour=0;

        return [
            $event->sendBack(DataStorage::GetData("time/{$hour}.txt")),
            $event->sendBack(CQCode::Record('base64://'.base64_encode(DataStorage::GetData("time/{$hour}.mp3")))),
        ];
    }
}