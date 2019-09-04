<html>
    <head>
        <title>登陆</title>
    </head>
    <body>
    <center>
        <h1>登陆</h1>
        用户名：<input type="text"> <br/>
        密码: <input type="password"> <img src="{{}}" alt=""><br/>
        第三方登录 <button id="wechat_btn" type="button">微信授权登陆</button>
    </center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            $('#wechat_btn').click(function(){
                window.location.href = '{{url('/wechat/login')}}';
            });
        });
    </script>
    </body>
</html>