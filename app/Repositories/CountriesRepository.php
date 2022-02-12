<?php

namespace App\Repositories;

class CountriesRepository
{
    public function getAllCountries()
    {
        return json_decode(file_get_contents(database_path('countries.json')), true);
    }
}
