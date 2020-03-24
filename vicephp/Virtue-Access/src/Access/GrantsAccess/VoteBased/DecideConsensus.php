<?php

namespace Virtue\Access\GrantsAccess\VoteBased;

use Virtue\Access;

/**
 * Grants access if there is consensus of granted against denied responses.
 */
class DecideConsensus implements Access\GrantsAccess
{
    /** @var iterable|Access\GrantsAccess[] */
    private $voters;

    public function __construct(iterable $voters = [])
    {
        $this->voters = $voters;
    }

    public function granted(string $resource): bool
    {
        $votes = 0;
        foreach ($this->voters as $voter) {
            $result = $voter->granted($resource);
            switch ($result) {
                case true:
                    $votes++;
                    break;
                case false:
                    $votes--;
                    break;
            }
        }

        return $votes >= 0;
    }
}
