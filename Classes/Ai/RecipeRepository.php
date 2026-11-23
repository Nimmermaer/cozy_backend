<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Ai;

/**
 * Reads the core-first recipes in Resources/Private/Ai/cozy-backend/recipes.
 *
 * Plain PHP without TYPO3 dependencies, so it can be used by the MCP tools
 * as well as by Build/Scripts/validate-recipes.php.
 */
final class RecipeRepository
{
    public const DECISION_TREE = '_decision-tree.md';

    private readonly string $recipeDirectory;

    public function __construct(?string $recipeDirectory = null)
    {
        $this->recipeDirectory = rtrim(
            $recipeDirectory ?? dirname(__DIR__, 2) . '/Resources/Private/Ai/cozy-backend/recipes',
            '/'
        );
    }

    public function getRecipeDirectory(): string
    {
        return $this->recipeDirectory;
    }

    /**
     * Frontmatter of all recipes, keyed by file name.
     *
     * @return array<string, array<string, string|list<string>>>
     */
    public function findAll(): array
    {
        $recipes = [];
        foreach ($this->getRecipeFiles() as $file) {
            $recipes[basename($file)] = self::parseFrontmatter((string)file_get_contents($file)) ?? [];
        }
        return $recipes;
    }

    /**
     * Full markdown of one recipe, null if the id is unknown.
     */
    public function findById(string $id): ?string
    {
        if (!preg_match('/^[a-z0-9-]+$/', $id)) {
            return null;
        }
        $file = $this->recipeDirectory . '/' . $id . '.md';
        return is_file($file) ? (string)file_get_contents($file) : null;
    }

    public function getDecisionTree(): ?string
    {
        $file = $this->recipeDirectory . '/' . self::DECISION_TREE;
        return is_file($file) ? (string)file_get_contents($file) : null;
    }

    /**
     * @return list<string>
     */
    public function getRecipeFiles(): array
    {
        $files = glob($this->recipeDirectory . '/*.md') ?: [];
        return array_values(array_filter(
            $files,
            static fn(string $file): bool => !str_starts_with(basename($file), '_')
        ));
    }

    /**
     * Minimal parser for the flat frontmatter used by the recipes:
     * "key: value", "key: [a, b]" and "key:" followed by "  - item" lines.
     *
     * @return array<string, string|list<string>>|null null if there is no frontmatter
     * @throws \RuntimeException on lines that cannot be parsed
     */
    public static function parseFrontmatter(string $content): ?array
    {
        if (!preg_match('/\A---\n(.*?)\n---\n/s', $content, $match)) {
            return null;
        }
        $data = [];
        $currentList = null;
        foreach (explode("\n", $match[1]) as $line) {
            if (trim($line) === '') {
                continue;
            }
            if ($currentList !== null && preg_match('/^\s+-\s+(.*)$/', $line, $item)) {
                $data[$currentList][] = trim($item[1], " \"'");
                continue;
            }
            if (!preg_match('/^([a-z0-9_]+):\s*(.*)$/', $line, $pair)) {
                throw new \RuntimeException('Cannot parse frontmatter line: ' . $line, 1763990001);
            }
            [$key, $value] = [$pair[1], trim($pair[2])];
            $currentList = null;
            if ($value === '') {
                $data[$key] = [];
                $currentList = $key;
            } elseif (preg_match('/^\[(.*)\]$/', $value, $inline)) {
                $data[$key] = array_map(
                    static fn(string $v): string => trim($v, " \"'"),
                    explode(',', $inline[1])
                );
            } else {
                $data[$key] = trim($value, "\"'");
            }
        }
        return $data;
    }
}
