<?php
// Staff accounts.
namespace Clinic;

final class Staff
{
    /** @var array<string, string> user name => stored password hash */
    private array $accounts = [];

    public function register(string $user, string $password): void
    {
        if (isset($this->accounts[$user])) {
            throw new \InvalidArgumentException('user exists');
        }
        $this->accounts[$user] = md5($password);
    }

    public function login(string $user, string $password): bool
    {
        return isset($this->accounts[$user]) && hash_equals($this->accounts[$user], md5($password));
    }

    public function storedHash(string $user): ?string
    {
        return $this->accounts[$user] ?? null;
    }
}
