<div class="form-group">
    <label for="username" class="col-sm-2 control-label">{{{ Lang::get('confide::confide.username') }}}</label>
    <div class="col-sm-3">
        {{ Form::text('username', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for="email" class="col-sm-2 control-label">{{{ Lang::get('confide::confide.e_mail') }}}</label>
    <div class="col-sm-3">
        {{ Form::email('email', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for="password" class="col-sm-2 control-label">{{{ Lang::get('confide::confide.password') }}}</label>
    <div class="col-sm-3">
        {{ Form::password('password', ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label for="password_confirmation" class="col-sm-2 control-label">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
    <div class="col-sm-3">
        {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Notifications</label>
    <div class="col-sm-10">
        <div class="checkbox">
            <label>
                {{ Form::checkbox('report_instantly') }} รับรายงานทันทีที่เกิดปัญหา Out-of-Range Value
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                {{ Form::checkbox('report_daily') }} รับรายงานประจำวัน
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                {{ Form::checkbox('report_monthly') }} รับรายงานประจำเดือน
            </label>
        </div>
    </div>
</div>