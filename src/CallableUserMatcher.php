<?php
declare(strict_types=1);

namespace Ekvio\Integration\Invoker;

/**
 * Class CallableUserMatcher
 * @package Ekvio\Integration\Invoker
 */
class CallableUserMatcher implements UserMatcher
{
    /**
     * @var callable
     */
    private $matcher;

    /**
     * CallableUserMatcher constructor.
     * @param callable $matcher
     */
    public function __construct(callable $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @param array $users
     * @return array
     */
    public function match(array $users): array
    {
        return ($this->matcher)($users);
    }
}