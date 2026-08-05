<?php

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

class TelegramAdapter
{
    private Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        $this->bot = $bot;
    }

    public function sendMessage(array $content)
    {
        $chat_id = $content['chat_id'];
        $text = $content['text'];
        $opt = [];
        if (isset($content['reply_markup'])) {
            $opt['reply_markup'] = $content['reply_markup'];
        }
        if (isset($content['message_id'])) {
            $opt['reply_to_message_id'] = $content['message_id'];
        }
        
        return $this->bot->sendMessage($text, ...array_merge(['chat_id' => $chat_id], $opt));
    }

    public function editMessageText(array $content)
    {
        $chat_id = $content['chat_id'];
        $message_id = $content['message_id'] ?? null;
        $text = $content['text'] ?? '';
        $opt = [];
        if (isset($content['reply_markup'])) {
            $opt['reply_markup'] = $content['reply_markup'];
        }

        return $this->bot->editMessageText($text, ...array_merge(['chat_id' => $chat_id, 'message_id' => $message_id], $opt));
    }

    public function editMessageReplyMarkup(array $content)
    {
        $chat_id = $content['chat_id'];
        $message_id = $content['message_id'] ?? null;
        $opt = [];
        if (isset($content['reply_markup'])) {
            $opt['reply_markup'] = $content['reply_markup'];
        }

        return $this->bot->editMessageReplyMarkup(...array_merge(['chat_id' => $chat_id, 'message_id' => $message_id], $opt));
    }

    public function deleteMessage(array $content)
    {
        $chat_id = $content['chat_id'];
        $message_id = $content['message_id'];
        
        return $this->bot->deleteMessage($chat_id, $message_id);
    }

    public function sendDocument(array $content)
    {
        $chat_id = $content['chat_id'];
        $document = $content['document']; // path
        $caption = $content['caption'] ?? '';
        
        $file = fopen($document, 'r+');
        $opt = ['chat_id' => $chat_id];
        if ($caption) {
            $opt['caption'] = $caption;
        }

        return $this->bot->sendDocument(InputFile::make($file), ...$opt);
    }
    
    public function sendPhoto(array $content)
    {
        $chat_id = $content['chat_id'];
        $photo = $content['photo']; // URL or path
        $opt = ['chat_id' => $chat_id];
        
        if (isset($content['caption'])) {
            $opt['caption'] = $content['caption'];
        }
        if (isset($content['reply_markup'])) {
            $opt['reply_markup'] = $content['reply_markup'];
        }

        return $this->bot->sendPhoto($photo, ...$opt);
    }

    public function forwardMessage(array $content)
    {
        $chat_id = $content['chat_id'];
        $from_chat_id = $content['from_chat_id'];
        $message_id = $content['message_id'];
        
        return $this->bot->forwardMessage($chat_id, $from_chat_id, $message_id);
    }

    // Keyboard Builders
    public function buildInlineKeyboardButton($text, $url = "", $callback_data = "", $switch_inline_query = "")
    {
        $btn = InlineKeyboardButton::make($text);
        if ($url != "") $btn->url = $url;
        if ($callback_data != "") $btn->callback_data = $callback_data;
        if ($switch_inline_query != "") $btn->switch_inline_query = $switch_inline_query;
        return $btn;
    }

    public function buildInlineKeyBoard(array $options)
    {
        $keyboard = InlineKeyboardMarkup::make();
        foreach ($options as $row) {
            $keyboard->addRow(...$row);
        }
        return $keyboard;
    }

    public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
    {
        $btn = KeyboardButton::make($text);
        if ($request_contact) $btn->request_contact = true;
        if ($request_location) $btn->request_location = true;
        return $btn;
    }

    public function buildKeyBoard(array $options, $onetime = false, $resize = true, $selective = false)
    {
        if (empty($options)) {
            return ReplyKeyboardRemove::make(true);
        }
        
        $keyboard = ReplyKeyboardMarkup::make(
            resize_keyboard: $resize,
            one_time_keyboard: $onetime,
            selective: $selective
        );
        
        foreach ($options as $row) {
            $keyboard->addRow(...$row);
        }
        return $keyboard;
    }
}
