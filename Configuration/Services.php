<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Translation\Command\XliffLintCommand;
use Symfony\Component\Yaml\Command\LintCommand;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    if (class_exists(XliffLintCommand::class)) {
        $services->set(XliffLintCommand::class)
            ->public()
            ->tag('console.command', [
                'command' => 'lint:xliff',
                'description' => 'Lints language files ',
            ]);
    }
    if (class_exists(LintCommand::class)) {
        $services->set(LintCommand::class)
            ->public()
            ->tag('console.command', [
                'command' => 'lint:yaml',
                'description' => 'Lints a file and outputs encountered errors',
            ]);
    }
};
