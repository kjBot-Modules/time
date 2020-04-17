<?php
namespace kjBotModule\kj415j45\time;

use kjBot\SDK\CQCode;
use kjBot\Framework\Module;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Event\MessageEvent;
use kjBotModule\kj415j45\CoreModule\Access;
use kjBotModule\kj415j45\CoreModule\AccessLevel;

class Update extends Module{
    public function process(array $args, MessageEvent $event){
        Access::Control($event)->hasLevelOrDie(AccessLevel::Developer);

        $web = file_get_contents($args[1]);
        if(false == $web)throw new \Exception('Update failed, no data found');
        preg_match_all('{https:\\\/\\\/img\.moegirl\.org\\\/common\\\/\S{1}\\\/\S{2}\\\/[%a-zA-Z0-9_]*\.mp3}', $web, $result);
        $start=count($result[0])-24-1;
        for($i=$start, $j=0; $i<$start+24 ; $i++, $j++){
            $target = str_replace("\\","",$result[0][$i]);
            DataStorage::SetData("time/{$j}.mp3", file_get_contents($target));
            $event->sendBack(CQCode::Record('base64://'.base64_encode(DataStorage::GetData("time/{$j}.mp3"))));
        }
        unset($result);
        preg_match_all('/[0-9]{4}：[ 　\S]*<\/span><br \/>(?:[0-9]{4}：)?([ 　\S]*)/', $web, $result); //警惕 U+3000
        for($i=0;$i<24;$i++)
        DataStorage::SetData("time/{$i}.txt", preg_replace('/<[\S ]*>[\s\S]*<\/\S*>/','',$result[1][$i]));

        return [
            $event->sendBack(DataStorage::GetData('time/0.txt')),
            $event->sendBack(CQCode::Record('base64://'.base64_encode(DataStorage::GetData('time/0.mp3')))),
        ];
    }
}