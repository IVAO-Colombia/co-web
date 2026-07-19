# Verification workflows

After each implementation we must verify the code by using the following composer scripts

- lint
- rector
- phpstan

Those are defined inside the `scripts` section of the composer.json file

# Laravel code

- Whenever possible use the PHP #\[Attributes\] to add things like tables
- Don't add guarded or fillable attributes to models as we are using Model::unguard().
