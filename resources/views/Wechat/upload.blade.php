<html>
<head>
    <title>用户列表</title>
</head>
<body>
<center>
    <form action="{{url('wechat/do_upload')}}" method="post" enctype="multipart/form-data">
        @csrf
        <select name="type" id="">
            <option value="1">图片</option>
            <option value="2">音频</option>
            <option value="3">视频</option>
            <option value="4">缩略图</option>
        </select>
        <input type="file" name="file_name" value="">
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>