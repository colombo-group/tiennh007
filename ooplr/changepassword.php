<?php
	require_once('core/init.php');

	$user = new User();

	if(!$user->isLoggedIn()) {
		Redirect::to('index.php');
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))){

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'currentPassword' => array(
					'required' => true,
					'min' => 6
					),
				'newPassword' => array(
					'required' => true,
					'min' => 6
					),
				'newPasswordAgain' => array(
					'required' => true,
					'min' => 6,
					'matches' => 'newPassword'
					)
				));
			if($validation->passed()) {
				if(Hash::make(Input::get('currentPassword'), $user->data()->salt) !== $user->data()->password) {
					echo 'Your current password is wrong.';
				} else {
					$salt = Hash::salt(32);
					$user->update(array(
						'password' => Hash::make(Input::get('newPassword'), $salt),
						'salt' => $salt
						));
					Session::flash('home', 'Your password has been changed!');
					Redirect::to('index.php');
				}
			} else {
				foreach($validation->errors() as $error) {
					echo $error, '<br>';
				}
			}
		}
	}
?>

<form action="" method="post">
	<div class="field">
		<label for="currentPassword">Your existing Password</label>
		<input type="password" name="currentPassword" id="currentPassword"/>
	</div>
	<div class="field">
		<label for="newPassword">New Password</label>
		<input type="password" name="newPassword" id="newPassword"/>
	</div>
	<div class="field">
		<label for="newPasswordAgain">Enter your new password again</label>
		<input type="password" name="newPasswordAgain" id="newPasswordAgain"/>
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
	<input type="submit" value="Update"/>
</form>