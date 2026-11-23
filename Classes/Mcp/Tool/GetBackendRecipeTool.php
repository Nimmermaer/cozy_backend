<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Mcp\Tool;

use Hn\McpServer\MCP\Tool\AbstractTool;
use Mblunck\CozyBackend\Ai\RecipeRepository;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;

/**
 * Developer tool for hn/typo3-mcp-server: serves the core-first recipes of
 * EXT:cozy_backend, so an assistant checks TYPO3 configuration before
 * writing custom code. Registered only if mcp_server is installed and the
 * application runs in Development context (see Configuration/Services.php).
 */
final class GetBackendRecipeTool extends AbstractTool
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository
    ) {
    }

    public function getSchema(): array
    {
        return [
            'description' => 'For developers and integrators who CONFIGURE the TYPO3 backend (not for editing content). '
                . 'Returns core-first recipes: how to solve an editor need with TSconfig, TCA overrides, site set '
                . 'settings or events instead of custom code. Call without "id" to get the decision tree and the '
                . 'list of recipes, then call again with the id of the matching recipe.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'id' => [
                        'type' => 'string',
                        'description' => 'Recipe id, e.g. "tceform-hide-rename". Omit to list all recipes.',
                    ],
                ],
                'required' => [],
            ],
            'annotations' => [
                'readOnlyHint' => true,
                'idempotentHint' => true,
            ],
        ];
    }

    protected function doExecute(array $params): CallToolResult
    {
        $id = trim((string)($params['id'] ?? ''));
        if ($id === '') {
            return $this->text($this->renderOverview());
        }

        $recipe = $this->recipeRepository->findById($id);
        if ($recipe === null) {
            return $this->createErrorResult(
                'Unknown recipe "' . $id . '". Call GetBackendRecipe without "id" to list all recipes.'
            );
        }
        return $this->text($recipe);
    }

    private function renderOverview(): string
    {
        $lines = [(string)$this->recipeRepository->getDecisionTree(), '', '# Recipes', ''];
        foreach ($this->recipeRepository->findAll() as $meta) {
            $versions = implode(', ', (array)($meta['typo3'] ?? []));
            $lines[] = sprintf(
                '- %s: %s [%s, TYPO3 %s] – %s',
                $meta['id'] ?? '?',
                $meta['title'] ?? '',
                $meta['mechanism'] ?? '',
                $versions,
                $meta['editor_need'] ?? ''
            );
        }
        return implode("\n", $lines);
    }

    private function text(string $text): CallToolResult
    {
        return new CallToolResult([new TextContent($text)]);
    }
}
