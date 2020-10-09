<?php
declare(strict_types=1);

namespace Ekvio\Integration\Invoker;

/**
 * Interface UserMatcher
 * @package Ekvio\Integration\Invoker
 */
interface UserMatcher
{
    /**
     * @param array $users
     * @return array
     */
    public function match(array $users): array;
}