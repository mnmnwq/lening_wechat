<html>
    <head>
        <title>用户列表</title>
    </head>
    <body>
        <center>
            <table border="1">
                <tr>
                    <td>用户昵称</td>
                    <td>用户openid</td>
                    <td>操作</td>
                </tr>
                @foreach($info as $v)
                    <tr>
                        <td></td>
                        <td>{{$v}}</td>
                        <td>查看详情[昵称，城市，头像]</td>
                    </tr>
                @endforeach
            </table>
        </center>
    </body>
</html>