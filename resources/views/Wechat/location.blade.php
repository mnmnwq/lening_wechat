<html>
<head>
    <title></title>
</head>
<body>
<center>

</center>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wxca8b96fc61e2e6b3', // 必填，公众号的唯一标识
        timestamp: '{{$timestamp}}', // 必填，生成签名的时间戳
        nonceStr: '{{$nonceStr}}', // 必填，生成签名的随机串
        signature: '{{$signature}}',// 必填，签名
        jsApiList: ['openLocation','getLocation'] // 必填，需要使用的JS接口列表
    });

    wx.ready(function(){
        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        wx.openLocation({
            latitude: 0, // 纬度，浮点数，范围为90 ~ -90
            longitude: 0, // 经度，浮点数，范围为180 ~ -180。
            name: '', // 位置名
            address: '', // 地址详情说明
            scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
            infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
        });
    });

</script>
</body>
</html>