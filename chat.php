<?php header("content-type:text/html;charset=utf-8");
    session_start();
//    $session=$_SESSION['userinfo'];
//    var_dump($session);die;
	if(empty($_SESSION['userinfo'])){
		echo "<script>alert('请登录');location.href='login.html'</script>";
	};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>聊天室</title>
    <script src="jquery-3.2.1.min.js"></script>
</head>
<body>
<p align="center" user_id="<?php  echo $_SESSION['userinfo']['user_id']; ?>">
    欢迎<font style="color: mediumvioletred" id="user_name"> <?php echo $_SESSION['userinfo']['user_name']; ?></font>来到聊天室
</p>
<div  align="center">
    请输入文本：<input type="text" id="content">
    <input type="submit" value="发送" id="submit"><hr>
</div>
<div align="center">
    <div id="div"  style="width:800px;height:800px;border:#0b2e13 1px solid;background-color:#1c7430"></div>
</div>
</body>
</html>
<script>
    var ws_server='ws://swoole.1809.com:9502/';
    var ws=new WebSocket(ws_server);
    ws.onopen=function(){
        $('#submit').click(function(){
            var text=$('#content').val();
            var user_id=$('p').attr('user_id');
            var user_name=$('#user_name').text();
            var data={
                type:"message",
                text:text,
                user_id:user_id,
                user_name:user_name,
                data:Date.now()
            }
            ws.send(JSON.stringify(data));
        });
    }
    ws.onmessage=function(d){
        var data=JSON.parse(d.data);
        var up="<b>"+data['user_name']+"："+data['text']+"</b>------"+data['time']+"<hr>";
        $('#div').append(up);
    }
</script>
