<?php

declare(strict_types=1);

namespace Mtmd\Ga4\Tests;

use InvalidArgumentException;
use Mtmd\Ga4\Tag;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{
    public function testRendersTheGtagSnippetForAValidId(): void
    {
        $html = Tag::render('G-37XDTHZRHV');

        $this->assertStringContainsString(
            'https://www.googletagmanager.com/gtag/js?id=G-37XDTHZRHV',
            $html
        );
        $this->assertStringContainsString("gtag('config', 'G-37XDTHZRHV')", $html);
        $this->assertStringContainsString('window.dataLayer = window.dataLayer || []', $html);
    }

    public function testReturnsAnEmptyStringWhenUnconfigured(): void
    {
        $this->assertSame('', Tag::render(''));
        $this->assertSame('', Tag::render('   '));
    }

    public function testTrimsSurroundingWhitespace(): void
    {
        $this->assertStringContainsString('id=G-37XDTHZRHV', Tag::render('  G-37XDTHZRHV  '));
    }

    public function testRejectsAnIdThatIsNotAMeasurementId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('measurement ID');
        Tag::render('123456789');
    }

    public function testRejectsAnIdContainingMarkup(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Tag::render("G-ABC'</script><script>alert(1)</script>");
    }
}
