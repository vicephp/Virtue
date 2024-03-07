<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $config): void {
    MBConfig::disableDefaultWorkers();

    $config->packageDirectories([
        __DIR__ . '/vicephp',
    ]);

    $config->dataToAppend(
        [
            ComposerJsonSection::REQUIRE_DEV => [
                'phpunit/phpunit' => '^8.0 | ^9.0',
            ],
        ]
    );
};
