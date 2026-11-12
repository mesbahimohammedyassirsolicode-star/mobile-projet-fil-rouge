# Smart Tourism Phase 3 Location Design

## Intent

Place the newly implemented Smart Tourism architecture in a dedicated
`phase3/` folder so it is clearly separated from the older `session1/` and
`session2/` prototypes.

## Scope

Move only the Smart Tourism Phase 1 files: `app/`, `public/`, `views/`,
`database/`, `tests/`, `bootstrap.php`, `.env.example`, and the Smart Tourism
README and planning documents. Keep the repository-level `.gitignore` at the
root so worktree and secret exclusions continue to apply. Leave
legacy session folders, existing root assets/schema files, and unrelated
working-tree changes untouched.

## Approach

Use Git moves so history is preserved and `phase3/` becomes the single
canonical project location. Update documentation and test commands for the
new working directory; do not duplicate the implementation at the root.

## Verification

Run the four PHP tests from `phase3/`, run PHP syntax checks, and confirm the
legacy folders and unrelated root files remain present.
