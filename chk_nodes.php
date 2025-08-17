<?php

// 参考 Telegram Bot API
// https://pisuke-code.com/php-how-to-create-telegram-bot/
// Cron に登録
// * * * * * php /<path to file>/chk_nodes.php


$urls = [];
$urls[] = 'https://nis1-node.example.com:7890/chain/height';
$urls[] = 'https://symbol-node.example.com:3000/chain/info';

foreach ($urls as $url) {

    $conn = curl_init();
    curl_setopt($conn, CURLOPT_URL, $url);
    curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 3); // 接続までの待機時間
    curl_setopt($conn, CURLOPT_TIMEOUT, 4);        // レスポンス完了までの待機時間
    $res = curl_exec($conn);
    $json_res = json_decode($res);
    $httpcode = curl_getinfo($conn, CURLINFO_RESPONSE_CODE);
    curl_close($conn);

    if ($httpcode != 200) {
        // telegram
        exec("curl https://api.telegram.org/bot[API_TOKEN]/sendMessage -d chat_id=[CHAT_ID] -d text=NodeDown_$url");
    }

    sleep (2);
}

