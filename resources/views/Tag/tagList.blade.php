<html>
<head>
    <title></title>
</head>
<body>
<center>
    <h1>公众号标签管理</h1>
    <a href="{{url('/wechat/add_tag')}}">增加标签</a>
    <br>
    <br>
    <br>
    <table border="1">
        <tr>
            <td>tag_id</td>
            <td>tag_name</td>
            <td>标签下粉丝数</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td><a href="{{url('')}}">删除</a> | <a href="{{url('')}}">修改</a></td>
        </tr>
        @endforeach
    </table>
</center>
</body>
</html>