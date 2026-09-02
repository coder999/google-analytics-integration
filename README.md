# ga4

GA4 Data API reporting client, gtag renderer, and property provisioning CLI. No runtime dependencies, no database.

## Consumer entry points

- `Dashboard` — GA4 Data API client for building reports
- `Tag` — gtag.js event tracking renderer
- `bin/ga-provision` — Property provisioning CLI

## Installation

Add to `composer.json`:

```json
{
    "require": { "coder999/ga4": "^0.1.0" },
    "repositories": [
        { "type": "vcs", "url": "https://github.com/coder999/ga4" }
    ]
}
```

Then run `composer install`.
