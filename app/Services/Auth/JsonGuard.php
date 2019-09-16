<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 14.02.19
 * Time: 17:10.
 */

namespace App\Services\Auth;

use App\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Traits\Macroable;

class JsonGuard implements Guard
{
    use GuardHelpers, Macroable;

    public const HEADER_NAME_TOKEN = 'X-TOKEN-AUTH';
    public const COLUMN_NAME = 'access_token';

    /**
     * The name of the Guard. Typically "session".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    protected $name;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    protected $user;

    /** @var string */
    protected $storageKey;

    /**
     * Create a new authentication guard.
     *
     * @param string       $name
     * @param UserProvider $provider
     * @param Request      $request
     */
    public function __construct($name, $provider, Request $request = null)
    {
        $this->name = $name;
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return !is_null($this->user());
    }

    /**
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * @return Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->retrieveByCredentials(
                [self::COLUMN_NAME => $token]
            );
        }

        return $this->user = $user;
    }

    /**
     * @return int|mixed|null
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    private function getTokenForRequest(): ?string
    {
        $token = (string) $this->request->header(self::HEADER_NAME_TOKEN);

        if (empty($token)) {
            $token = $this->request->cookies->get(self::HEADER_NAME_TOKEN);
        }

        return is_bool($token) ? null : $token;
    }

    public function login(User $user)
    {
        Cookie::queue(self::HEADER_NAME_TOKEN, $user->access_token, 60 * 60 * 60);
    }

    public function logout()
    {
        $user = $this->user();
        if (null !== $user) {
            $this->request->cookies->remove(self::HEADER_NAME_TOKEN);

            $user->access_token = null;
            $user->save();
            $this->request->session()->invalidate();
        }
    }
}
