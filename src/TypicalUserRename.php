<?php
declare(strict_types=1);

namespace Ekvio\Integration\Invoker;

use Ekvio\Integration\Contracts\Extractor;
use Ekvio\Integration\Contracts\Invoker;
use Ekvio\Integration\Contracts\Profiler;
use Ekvio\Integration\Sdk\V2\User\User;
use RuntimeException;

/**
 * Class TypicalUserRename
 * @package Ekvio\Integration\Invoker
 */
class TypicalUserRename implements Invoker
{
    private const NAME = 'User rename invoker';
    /**
     * @var Extractor
     */
    private $extractor;
    /**
     * @var UserMatcher
     */
    private $userMatcher;
    /**
     * @var User
     */
    private $userApi;
    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * TypicalUserRename constructor.
     * @param Extractor $extractor
     * @param UserMatcher $userMatcher
     * @param User $userApi
     * @param Profiler $profiler
     */
    public function __construct(Extractor $extractor, UserMatcher $userMatcher, User $userApi, Profiler $profiler)
    {
        $this->extractor = $extractor;
        $this->userMatcher = $userMatcher;
        $this->userApi = $userApi;
        $this->profiler = $profiler;
    }

    /**
     * @param array $arguments
     */
    public function __invoke(array $arguments = [])
    {
        $this->profiler->profile('Extract users...');
        $users = $this->extractor->extract();
        $this->profiler->profile(sprintf('Extracted %s users....', count($users)));

        $logins = $this->userMatcher->match($users);
        $this->profiler->profile(sprintf('Matched to %s renames...', count($logins)));

        if(!$logins) {
            $this->profiler->profile('Logins for rename not found...');
        }

        $this->profiler->profile(sprintf('Begin renaming %s accounts...', count($logins)));
        $response = $this->userApi->rename($logins);
        if($response) {
            $this->profiler->profile(sprintf('Error in renaming: %s', json_encode($response)));
            throw new RuntimeException(sprintf('Error in renaming process for %s accounts', count($response)));
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return self::NAME;
    }
}