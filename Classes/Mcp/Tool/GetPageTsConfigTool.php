<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Mcp\Tool;

use Hn\McpServer\MCP\Tool\AbstractTool;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Type\Bitmask\Permission;

/**
 * Developer tool for hn/typo3-mcp-server: shows the effective page TSconfig
 * of a page (merged along the rootline, including site sets), so an assistant
 * sees what is already configured before adding TSconfig.
 * Admins only; registered only in Development context (see Configuration/Services.php).
 */
final class GetPageTsConfigTool extends AbstractTool
{
    private const MAX_LENGTH = 60000;

    public function getSchema(): array
    {
        return [
            'description' => 'For developers and integrators who CONFIGURE the TYPO3 backend (not for editing content). '
                . 'Returns the effective page TSconfig of a page as TSconfig text, e.g. to check existing TCEFORM, '
                . 'TCAdefaults or mod.wizards settings before adding new ones. Use "path" to limit the output. '
                . 'Admin users only.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'pageId' => [
                        'type' => 'integer',
                        'description' => 'Page uid whose effective TSconfig is returned.',
                    ],
                    'path' => [
                        'type' => 'string',
                        'description' => 'Optional dot path to limit the output, e.g. "TCEFORM.tt_content" or "mod.wizards".',
                    ],
                ],
                'required' => ['pageId'],
            ],
            'annotations' => [
                'readOnlyHint' => true,
                'idempotentHint' => true,
            ],
        ];
    }

    protected function doExecute(array $params): CallToolResult
    {
        $backendUser = $GLOBALS['BE_USER'] ?? null;
        if (!$backendUser instanceof BackendUserAuthentication || !$backendUser->isAdmin()) {
            return $this->createErrorResult('GetPageTsConfig is only available for admin users.');
        }

        $pageId = (int)($params['pageId'] ?? 0);
        if ($pageId <= 0) {
            return $this->createErrorResult('Parameter "pageId" must be a positive page uid.');
        }
        $page = BackendUtility::readPageAccess($pageId, $backendUser->getPagePermsClause(Permission::PAGE_SHOW));
        if (!is_array($page) || $page === []) {
            return $this->createErrorResult('Page ' . $pageId . ' does not exist or is not accessible.');
        }

        $tsConfig = BackendUtility::getPagesTSconfig($pageId);
        $path = trim((string)($params['path'] ?? ''), " .\t\n");
        $prefix = '';
        if ($path !== '') {
            foreach (explode('.', $path) as $segment) {
                if (!isset($tsConfig[$segment . '.']) || !is_array($tsConfig[$segment . '.'])) {
                    $value = $tsConfig[$segment] ?? null;
                    return $this->text($value === null
                        ? 'No TSconfig found at "' . $path . '" for page ' . $pageId . '.'
                        : $path . ' = ' . $value);
                }
                $tsConfig = $tsConfig[$segment . '.'];
            }
            $prefix = $path;
        }

        $text = $this->render($tsConfig, $prefix);
        if ($text === '') {
            $text = 'No TSconfig found' . ($path !== '' ? ' at "' . $path . '"' : '') . ' for page ' . $pageId . '.';
        }
        if (mb_strlen($text) > self::MAX_LENGTH) {
            $text = mb_substr($text, 0, self::MAX_LENGTH)
                . "\n# ... output truncated, use \"path\" to narrow it down.";
        }
        return $this->text('# Effective page TSconfig of page ' . $pageId . "\n" . $text);
    }

    /**
     * Renders a TypoScript array as flat "path = value" lines.
     */
    private function render(array $tsConfig, string $prefix): string
    {
        $lines = [];
        foreach ($tsConfig as $key => $value) {
            $key = (string)$key;
            if (is_array($value)) {
                $child = $this->render($value, $this->join($prefix, rtrim($key, '.')));
                if ($child !== '') {
                    $lines[] = $child;
                }
                continue;
            }
            $value = (string)$value;
            $path = $this->join($prefix, $key);
            if (str_contains($value, "\n")) {
                // Multi-line value notation
                $lines[] = $path . " (\n" . $value . "\n)";
            } else {
                $lines[] = $path . ($value === '' ? ' =' : ' = ' . $value);
            }
        }
        return implode("\n", $lines);
    }

    private function join(string $prefix, string $key): string
    {
        return $prefix === '' ? $key : $prefix . '.' . $key;
    }

    private function text(string $text): CallToolResult
    {
        return new CallToolResult([new TextContent($text)]);
    }
}
