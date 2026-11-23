<?php

declare(strict_types=1);

use Hn\McpServer\MCP\Tool\ToolInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Core\Environment;

/**
 * Registers the developer tools for hn/typo3-mcp-server.
 *
 * - Only if EXT:mcp_server is installed (the tools implement its ToolInterface,
 *   which then tags them as "mcp.tool" automatically).
 * - Only in Development context: the tools target developers, and every tool
 *   is visible to the AI of every MCP user, editors included.
 *
 * Services.yaml excludes Classes/Mcp/ so nothing breaks without mcp_server.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    if (!interface_exists(ToolInterface::class) || !Environment::getContext()->isDevelopment()) {
        return;
    }

    $containerConfigurator->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
        ->load('Mblunck\\CozyBackend\\Mcp\\', __DIR__ . '/../Classes/Mcp/');
};
