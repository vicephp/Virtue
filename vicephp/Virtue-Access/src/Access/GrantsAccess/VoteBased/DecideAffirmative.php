<?php

namespace Virtue\Access\GrantsAccess\VoteBased;

use Virtue\Access;

/**
 * Grants access if any voter returns an affirmative response.
 */
class DecideAffirmative implements Access\GrantsAccess
{
    /** @var iterable|Access\GrantsAccess[] */
    private $voters;

    public function __construct(iterable $voters = [])
    {
        $this->voters = $voters;
    }

    public function granted(string $resource): bool
    {
        foreach ($this->voters as $voter) {
            if($voter->granted($resource)) {
                return true;
            }
        }

        return false;
    }
}
