<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\User;
use Kirby\Cms\Users as KirbyUsers;
use Kirby\Cms\User as KirbyUser;

// FEATURE: public function add(Users|User|array $subscriber): Users;
// FEATURE: group()/groupBy()
// FEATURE: isEmpty()/isNotEmpty()
// FEATURE: remove()
// FEATURE: not()
// FEATURE: sort()

/**
 * Users Class
 * derived from object included in Steady API response
 *
 * see:
 * - https://developers.steadyhq.com/#subscriptions
 * - https://developers.steadyhq.com/#newsletter-subscribers
 *
 * @param array $data array of associative user array
 * @param array $data steady api response //TODO: decide on $data description
 * @method count(): returns int
 * @method factory(User[] $array)
 * @method find(string $id): returns ?User
 * @method filter(\Closure $filter): returns array of User objects
 * @method list(): returns array of User objects
 */
class Users implements \IteratorAggregate
{

	use hasItems, CountTrait, FindTrait, FactoryTrait, FilterTrait, toArrayTrait;

	/** @var User[] array of User objects */
	private array $items = [];

	/** @param array<array> $data array of User data arrays */
	public function __construct(
		array $data
	) {
		foreach ($data as $user) {
			$this->items[] = new User($user);
		};
	}

	/**
	 * Create Kirby $users from Users
	 * @param string $role Kirby $user role for newsletter subscribers
	 */
	public function createKirbyUsers(
		string $role = 'steady-subscriber'
	): KirbyUsers {
		$new_users = array_map(
			function (User $user) use ($role) {
				// See https://getkirby.com/docs/reference/objects/cms/users/factory
				$data = [
					'content'	=> [ // array	Sets the Content object
						'steady_id' => $user->id,
						'steady_opted_in_at' => $user->opted_in_at,
						'steady_type' => $user->type
					],
					'email' => $user->email,
					'language' => 'de',
					'name' => $user->email,
					'password' => null, //	string	Sets the user's password hash
					'role' => $role, //string	Sets the user role
				];
				try {
					$user = KirbyUser::create($data);
				} catch (\Exception $e) {
					echo $e;
				}
				return $user;
			},
			$this->list()
		);
		return new KirbyUsers($new_users);
	}

	public function sync() {
		$users = array_map(
			function (User $user) {
				// return user if it exists
				if ($u = kirby()->users()->findBy('steady_id', $user->id)) return $u;
				if ($u = kirby()->users()->findBy('email', $user->email)) return $u;
				// if no user found, create user with user info
				$data = [
					'content'	=> [ // array	Sets the Content object
						'steady_id' => $user->id,
						'steady_type' => $user->type,
						'steady_opted_in_at' => $user->opted_in_at ?? null,
					],
					'email' => $user->email,
					'language' => 'de',
					'name' => $user->email,
					'password' => null, //	string	Sets the user's password hash
					'role' => $user->type, //string	Sets the user role
				];
				// TODO: try/catch
				return KirbyUser::create($data);
			},
			$this->list()
		);
		return $users;
	}
}
