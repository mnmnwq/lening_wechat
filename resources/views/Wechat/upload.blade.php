<html>
<head>
    <title>用户列表</title>
</head>
<body>
<center>
    <img src="{{asset('/storage/goods/jhVbINfxgIRuWsF542OrlUdiip5x0YGV12YYa97t.jpeg')}}" alt="">
    <form action="{{url('wechat/do_upload')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" value="">
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>