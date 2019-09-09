<html>
<head>
    <title>素材管理</title>
</head>
<body>
<center>
    <h1>素材管理</h1>
    <a href="{{url('/wechat/upload')}}">上传永久素材</a><br/><br/>
    <table border="1">
        <tr>
            <td>id</td>
            <td>media_id</td>
            <td>type</td>
            <td>path</td>
            <td>add_time</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->media_id}}</td>
            <td>@if($v->type == 1)image @elseif($v->type == 2)voice @elseif($v->type == 3)video @elseif($v->type)thumb @endif</td>
            <td>{{$v->path}}</td>
            <td>{{date('Y-m-d H:i',$v->add_time)}}</td>
            <td>
                <a href="{{url('/wechat/del_source')}}">删除</a> @if($v->is_download == 1) | <a href="{{url('/wechat/download_source')}}?id={{$v->id}}">下载资源</a>@endif
            </td>
        </tr>
        @endforeach

    </table>
    <br/>
    <br/>
    <button type="button" class="pre_page" data="{{$pre_page}}">上一页</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button type="button" class="next_page" data="{{$next_page}}">下一页</button>
</center>
<script src="{{asset('mstore/js/jquery.min.js')}}"></script>
<script>
    $(function(){
        $(".pre_page").click(function(){
            var pre_page = $(this).attr('data');
            window.location.href = '{{url('/wechat/source')}}?page='+pre_page+'&source_type={{$source_type}}';
        });
        $(".next_page").click(function(){
            var next_page = $(this).attr('data');
            window.location.href = '{{url('/wechat/source')}}?page='+next_page+'&source_type={{$source_type}}';
        });
    });
</script>
</body>
</html>