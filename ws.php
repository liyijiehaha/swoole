<?php

$server = new swoole_websocket_server("0.0.0.0",9502);
$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});
$server->on('message', function($server, $frame) {
    $data=json_decode($frame->data,true);
    $time=time();
    $text=$data['text'];
    $user_name=$data['user_name'];
    $user_id=$data['user_id'];
    $db_user="root";
    $db_password="root";
    $dbh =new PDO("mysql:host=192.168.65.1;dbname=sw_chat",$db_user,$db_password);
    $db_sql="insert into chat_messages (text,user_id,user_name,create_time) values('{$text}','{$user_id}','{$user_name}','$time')";
    $dbh->exec($db_sql);
    $errcode=$dbh->errorCode();
    var_dump($errcode);
    $errinfo=$dbh->errorInfo();
    var_dump($errinfo);
    $send_data=[
        'text'=>$text,
        'user_name'=>$user_name,
        'user_id'=>$user_id,
        'time'=>date('Y-m-d H:i:s'),
    ];
    $data=json_encode($send_data);
    foreach ($server->connections as $fds){
        if($server->isEstablished($fds)){
            $server->push($fds,$data);
        }
    }
});
$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});
$server->start();