<?php

namespace Webkul\Customer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VatIdRule implements ValidationRule
{
    /**
     * The country code from the input form.
     *
     * @var string
     */
    private $country;

    /**
     * Run the validation rule.
     *
     * The rules are borrowed from:
     *
     * @see https://raw.githubusercontent.com/danielebarbaro/laravel-vat-eu-validator/master/src/VatValidator.php
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Allow empty VAT ID - it's optional
        if (empty($value)) {
            return;
        }

        $validator = new VatValidator;

        // Only validate if value is provided and doesn't match basic pattern
        // Allow any alphanumeric string of reasonable length (5-30 chars)
        if (! empty($value) && ! preg_match('/^[A-Z0-9]{5,30}$/i', $value)) {
            // If it doesn't match the basic pattern, try the strict VAT validation
            if (! $validator->validate($value, $this->country)) {
                $fail('customer::app.validations.vat-id.invalid-format')->translate();
            }
        }
    }

    /**
     * Set the country code.
     *
     * @param  string  $country
     */
    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }
}
