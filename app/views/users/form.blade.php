        <div class="form-group">
            <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
            {{ Form::text('username', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
            {{ Form::email('email', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
            {{ Form::password('password', ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            {{ Form::select('role', array('User' => 'User', 'Admin' => 'Admin'), null, ['class' => 'form-control']) }}
        </div>