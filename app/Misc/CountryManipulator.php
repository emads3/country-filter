<?php

namespace App\Misc;

trait CountryManipulator
{
    public function getCountryName($phone): string|null
    {
        $code = $this->extractCodeFromPhoneNumber($phone);
        $country = $this->findCountryByCode($code);

        if ($country) return $country['name'];

        return null;
    }

    public function getCountryCode($phone): string|null
    {
        $code = $this->extractCodeFromPhoneNumber($phone);
        return '+' . $code;
    }

    public function findCountryByCode($code): ?array
    {
        $code = trim($code);

        // if code doesn't have a '+' sign before it, add it,
        // because it's registered in the countries file with prefix +
        if (!str_starts_with($code, '+')) {
            $code = '+' . $code;
        }

        foreach ($this->getCountriesFile() as $country)
            if ($code === $country['code']) return $country;

        return null;
    }

    public function findCountryByName($name): ?array
    {
        $name = strtolower(trim($name));

        foreach ($this->getCountriesFile() as $country)
            if ($name === $country['name']) return $country;

        return null;
    }

    public function getCountriesFile()
    {
        return json_decode(file_get_contents(database_path('countries.json')), true);
    }


    /**
     * extract the country code from the phone number itself which matches a string like: (1) (11) (111)
     */
    public function extractCodeFromPhoneNumber($phone)
    {
        $matches = [];

        preg_match('/(\(\d{1,3}\))/', $phone, $matches);

        if (count($matches)) { // already found a match

            $codeWithoutParenthesis = str_replace(['(', ')'], '', $matches[1]); // remove the ()

            return trim($codeWithoutParenthesis);
        }

        return null;
    }

    public function getPhoneWithoutCountryCode($phone): string|null
    {
        $phoneWithoutCountryCode = preg_filter('/(\(\d{1,3}\))/', '', $phone);

        return trim($phoneWithoutCountryCode);

    }

    public function isValid($phone)
    {
        $code = $this->extractCodeFromPhoneNumber($phone);
        $country = $this->findCountryByCode($code);

        $regexPatternWithDelimiter = '/' . $country['regex_pattern'] . '/';

        return preg_match($regexPatternWithDelimiter, $phone);
    }

    public function getCountriesPatterns()
    {
        return array_map(function ($country) {
            return $country['regex_pattern'];
        }, $this->getCountriesFile());
    }
}
