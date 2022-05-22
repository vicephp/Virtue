<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    // where are the packages located?
    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/vicephp',
    ]);
    // for "merge" command
    $parameters->set(Option::DATA_TO_APPEND, [
        'require-dev' => [
            'phpunit/phpunit' => '^8.0 | ^9.0',
        ],
    ]);
    // split
    $parameters->set(Option::DIRECTORIES_TO_REPOSITORIES, [
        __DIR__ . '/vicephp/Virtue-Access' => 'git@github.com:vicephp/Virtue-Access.git',
        __DIR__ . '/vicephp/Virtue-Api' => 'git@github.com:vicephp/Virtue-Api.git',
        __DIR__ . '/vicephp/Virtue-DataTables' => 'git@github.com:vicephp/Virtue-DataTables.git',
        __DIR__ . '/vicephp/Virtue-Forms' => 'git@github.com:vicephp/Virtue-Forms.git',
        __DIR__ . '/vicephp/Virtue-JWT' => 'git@github.com:vicephp/Virtue-JWT.git',
        __DIR__ . '/vicephp/Virtue-PDO' => 'git@github.com:vicephp/Virtue-PDO.git',
        __DIR__ . '/vicephp/Virtue-View' => 'git@github.com:vicephp/Virtue-View.git',
        __DIR__ . '/vicephp/Virtue-Http' => 'git@github.com:vicephp/Virtue-Http.git',
    ]);

    $services = $containerConfigurator->services();

    // release workers - in order to execute
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker::class);
//    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker::class);
//    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker::class);
//    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker::class);
//    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker::class);
};
