<div class="form-group">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <input id="reset-callback-key-input-{{$id}}" class="form-control" type="text" value="{!! old($column, $value) !!}" name="{{$name}}">
        <button id="reset-callback-key-btn-{{$id}}" style="margin-top:5px">重置callback key值</button>

        @include('admin::form.help-block')

    </div>
</div>
