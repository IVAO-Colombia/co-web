## Verification workflows

This is a Laravel + TypeScript/Vue project using Wayfinder for route generation. Run route generation before linting Vue files to avoid local-vs-CI lint discrepancies.

After each implementation we must verify the code by using the following composer scripts

- lint
- rector
- phpstan

Those are defined inside the `scripts` section of the composer.json file

# Laravel code

- Whenever possible use the PHP #\[Attributes\] to add things like tables
- Don't add guarded or fillable attributes to models as we are using Model::unguard().

## Conventions

When adding or changing user-facing content, always include the Spanish translations. When implementing features, remove any dead/placeholder code before finishing.

## Testing section

Always run the full test suite after multi-file changes and confirm all tests pass before considering a task complete.
Add under

## External APIs

For authoritative API/enum details, prefer OpenAPI specs over JS-rendered documentation pages, since WebFetch may return incomplete data.
