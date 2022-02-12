<?php

namespace App\Models;

use App\Misc\CountryManipulator;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use CountryManipulator;

    protected $table = 'customer'; // override tableName, by default it's customers 'plural of the class name'
    protected $phoneColumnName = 'phone';


    public function getCountryAttribute()
    {
        return ucfirst($this->getCountryName($this->{$this->phoneColumnName}));
    }

    public function getStateAttribute()
    {
        return $this->isValid($this->{$this->phoneColumnName}) ? 'OK' : 'NOK';
    }

    public function getCountryCodeAttribute()
    {
        return $this->getCountryCode($this->{$this->phoneColumnName});
    }

    /**
     * returns the phone number without the country code
     */
    public function getPhoneNumAttribute()
    {
        return $this->getPhoneWithoutCountryCode($this->{$this->phoneColumnName});
    }

    public function scopeFromCountry($query, $country)
    {
        $country = $this->findCountryByName($country);
        $code = trim($country['code'], " \t\n\r\0\x0B+");

        $query->where('phone', 'like', "($code)%");
    }

    public function scopeValidState($query)
    {
        $query->where(function ($q) {
            foreach ($this->getCountriesPatterns() as $countryPattern) {
                $q->orWhereRaw('phone REGEXP ' . $countryPattern);
            }
        });
    }

    public function scopeInvalidState($query)
    {
        $query->where(function ($q) {
            foreach ($this->getCountriesPatterns() as $countryPattern) {
                $q->whereRaw('phone NOT REGEXP ' . $countryPattern);
            }
        });
    }

}

