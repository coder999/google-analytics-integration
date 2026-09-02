<?php

declare(strict_types=1);

namespace Mtmd\Ga4;

use InvalidArgumentException;

final class Tag
{
    /**
     * Renders the gtag.js snippet. Returns an empty string when the site has
     * no measurement ID configured, so callers can emit it unconditionally.
     *
     * The ID is validated against a strict pattern rather than escaped,
     * because it is interpolated into a JavaScript string literal where
     * HTML escaping would not be the right defence.
     */
    public static function render(string $measurementId): string
    {
        $id = trim($measurementId);
        if ($id === '') {
            return '';
        }

        if (!preg_match('/^G-[A-Z0-9]{6,20}$/i', $id)) {
            throw new InvalidArgumentException(
                'Not a GA4 measurement ID: ' . $id . '. Expected the "G-XXXXXXXXXX" form '
                . '(the numeric property ID belongs in GA4_PROPERTY_ID).'
            );
        }

        return <<<HTML
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={$id}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '{$id}');
        </script>

        HTML;
    }
}
