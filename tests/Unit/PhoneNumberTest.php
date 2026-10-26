<?php

namespace Tests\Unit;

use App\Support\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PhoneNumberTest extends TestCase
{
    public static function validNumbers(): array
    {
        return [
            'national format' => ['08031234567', '+2348031234567'],
            'spaces and dashes' => ['0803-123 4567', '+2348031234567'],
            'country code no plus' => ['2348031234567', '+2348031234567'],
            'plus with trunk zero' => ['+234 (0)803 123 4567', '+2348031234567'],
            'double zero prefix' => ['002348031234567', '+2348031234567'],
            'bare national number' => ['8031234567', '+2348031234567'],
            'foreign number' => ['+14155552671', '+14155552671'],
        ];
    }

    #[DataProvider('validNumbers')]
    public function test_it_normalizes_to_e164(string $input, string $expected): void
    {
        $this->assertSame($expected, PhoneNumber::normalize($input, '234'));
    }

    public function test_it_rejects_unusable_input(): void
    {
        $this->assertNull(PhoneNumber::normalize('abc', '234'));
        $this->assertNull(PhoneNumber::normalize('123', '234'));
    }

    public function test_it_masks_the_middle_digits(): void
    {
        $this->assertSame('+234*******567', PhoneNumber::mask('+2348031234567'));
    }
}
