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
class Users
{

	/** @var User[] array of User objects */
	private array $users = [];

	public function __construct(
		array $data
	) {
		foreach ($data as $user) {
			$this->users[] = new User($user);
		};
	}

	/**
	 * Returns total User objects
	 */
	public function count(): int {
		return count($this->list());
	}

	/**
	 * Filters Users by custom $filter Closure
	 * @param \Closure $filter custom filter function
	 * @return Users returns new and filtered Users object
	 */
	public function filter(\Closure $filter): Users {
		$filtered_users =  array_filter(
			$this->list(),
			$filter
		);
		return self::factory($filtered_users);
	}

	/**
	 * Create Users Object from array of User Objects
	 * @param User[] $array Array of User Objects
	 * @return Users $users New Users Object
	 */
	public static function factory(array $array): Users {
		$users = new Users([]);
		$users->users = $array;
		return $users;
	}

	/**
	 * Find User by $id
	 * @param string $id Steady user id
	 * @return ?User returns User or null
	 */
	public function find(string $id): ?User {
		return array_reduce(
			$this->list(),
			function (
				?User $carry,
				?User $user
			) use ($id) {
				return $carry ?? ($user->id === $id ? $user : $carry);
			},
			null
		);
	}

	/**
	 * Returns array of User objects
	 * @return User[]
	 */
	public function list(): array {
		return $this->users;
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
