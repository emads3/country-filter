<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Jumia - Emad Saeed</title>
</head>
<body class="container">

<h1>Phone Numbers</h1>

<form action="" method="GET">

    <select name="country">
        <option value=""></option>
        @foreach($countriesNames as $country)
            <option
                value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
        @endforeach
    </select>

    <select name="state">
        <option value=""></option>
        <option value="ok" {{ request('state') === 'ok' ? 'selected' : '' }}>Valid</option>
        <option value="nok" {{  request('state') === 'nok' ? 'selected' : ''  }}>Not Valid</option>
    </select>

    <button class="btn btn-success">Filter</button>

</form>

<hr>
<br>

<table class="table">
    <thead>
    <tr>
        <th>Country</th>
        <th>State</th>
        <th>Country Code</th>
        <th>Phone Num</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->country }}</td>
            <td>{{ $customer->state }}</td>
            <td>{{ $customer->countryCode }}</td>
            <td>{{ $customer->phoneNum }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $customers->withQueryString()->links() }}

</body>
</html>
