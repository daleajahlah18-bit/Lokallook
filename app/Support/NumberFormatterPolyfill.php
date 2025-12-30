<?php

namespace App\Support;

/**
 * Polyfill for NumberFormatter when Intl extension is not available.
 * This provides basic currency formatting functionality.
 */
class NumberFormatterPolyfill
{
    const CURRENCY = 1;
    const DECIMAL = 2;
    const PERCENT = 3;

    // Symbols
    const CURRENCY_SYMBOL = 0;
    const DECIMAL_SEPARATOR_SYMBOL = 1;
    const GROUPING_SEPARATOR_SYMBOL = 2;
    const MINUS_SIGN_SYMBOL = 3;
    const PERCENT_SYMBOL = 4;
    const ZERO_DIGIT_SYMBOL = 5;

    // Attributes
    const FRACTION_DIGITS = 0;
    const GROUPING_SIZE = 1;
    const ROUNDING_MODE = 2;
    const MULTIPLIER = 3;

    // Text attributes
    const CURRENCY_CODE = 0;
    const DEFAULT_RULESET = 1;
    const PUBLIC_RULESETS = 2;

    private $locale;
    private $style;
    private $currencyCode;
    private $pattern;

    public function __construct($locale, $style, $pattern = null)
    {
        $this->locale = $locale;
        $this->style = $style;
        $this->pattern = $pattern;
    }

    public function setAttribute($attr, $value)
    {
        if ($attr === 'FRACTION_DIGITS') {
            // Store fraction digits if needed
        }
        return true;
    }

    public function setSymbol($attr, $value)
    {
        if ($attr === 'CURRENCY_SYMBOL') {
            $this->currencyCode = $value;
        }
        return true;
    }

    public function setTextAttribute($attr, $value)
    {
        if ($attr === 'CURRENCY_CODE') {
            $this->currencyCode = $value;
        }
        return true;
    }

    public function getSymbol($attr)
    {
        if ($attr === 'CURRENCY_SYMBOL') {
            return $this->currencyCode ?? '$';
        }
        return '';
    }

    public function getAttribute($attr)
    {
        if ($attr === 'FRACTION_DIGITS') {
            return 2;
        }
        return null;
    }

    public function format($value, $type = null)
    {
        if ($this->style === self::CURRENCY) {
            // Simple currency formatting
            $symbol = $this->currencyCode ?? '$';
            return $symbol . number_format((float)$value, 2, '.', ',');
        } elseif ($this->style === self::DECIMAL) {
            return number_format((float)$value, 2, '.', ',');
        } elseif ($this->style === self::PERCENT) {
            return number_format((float)$value, 2, '.', ',') . '%';
        }
        return (string)$value;
    }

    public static function create($locale, $style, $pattern = null)
    {
        return new self($locale, $style, $pattern);
    }
}
