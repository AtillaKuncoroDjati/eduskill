<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    private $commonPasswords = [
        '123456',
        '654321',
        '12345678',
        '123456789',
        'password',
        '123123',
        '111111',
        '000000',
        '1234567',
        '1234567890',
        'qwerty',
        'abc123',
        'password1',
        'admin',
        'letmein',
        'welcome',
        'monkey',
        '1234',
        '12345'
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array($value, $this->commonPasswords)) {
            $fail('Kata sandi terlalu umum dan mudah ditekan. Silakan pilih kata sandi yang lebih kuat.');
        }

        // Tambahan validasi untuk pola berurutan
        if ($this->isSequential($value)) {
            $fail('Kata sandi tidak boleh berisi angka yang berurutan.');
        }

        if ($this->isRepeated($value)) {
            $fail('Kata sandi tidak boleh berisi karakter yang berulang.');
        }
    }

    private function isSequential(string $value): bool
    {
        $sequences = [
            '123456',
            '234567',
            '345678',
            '456789',
            '567890',
            '654321',
            '543210',
            '432109',
            '321098',
            '210987',
            'abcdef',
            'bcdefg',
            'cdefgh',
            'defghi',
            'efghij',
            'fedcba',
            'edcba9',
            'dcba98',
            'cba987',
            'ba9876'
        ];

        foreach ($sequences as $sequence) {
            if (str_contains(strtolower($value), $sequence)) {
                return true;
            }
        }

        return false;
    }

    private function isRepeated(string $value): bool
    {
        return preg_match('/(.)\1{5,}/', $value);
    }
}
