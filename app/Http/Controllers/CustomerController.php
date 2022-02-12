<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Repositories\CountriesRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request, CountriesRepository $countriesRepository)
    {
        $countriesNames = collect($countriesRepository->getAllCountries())->pluck('name')->toArray();

        $customers = Customer::query()
            ->when(
                $request->input('country') &&
                in_array(strtolower($request->input('country')), $countriesNames, false),
                function ($query) {

                    $query->fromCountry(request()->input('country'));

                })
            ->when(
                $request->has('state') && in_array($request->input('state'), ['ok', 'nok']),
                function ($query) {

                    \request()->input('state') === 'ok' ? $query->validState() : $query->invalidState();

                })
            ->paginate(10);

        return view('customers.index', compact('customers', 'countriesNames'));
    }
}
