<?php


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

    $this->call('UsersTableSeeder');
		$this->call('ProblemsTableSeeder');
	}

}

class UsersTableSeeder extends Seeder {

  public function run()
  {
    $user = new User;
    $user->username = 'vee';
    $user->email = 'vibhavee.t@gmail.com';
    $user->password = '1234';
    $user->role = 'Admin';
    $user->password_confirmation = '1234';
    $user->confirmation_code = md5(uniqid(mt_rand(), true));
    $user->confirmed = true;

    if(! $user->save()) {
      Log::info('Unable to create user '.$user->username, (array)$user->errors());
    } else {
      Log::info('Created user "'.$user->username.'" <'.$user->email.'>');
    }

    $user = new User;
    $user->username = 'test';
    $user->email = 'test@gmail.com';
    $user->password = '1234';
    $user->role = 'User';
    $user->password_confirmation = '1234';
    $user->confirmation_code = md5(uniqid(mt_rand(), true));
    $user->confirmed = true;

    if(! $user->save()) {
      Log::info('Unable to create user '.$user->username, (array)$user->errors());
    } else {
      Log::info('Created user "'.$user->username.'" <'.$user->email.'>');
    }
  }
}
class ProblemsTableSeeder extends Seeder {

  function run(){

    $problem = new Problem;
    $problem->station_code = 'POCG';
    $problem->type = 'RAIN_BD';
    $problem->start_datetime = date('Y-m-d 09:00');
    $problem->end_datetime = date('Y-m-d 10:10');
    $problem->num = 7;
    $problem->status = 'Undefined';

    if(! $problem->save()) {
      Log::info('Unable to create problem '.$problem->station_code, (array)$problem->errors());
    } else {
      Log::info('Created problem "'.$problem->station_code.'" <'.$problem->start_datetime.'>');
    }

    $problem = new Problem;
    $problem->station_code = 'DGSL';
    $problem->type = 'RAIN_BD';
    $problem->start_datetime = date('Y-m-d 19:00');
    $problem->end_datetime = date('Y-m-d 19:10');
    $problem->num = 1;
    $problem->status = 'Undefined';

    if(! $problem->save()) {
      Log::info('Unable to create problem '.$problem->station_code, (array)$problem->errors());
    } else {
      Log::info('Created problem "'.$problem->station_code.'" <'.$problem->start_datetime.'>');
    }

  }

}