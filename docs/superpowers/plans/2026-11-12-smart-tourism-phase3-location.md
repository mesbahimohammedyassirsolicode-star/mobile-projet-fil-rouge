# Smart Tourism Phase 3 Location Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Move the completed Smart Tourism implementation into `phase3/` without disturbing legacy sessions or unrelated root files.

**Architecture:** Preserve the existing PHP architecture exactly while changing its project root from the repository root to `phase3/`. Use Git moves for history preservation, then update only path-sensitive documentation and verification commands.

**Tech Stack:** PHP 8, PDO, MySQL, Git, PowerShell.

**Spec:** `docs/superpowers/specs/2026-11-12-smart-tourism-phase3-location-design.md`

## Global Constraints

- Move only the Smart Tourism implementation into `phase3/`.
- Leave `session1/`, `session2/`, `db.sql`, diagrams, `.vscode/`, and other unrelated root changes untouched.
- Do not duplicate the implementation at the repository root.
- Preserve the existing schema, PHP behavior, namespaces, and test behavior.
- Keep the new `phase3/` project runnable with commands issued from `phase3/`.

## Review Focus

- Every moved internal relative path must still resolve from `phase3/`; verify by running all four tests from `phase3/`.
- Legacy session directories must remain in the repository root; verify with a path-presence check.
- Unrelated pre-existing root files must not be moved or deleted; verify `git status` and root listing.
- The database schema must remain byte-identical after moving; verify a hash comparison.
- No duplicate Smart Tourism source tree may remain at the root; verify expected root paths are absent.

---

### Task 1: Relocate Smart Tourism project

**Files:**
- Move: `app/` -> `phase3/app/`
- Move: `public/` -> `phase3/public/`
- Move: `views/` -> `phase3/views/`
- Move: `database/` -> `phase3/database/`
- Move: `tests/` -> `phase3/tests/`
- Move: `bootstrap.php` -> `phase3/bootstrap.php`
- Move: `.env.example` -> `phase3/.env.example`
- Keep: `.gitignore` at the repository root for repository-wide exclusions
- Move: `README.md` -> `phase3/README.md`
- Move: `docs/superpowers/plans/2026-11-12-smart-tourism-phase-1.md` -> `phase3/docs/superpowers/plans/2026-11-12-smart-tourism-phase-1.md`
- Move: `docs/superpowers/specs/2026-11-12-smart-tourism-phase-1-design.md` -> `phase3/docs/superpowers/specs/2026-11-12-smart-tourism-phase-1-design.md`
- Modify: `phase3/README.md` only if its commands or paths need adjustment after the move

**Interfaces:**
- Consumes the existing root Smart Tourism files and their relative paths.
- Produces a self-contained `phase3/` project whose `bootstrap.php` and test scripts work unchanged or with minimal documented path updates.

- [ ] **Step 1: Record the relocation verification commands**: capture the source/schema hash, assert `session1/` and `session2/` exist, and assert the expected root Smart Tourism paths exist before moving.
- [ ] **Step 2: Move the listed files with Git-aware moves**, preserving file history and leaving unrelated root files untouched.
- [ ] **Step 3: Update only path-sensitive documentation** so `phase3/README.md` and the moved planning documents refer to the new project root where necessary.
- [ ] **Step 4: Run from `phase3/`**:
  `php tests/configuration_test.php`, `php tests/base_dao_test.php`, `php tests/base_service_test.php`, `php tests/category_integration_test.php`, PHP syntax checks, and `git diff --check`; expected result is all pass.
- [ ] **Step 5: Verify the relocation** with root/path-presence checks and a SHA-256 comparison of `phase3/database/smart_tourism.sql` against the pre-move schema; expected result is legacy/unrelated files remain and no root duplicate Smart Tourism tree remains.
- [ ] **Step 6: Commit**:

```text
chore: move Smart Tourism project into phase3

Co-authored-by: Copilot <223556219+Copilot@users.noreply.github.com>
```
