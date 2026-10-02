<?php

namespace Virtue\Access;

/**
 * Grants resources by scopes, e.g. the scopes of an OAuth client.
 *
 * A scope becomes a role of the identity, and each scope's resource is granted by that role:
 *
 *     $access = new GrantsAccess\RoleBased(new Identities\User($name, Scopes::asRoles($scopes)), Scopes::grants($all));
 *     $access->granted(Scopes::resource('read-catalog'));
 *
 * Identities\User counts its name as a role, so a scope used as a bare role would be granted to any identity
 * named like it. Scope roles therefore carry a prefix that names should not start with.
 */
class Scopes
{
    public const ROLE_PREFIX = 'scope:';

    public static function resource(string $scope): string
    {
        return 'scopes/' . $scope;
    }

    /**
     * @param string[] $scopes
     * @return string[]
     */
    public static function asRoles(array $scopes): array
    {
        return array_values(array_map(
            function (string $scope): string {
                return self::ROLE_PREFIX . $scope;
            },
            $scopes
        ));
    }

    /**
     * @param string[] $scopes
     * @return array<string, string[]>
     */
    public static function grants(array $scopes): array
    {
        $roles = [];
        foreach ($scopes as $scope) {
            $roles[self::resource($scope)] = self::asRoles([$scope]);
        }

        return $roles;
    }
}
