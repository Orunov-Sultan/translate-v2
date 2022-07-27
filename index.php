<?php
require_once 'Telegram.php';
require_once 'TelegramErrorLogger.php';
require_once ('vendor/autoload.php');

use \Dejurin\GoogleTranslateForFree;

const TOKEN = 'ВВЕДИТЕ ВАШ ТОКЕН';
const BASE_URL = 'https://api.telegram.org/bot'. TOKEN .'/';

$telegram = new Telegram(TOKEN);

$chat_id = $telegram->ChatID() ?? '';
$text = $telegram->Text() ?? '';

if ($text == '/start' || $text == 'start'){

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Добро пожаловать!"
    ]);
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Я бот-переводчик и помогу перевести с английского на русский и обратно. Просто отправьте мне слово или фразу"
    ]);
}elseif (!empty($text)){

    if (preg_match('#[a-z]+#i', $text)){
        $source = 'en';
        $target = 'ru';
    }else{
        $source = 'ru';
        $target = 'en';
    }

    $attempts = 5;

    $tr = new GoogleTranslateForFree();
    $result = $tr->translate($source, $target, $text, $attempts);

    if ($result){

        $content = [
            'chat_id' => $chat_id,
            'text' => $result
        ];
        $telegram->sendMessage($content);

    }else{
        $content = [
            'chat_id' => $chat_id,
            'text' => 'Упс... Я не смог перевести'
        ];
        $telegram->sendMessage($content);
    }
}else{
    $content = [
        'chat_id' => $chat_id,
        'text' => 'Это бот-переводчик, поэтому он ожидает от вас текст для перевода...'
    ];
    $telegram->sendMessage($content);
}