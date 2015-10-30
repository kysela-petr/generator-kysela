<?php

namespace Console;

/**
 * @author Petr Hlavac
 */
class UserPresenter extends BasePresenter {

	/** @var \Model\UserFacade @inject */
	public $userFacade;

	/** @var \Esports\Helper\PasswordHasher @inject */
	public $passwordHasher;

	public function actionAdd($username, $password, $role, $name, $surname) {
		if (!($username && $password)) {
			echo "Usage: -username=Username -password=Password -role=Role -name=Name -surname=Surname\n";
			$this->terminate();
		}

		if (!$username) {
			echo "Username cannot be empty\n";
			$this->terminate();
		}

		if (!$password) {
			echo "Password cannot be empty\n";
			$this->terminate();
		}

		if (!$role) {
			echo "Role cannot be empty\n";
			$this->terminate();
		}

		$this->runAndScream(function () use ($username, $password, $role, $name, $surname) {
			$this->createUser($username, $password, $role, $name, $surname);
		});
	}

	protected function createUser($username, $password, $role, $name, $surname) {
		try {
			$data = [
				'username' => $username,
				'password' => $this->passwordHasher->hash($password),
				'role' => $role,
				'name' => $name,
				'surname' => $surname
			];

			$user = $this->userFacade->add($data);
			$id = $user['id'];

			echo "User created with ID $id\n";
		} catch (\Esports\PrimaryKeyException $e) {
			echo "User $username already exists!\n";
		}
	}

}
