<html>
<head>
    <title></title>
</head>
<body>
<center>
    <form action="{{url('/wechat/do_add_tag')}}" method="post">
        @csrf
        标签名称：<input type="text" name="tag_name" id="">
        <br>
        <br>
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>