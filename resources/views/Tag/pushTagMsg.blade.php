<html>
<head>
    <title></title>
</head>
<body>
<center>
    <form action="{{url('/wechat/do_push_tag_message')}}" method="post">
        @csrf
        <input type="hidden" name="tagid" value="{{$tagid}}">
        消息：
        <textarea name="message" id="" cols="30" rows="10"></textarea>
        <br>
        <br>
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>