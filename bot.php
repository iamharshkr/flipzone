<?php
$token = 'Insert telegram api bot token!'; //Insert telegram api bot token!
$update = json_decode(file_get_contents('php://input'), TRUE);
$msg = $update['message']['text'];
$userid = $update['message']['chat']['id'];

if (preg_match('/^\/([sS]tart)/', $msg)) {
    $text = "I can search for products and give you some best results from them. \n\nTo use me send /Asearch for amazon and /Fsearch for Flipkart Name of Product. \n\nFor example: /Asearch smartwatch or /Fsearch smartwatch";
    sendMessage($userid, $text); //Welcome
} else if (preg_match('/^\/([aA]search)/', $msg)) {
    $keywords = str_replace('/asearch', '', strtolower($msg));
    if (strlen($keywords) > 3 && $keywords != '') {
        sendMessage($userid, 'Showing result for : ' . $keywords . ' from Amazon');
        include('amazon.php');
    } else {
        sendMessage($userid, "Please send correct keyword, otherwise i'll not able to help you."); //keywords is missing.
    }
} else if (preg_match('/^\/([fF]search)/', $msg)) {
    $keywords = str_replace('/fsearch', '', strtolower($msg));
    if (strlen($keywords) > 3 && $keywords != '') {
        sendMessage($userid, 'Showing result for : ' . $keywords . ' from Flipkart');
        include('flipkart.php');
    } else {
        sendMessage($userid, "Please send correct keyword, otherwise i'll not able to help you."); //keywords is missing.
    }
} else {
    $text = "Please use this format to search product. \n\n/Asearch for amazon and /Fsearch for Flipkart Name of Product. \n\nFor example: /Asearch smartwatch or /Fsearch smartwatch";
    sendMessage($userid, $text); //Welcome
}
function sendMessage($chat_id, $text)
{ //function sendMessage
    global $token;
    $args = ['chat_id' => $chat_id, 'parse_mode' => 'HTML', 'text' => $text, 'disable_web_page_preview' => '1'];
    return cURL('https://api.telegram.org/bot' . $token . '/sendMessage', $args);
}

function sendTelegramwithphoto($image_url, $text, $Prod_url)
{
    global $userid;
    global $token;
    $chat_id = $userid;
    $apiUrl = 'https://api.telegram.org/bot' . $token . '/sendPhoto';
    $inline_key = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "Shop Now",
                    "url" => $Prod_url
                ]
            ]
        ]
    ]);

    $arrs = [
        'chat_id' => $chat_id,
        'parse_mode' => 'HTML',
        'photo' => $image_url,
        'caption' => $text,
        'reply_markup' => $inline_key,
        'disable_web_page_preview' => '1'
    ];
    return cURL($apiUrl, $arrs);
}

function cURL($url, $args)
{ //function curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    $output = curl_exec($ch);
    return $output;
    curl_close($ch);
}
