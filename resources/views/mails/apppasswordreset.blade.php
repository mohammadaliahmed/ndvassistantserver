<html>
<head>
</head>
<body style="margin: 0px;">
<div style="width: 100%;">

    <div style="float: left; background-color: #ef0f72; width: 100%; color: #fff; padding: 5px 5px 15px 5px;">
        <h4 style="line-height: 30px;width: 100%; font-size: 17px; text-align: center; margin: 10px;">NDVHS Sahoolat</h4>
        <p style="text-align: center;">Password Reset</p>
    </div>

    <div style="width: 100%; float: left; margin-top: 20px; padding-bottom: 100px;">
        {{--<p> <strong>Submitted By:</strong> {{Auth::user()->name}}</p><br>--}}
        Please click on the link to reset your password
        <p> <strong>Activation link:</strong> <a href="{{$link}}">{{$link}}</a></p><br>

    </div>
</div>
</body>
</html>