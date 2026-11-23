---
id: ai-ready-backend
title: A clean editor backend is a clean AI interface (hn/typo3-mcp-server)
editor_need: "Editors want an AI assistant to create and change content – safely and within the same rules as themselves."
mechanism: system-config
typo3: [13, 14]
version_notes: "hn/typo3-mcp-server supports TYPO3 ^13.4 and ^14.3 and needs EXT:workspaces. Its stdio command (mcp:server) is deprecated and runs as admin; use the HTTP endpoint with OAuth, which runs as the logged-in backend user. stefanfroemken/changelog-mcp requires TYPO3 ^14.0 and EXT:reactions."
examples:
  - Configuration/TsConfig/Page/TCEFORM.tsconfig
  - Configuration/TsConfig/Page/TCAdefaults.tsconfig
  - Configuration/TsConfig/User/options.tsconfig
  - Classes/Mcp/Tool/GetBackendRecipeTool.php
  - Classes/Mcp/Tool/GetPageTsConfigTool.php
anti_pattern: "Letting the AI work as admin; writing editorial rules into prompts instead of TSconfig and permissions; building an own MCP server for content editing."
---
## When
An AI assistant should edit content through MCP. Use hn/typo3-mcp-server instead of building an own
server: it writes every change into a workspace and works with the rights of a backend user.

## What the MCP server takes over from your backend configuration
Verified in its source code (`Classes/Service/*`):
- **TCEFORM `disabled`** – hidden fields are not offered to the AI (`TableAccessService`).
- **`addItems` / `removeItems` / `keepItems`** and itemsProcFunc – select options come from the
  FormDataCompiler, like in the backend form (`SelectItemResolver`).
- **`TCAdefaults`** (page and user TSconfig, also per type) – new records start with the same values as
  in the backend form (`NewRecordInitializer`).
- **Permissions** – DB mounts, table and field (`exclude`) permissions of the backend user apply.
- **Workspaces** – changes are never written live; a human reviews and publishes.
- **Backend layouts** – the page tool shows the columns of the backend layout.

So every recipe in this collection also narrows down what the AI can do wrong.

## Solution
1. Clean up the backend for editors first (`tceform-hide-rename`, `tcadefaults-prefill`,
   `wizard-presets`, `backend-layouts`).
2. Create a dedicated backend user/group for AI work with the **minimal** rights an editor needs –
   no admin.
3. Limit tables in the extension configuration of `mcp_server`: `allowedTables` (allow list) and
   `excludedTables` (e.g. tables with personal data). `excludedTables` wins.
4. Configure `TCEMAIN.preview.<table>` for record types like news so the AI can show frontend URLs.
5. Give fields understandable labels and descriptions (XLF). The AI reads the same schema as editors –
   that clear labels improve its results is plausible but not measured here.

## Three layers, three MCP servers
| Layer | Project | Answers |
|---|---|---|
| Content | hn/typo3-mcp-server | What is in this instance? Change content (in workspaces). |
| Version knowledge | stefanfroemken/changelog-mcp | What changed in the core between versions? (`search_changelogs`) |
| Patterns | EXT:cozy_backend | How do I solve an editor need with core configuration? |

changelog-mcp imports the changelog files from `vendor/typo3/cms-core/Documentation/Changelog` and
needs TYPO3 14. For v13 projects, run it in a separate v14 instance – its changelogs also contain all
v13 entries.

## Developer tools from EXT:cozy_backend
If `mcp_server` is installed and TYPO3 runs in **Development** context, cozy_backend adds two read-only tools:
- `GetBackendRecipe(id?)` – this recipe collection (decision tree, single recipes).
- `GetPageTsConfig(pageId, path?)` – effective page TSconfig of a page (admins only).

Test them without an MCP client:
```
vendor/bin/typo3 mcp:test GetBackendRecipe '{"id":"ai-ready-backend"}'
vendor/bin/typo3 mcp:test GetPageTsConfig '{"pageId":1,"path":"TCEFORM.tt_content"}'
```

## Pitfalls
- Every registered tool is visible to the AI of every MCP user. Keep developer tools out of production.
- Files are not workspace-versioned: uploads land in storage immediately (only their references are staged).
- Workspace preview links (`workspacePreviewLinks`) show all unpublished changes to anyone with the link.

## References
- https://github.com/hauptsacheNet/typo3-mcp-server
- https://github.com/froemken/changelog-mcp
