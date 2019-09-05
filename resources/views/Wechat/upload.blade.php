<html>
<head>
    <title>用户列表</title>
</head>
<body>
<center>
    <form action="{{url('wechat/do_upload')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file_name" value="">
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>