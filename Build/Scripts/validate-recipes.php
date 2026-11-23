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

use Mblunck\CozyBackend\Ai\RecipeRepository;

$extensionRoot = dirname(__DIR__, 2);
// Loaded directly so the script also works without Composer autoloading
require_once $extensionRoot . '/Classes/Ai/RecipeRepository.php';

$repository = new RecipeRepository($argv[1] ?? null);
$recipeDir = $repository->getRecipeDirectory();

const REQUIRED_KEYS = ['id', 'title', 'editor_need', 'mechanism', 'typo3', 'version_notes', 'examples', 'anti_pattern'];
const MECHANISMS = ['page-tsconfig', 'user-tsconfig', 'tca-override', 'site-set', 'event', 'typoscript', 'fluid', 'system-config'];
const TYPO3_VERSIONS = [13, 14];

$errors = [];
$ids = [];
$recipes = $repository->getRecipeFiles();

if ($recipes === []) {
    fwrite(STDERR, "No recipes found in $recipeDir\n");
    exit(1);
}

foreach ($recipes as $file) {
    $name = basename($file);
    try {
        $meta = RecipeRepository::parseFrontmatter((string)file_get_contents($file));
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
$tree = $repository->getDecisionTree();
if ($tree === null) {
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
