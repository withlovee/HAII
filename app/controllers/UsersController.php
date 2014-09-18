<?php



/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends Controller
{

	public function index(){
		$users = User::orderBy('id', 'ASC')->get();
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
			// if (Config::get('confide::signup_email')) {
			// 	Mail::queueOn(
			// 		Config::get('confide::email_queue'),
			// 		Config::get('confide::email_account_confirmation'),
			// 		compact('user'),
			// 		function ($message) use ($user) {
			// 			$message
			// 				->to($user->email, $user->username)
			// 				->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
			// 		}
			// 	);
			// }

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
		return View::make('users.reset_password')
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
	public function logout(){
		Confide::logout();

		return Redirect::to('/');
	}

	public function edit($id){
		$user = User::find($id);
		return View::make('users.edit', compact('user'));		
	}

	public function update($id){
		$repo = App::make('UserRepository');
		$user = $repo->update($id, Input::all());
		$error = $user->errors()->all();
		if(empty($error)) {
			return Redirect::action('UsersController@index')
				->with('notice', 'The user has been updated successfully.');
		} else {
			return Redirect::action('UsersController@edit', array($user->id))
				->withInput(Input::except('password'))
				->with('error', $error);
		}
	}

	public function destroy($id){
		User::destroy($id);
		return Redirect::action('UsersController@index')
			->with('notice', 'The user has been deleted successfully.');
	}

	public function profile(){
		$user = Auth::user();
		return View::make('users.profile', compact('user'));			
	}

	public function doProfile(){
		$repo = App::make('UserRepository');
		$user = $repo->profile(Input::all());
		$error = $user->errors()->all();
		if(empty($error)) {
			return Redirect::action('UsersController@profile')
				->with('notice', 'The user has been updated successfully.');
		} else {
			return Redirect::action('UsersController@profile', array($user->id))
				->withInput(Input::except('password'))
				->with('error', $error);
		}
	}
}
