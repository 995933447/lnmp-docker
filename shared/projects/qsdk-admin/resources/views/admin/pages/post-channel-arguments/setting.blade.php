<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }
        body {
            background: #EDF0F5;
        }
        #setting-form {
            padding: 20px;
            width: 70%;
            margin: 50px auto;
            display: flex;
            justify-content:space-between;
            flex-wrap: wrap;
            background: white;
        }
        #setting-form input {
            border: 1px solid #D3D6DE;
        }
        #setting-form .argument-block {
            width: 100%;
            height: 34px;
            display: flex;
            margin-bottom: 10px;
            justify-content: space-between;
        }
        #setting-form #argument-blocks {
            width: 100%;
        }
        #setting-form .argument-block #argument-key {
            width: 20%;
            height: 100%;
            padding-left: 5px;
            outline: none;
        }
        #setting-form .argument-block #argument-placeholder,#argument-sort {
            width: 20%;
            height: 100%;
            padding-left: 5px;
            outline: none;
        }
        #setting-form .argument-block #argument-type {
            width: 15%;
            height: 100%;
        }
        #setting-form .argument-block #delete-button {
            width: 5%;
            height: 100%;
        }
        #setting-button {
            width: 100%;
            height: 34px;
            display: flex;
            justify-content: center;
        }
        #setting-form #add-button,#submit-button {
            padding: 0 10px;
            height: 100%;
            margin-right: 10px;
            border:none;
            background: rgb(60, 141, 188);
            color: white;
            border-radius: 2px;
        }
        #setting-form #argument-version {
            width: 100%;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #setting-form #argument-version select {
            width: 50px;
            height: 30px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    @if(!empty(session('error')))
        <div style="z-index: 999;color: red;margiin:10px auto; width: 200px">
        　　　　{{session('error')}}
        </div>
    @endif

    @if(!empty(session('success')))
        <div style="z-index: 999;color: darkgreen;margin:10px auto; width: 200px">
            {{session('success')}}
        </div>
    @endif

    <form action="" id="setting-form" method="post">
        <span style="margin-bottom: 10px">当前渠道：{{$channel->name}}</span>
        <div id="argument-blocks">
            <label for="" class="argument-block">
                <input type="text" placeholder="参数名称" id="argument-key" name="keys[]">
                <input type="text" placeholder="参数提示" id="argument-placeholder" name="placeholders[]">
                <input type="text" placeholder="默认为0，越大排序越前" id="argument-sort" name="sorts[]">
                <select name="status[]" id="argument-type">
                    <option value="1">有效</option>
                    <option value="0">无效</option>
                </select>
                <select name="types[]" id="argument-type">
                    <option value="0">文本类型</option>
                </select>
                <button id="delete-button">删除</button>
            </label>
        </div>

        <label id="argument-version">
            <span>sdk版本:</span>
            <select name="sdk_version_id" id="">
                @foreach($channel_sdk_versions as $sdkVersion)
                    @if($channel->sdkVersion->id == $sdkVersion->id)
                        <option value="{{$sdkVersion->id}}" selected>{{$sdkVersion->sdk_version}}</option>
                    @else
                        <option value="{{$sdkVersion->id}}">{{$sdkVersion->sdk_version}}</option>
                    @endif
                @endforeach
            </select>
        </label>

        <label for="" id="setting-button">
            <input type="hidden" name="post_channel_id" value="{{$channel->id}}">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <button id="add-button">添加新项</button>
            <input id="submit-button" type="submit">
        </label>
    </form>
</body>
</html>

<script>
    var deleteSettingInput = function (e) {
        e.preventDefault();
        this.parentNode.parentNode.removeChild(this.parentNode)
    }

    function bindDeleteButtonEvent() {
        let argumentBlocks = document.getElementsByClassName('argument-block');
        for (let i = 0; i < argumentBlocks.length; i++) {
            argumentBlocks[i].getElementsByTagName('button')[0].onclick = deleteSettingInput
        }
    }

    bindDeleteButtonEvent();

    document.getElementById('add-button').onclick = function (e) {
        let settingInputs = document.getElementById('argument-blocks');
        let firstSettingInput = settingInputs.getElementsByClassName('argument-block')[0];
        let settingInputHtml = firstSettingInput.innerHTML
        firstSettingInput.insertAdjacentHTML('afterend',  " <label for=\"\" class=\"argument-block\">\n" + settingInputHtml +"</label>")
        e.preventDefault();

        bindDeleteButtonEvent()
    }
</script>
