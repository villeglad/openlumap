<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company;

class CompaniesController extends Controller
{

    public function index(Array $search)
    {
        $companies = Compnay::all();
        return view('holidays.index', compact('holidays'));
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return $company;
    }

    public function find(Request $request) 
    {
        
        $search = $request->input('search');
        $companies = Company::where('name', 'LIKE', "%$search%")
            ->orWhere('address', 'LIKE', "%$search%")
            ->orWhere('postcode', 'LIKE', "%$search%")
            ->orWhere('city', 'LIKE', "%$search%")
            ->orWhere('country', 'LIKE', "%$search%")
            ->orWhere('industry', 'LIKE', "%$search%")
            ->orWhere('address', 'LIKE', "%$search%")
            ->orWhere('www', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->orWhere('phone', 'LIKE', "%$search%")
            ->orWhere('www', 'LIKE', "%$search%")
            ->get();
    
        return $companies;
    }

    public function store(Request $request)
    {
        $company = Company::create($request->all());
        return $company;
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return $company;
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return true;
    }
}