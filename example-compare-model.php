<?php

class ControllerModule
{
	

	/**
	 * 9 lines
	 */
	public function doSomethingCurrently()
	{
		$access = $this->system->getAccess(); // all tests depend on access
		$userSearch = $access->getHandler('User');
		$ids = $userSearch->findUsernameExact('martin'); // would need to write a new method for multiple username search
		$userHandler = $access->getHandler('User');
		$users = $userHandler->load($ids);
		$keyedUsers = []; // transformation is repeated time after time
		foreach ($users as $user) {
			$keyedUsers[$user->username] = $user;
		}

		// the future
		// if namespace changes
		// find "User" and replace with "Thing" - problem!
		// find "->getHandler('User')" replace with ""->getHandler('Thing')"
		// but "Handler" has been renamed too so fiddly to sort out
		// but getting a relative namespace like this e.g "User" is essentially the same as "use \Vendor\Package\Handler" then "new Handler\User"
	}


	/**
	 * 4 lines
	 */
	public function doSomethingNew()
	{
		$handlerUser = new \Vendor\Package\Handler\User; // more testable than access way because it is a independent component
		$handlerUser->readColumn(['martin'], 'username'); // dynamic could be multiple usernames
		$handlerUser->keyDataByProperty('username'); // result is stored in data, this enables transformations
		$users = $handlerUser->getData();

		// the future
		// if namespace changes
		// find "\Vendor\Package\Handler\User" and replace with "\Vendor\Package\New\Thing"
	}
}
