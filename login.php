<?php
    $user_name=$_POST['user_name'];
    $user_pwd=$_POST['user_pwd'];
    if(empty($user_name)){
        echo "姓名不能为空哦(｡･ω･｡)";exit;
    }
    if(empty($user_pwd)){
        echo "密码不能为空哦(｡･ω･｡)";exit;
    }
    //链接数据库
    $con=mysqli_connect('192.168.65.1','root','root','ecshop');
    $sql="select * from shop_user where user_name='$user_name'";
    $res=mysqli_query($con,$sql);
    $arr=mysqli_fetch_assoc($res);
    session_start();
    if($arr){//有该账户
        if(password_verify($user_pwd,$arr['user_pwd'])){
            $userinfo=[
                'user_id'=>$arr['user_id'],
                'user_name'=>$arr['user_name'],
            ];
            $_SESSION['userinfo'] =$userinfo;
            header("Refresh:0;url=chat.php");
        }else{
            $response=[
                'errno'=>10002,
                'msg'=>"密码错误",
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }else{
        $response=[
            'errno'=>10001,
            'msg'=>"账号不存在",
        ];
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }