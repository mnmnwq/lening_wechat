<html>
<head>
    <title>用户列表</title>
</head>
<body>
<center>
    <table border="1">
        <tr>
            <td>uid</td>
            <td>用户名</td>
            <td>分享码</td>
            <td>二维码</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->name}}</td>
            <td>{{$v->id}}</td>
            <td><img src="{{asset($v->qrcode_url)}}" alt="" height="100" ></td>
            <td><a href="{{url('/agent/create_qrcode')}}?uid={{$v->id}}">生成专属二维码</a></td>
        </tr>
        @endforeach
    </table>
</center>
<script src="{{asset('mstore/js/jquery.min.js')}}"></script>

</body>
</html>