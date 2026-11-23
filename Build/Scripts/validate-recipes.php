<?php

declare(strict_types=1);

/**
 * Validates the AI recipes in Resources/Private/Ai/cozy-backend/recipes.
 *
 * Checks: required frontmatter keys, unique ids, id matches file name,
 * allowed mechanism and TYPO3 versions, and that every example path exists.
 *
 * Usage: php Build/Scripts/validate-recipes.php [recipe-dir]
 */

$extensionRoot = dirname(__DIR__, 2);
$recipeDir = $argv[1] ?? $extensionRoot . '/Resources/Private/Ai/cozy-backend/recipes';

const REQUIRED_KEYS = ['id', 'title', 'editor_need', 'mechanism', 'typo3', 'version_notes', 'examples', 'anti_pattern'];
const MECHANISMS = ['page-tsconfig', 'user-tsconfig', 'tca-override', 'site-set', 'event', 'typoscript', 'fluid', 'system-config'];
const TYPO3_VERSIONS = [13, 14];

/**
 * Minimal parser for the flat frontmatter used by the recipes:
 * "key: value", "key: [a, b]" and "key:" followed by "  - item" lines.
 */
function parseFrontmatter(string $content): ?array
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
            throw new RuntimeException('Cannot parse frontmatter line: ' . $line);
        }
        [$key, $value] = [$pair[1], trim($pair[2])];
        $currentList = null;
        if ($value === '') {
            $data[$key] = [];
            $currentList = $key;
        } elseif (preg_match('/^\[(.*)\]$/', $value, $inline)) {
            $data[$key] = array_map(static fn(string $v): string => trim($v, " \"'"), explode(',', $inline[1]));
        } else {
            $data[$key] = trim($value, "\"'");
        }
    }
    return $data;
}

$errors = [];
$ids = [];
$files = glob($recipeDir . '/*.md') ?: [];
$recipes = array_filter($files, static fn(string $f): bool => !str_starts_with(basename($f), '_'));

if ($recipes === []) {
    fwrite(STDERR, "No recipes found in $recipeDir\n");
    exit(1);
}

foreach ($recipes as $file) {
    $name = basename($file);
    try {
        $meta = parseFrontmatter((string)file_get_contents($file));
    } catch (RuntimeException $e) {
        $errors[] = "$name: " . $e->getMessage();
        continue;
    }
    if ($meta === null) {
        $errors[] = "$name: missing frontmatter";
        continue;
    }
    foreach (REQUIRED_KEYS as $key) {
        if (!isset($meta[$key]) || $meta[$key] === '' || $meta[$key] === []) {
            $errors[] = "$name: missing or empty '$key'";
        }
    }
    $id = $meta['id'] ?? '';
    if ($id !== '' && $id . '.md' !== $name) {
        $errors[] = "$name: id '$id' does not match file name";
    }
    if (isset($ids[$id])) {
        $errors[] = "$name: duplicate id '$id' (also in {$ids[$id]})";
    }
    $ids[$id] = $name;
    if (isset($meta['mechanism']) && !in_array($meta['mechanism'], MECHANISMS, true)) {
        $errors[] = "$name: unknown mechanism '{$meta['mechanism']}'";
    }
    foreach ((array)($meta['typo3'] ?? []) as $version) {
        if (!in_array((int)$version, TYPO3_VERSIONS, true)) {
            $errors[] = "$name: unsupported TYPO3 version '$version'";
        }
    }
    foreach ((array)($meta['examples'] ?? []) as $path) {
        if (!file_exists($extensionRoot . '/' . $path)) {
            $errors[] = "$name: example path does not exist: $path";
        }
    }
}

// Every recipe should be reachable from the decision tree
$tree = @file_get_contents($recipeDir . '/_decision-tree.md');
if ($tree === false) {
    $errors[] = '_decision-tree.md is missing';
} else {
    foreach (array_keys($ids) as $id) {
        if ($id !== '' && !str_contains($tree, '`' . $id . '`')) {
            $errors[] = "_decision-tree.md: recipe '$id' is not referenced";
        }
    }
    preg_match_all('/`([a-z0-9-]+)`/', $tree, $refs);
    foreach (array_unique($refs[1]) as $ref) {
        if (!isset($ids[$ref])) {
            $errors[] = "_decision-tree.md: references unknown recipe '$ref'";
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, implode("\n", $errors) . "\n");
    exit(1);
}
echo sprintf("%d recipes OK\n", count($recipes));
