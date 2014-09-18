<?php



/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends Controller
{

	public function index(){
		$users = User::all();
		return View::make('users.index', compact('users'));
	}

	/**
	 * Displays the form for account creation
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function create()
	{
		return View::make('users.signup');
	}

	/**
	 * Stores new account
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function store()
	{
		$repo = App::make('UserRepository');
		$user = $repo->signup(Input::all());

		if ($user->id) {
			if (Config::get('confide::signup_email')) {
				Mail::queueOn(
					Config::get('confide::email_queue'),
					Config::get('confide::email_account_confirmation'),
					compact('user'),
					function ($message) use ($user) {
						$message
							->to($user->email, $user->username)
							->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
					}
				);
			}

			return Redirect::action('UsersController@index')
				->with('notice', Lang::get('confide::confide.alerts.account_created'));
		} else {
			$error = $user->errors()->all(':message');

			return Redirect::action('UsersController@create')
				->withInput(Input::except('password'))
				->with('error', $error);
		}
	}

	/**
	 * Displays the login form
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function login()
	{
		if (Confide::user()) {
			return Redirect::to('/');
		} else {
			return View::make('users.login');
			// return View::make(Config::get('confide::login_form'));
		}
	}

	/**
	 * Attempt to do login
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function doLogin()
	{
		$repo = App::make('UserRepository');
		$input = Input::all();

		if ($repo->login($input)) {
			return Redirect::intended('/');
		} else {
			if ($repo->isThrottled($input)) {
				$err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
			} elseif ($repo->existsButNotConfirmed($input)) {
				$err_msg = Lang::get('confide::confide.alerts.not_confirmed');
			} else {
				$err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
			}

			return Redirect::action('UsersController@login')
				->withInput(Input::except('password'))
				->with('error', $err_msg);
		}
	}

	/**
	 * Attempt to confirm account with code
	 *
	 * @param  string $code
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function confirm($code)
	{
		if (Confide::confirm($code)) {
			$notice_msg = Lang::get('confide::confide.alerts.confirmation');
			return Redirect::action('UsersController@login')
				->with('notice', $notice_msg);
		} else {
			$error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
			return Redirect::action('UsersController@login')
				->with('error', $error_msg);
		}
	}

	/**
	 * Displays the forgot password form
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function forgotPassword()
	{
		return View::make('users.forgot_password');
		// return View::make(Config::get('confide::forgot_password_form'));
	}

	/**
	 * Attempt to send change password link to the given email
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function doForgotPassword()
	{
		if (Confide::forgotPassword(Input::get('email'))) {
			$notice_msg = Lang::get('confide::confide.alerts.password_forgot');
			return Redirect::action('UsersController@login')
				->with('notice', $notice_msg);
		} else {
			$error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
			return Redirect::action('UsersController@doForgotPassword')
				->withInput()
				->with('error', $error_msg);
		}
	}

	/**
	 * Shows the change password form with the given token
	 *
	 * @param  string $token
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function resetPassword($token)
	{
		return View::make(Config::get('confide::reset_password_form'))
				->with('token', $token);
	}

	/**
	 * Attempt change password of the user
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function doResetPassword()
	{
		$repo = App::make('UserRepository');
		$input = array(
			'token'                 =>Input::get('token'),
			'password'              =>Input::get('password'),
			'password_confirmation' =>Input::get('password_confirmation'),
		);

		// By passing an array with the token, password and confirmation
		if ($repo->resetPassword($input)) {
			$notice_msg = Lang::get('confide::confide.alerts.password_reset');
			return Redirect::action('UsersController@login')
				->with('notice', $notice_msg);
		} else {
			$error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
			return Redirect::action('UsersController@resetPassword', array('token'=>$input['token']))
				->withInput()
				->with('error', $error_msg);
		}
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return  Illuminate\Http\Response
	 */
	public function logout()
	{
		Confide::logout();

		return Redirect::to('/');
	}

	public function doEdit($user){
		if ($user->isValid()) {
			$oldUser = clone $user;
			$user->username = Input::get('username');
			$user->email = Input::get('email');
			$user->role = Input::get('role');
			$user->confirmed = Input::get('confirm');

			$password = Input::get('password');
			$passwordConfirmation = Input::get('password_confirmation');

			if($user->confirmed == null) {
				$user->confirmed = $oldUser->confirmed;
			}

			// Save if valid. Password field will be hashed before save
			$user->save();

			// Save roles. Handles updating.
			$user->roles()->attach(Input::get('roles'));
		} else {
			return Redirect::to('users/edit/' . $user->id )->with('error', Lang::get('admin/users/messages.edit.error'));
		}

		// Get validation errors (see Ardent package)
		$error = $user->errors()->all();

		if(empty($error)) {
			return Redirect::to('users')->with('success', 'The user has been updated successfully.');
		} else {
			return Redirect::to('users/edit/' . $user->id )->with('error', 'There is an error updating this user.');
		}
	}
}
