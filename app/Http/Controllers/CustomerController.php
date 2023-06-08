<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Pagination\Paginator;
use App\Models\{Country,State,City};

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Customer::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $customers = $query->latest()->paginate(5);
        Paginator::useBootstrap();

        return view('customer.index', compact('customers', 'search'))
            ->with('i', ($customers->currentPage() - 1) * 5);
    }

    public function fetchState(Request $request){
        $data['states'] = State::where('country_id',$request->cid)->get(['name','value']);
        print_r($data);
        return response()->json($data);
    }

    public function fetchCity(Request $request){
        $data['cities'] = City::where('state_id',$request->state_id)->get(['name','id']);
        return response()->json($data);
    }

    public function create()
    {
        $data['countries'] = Country::get(['name','id']);
        return view('customer.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_no' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);

        $input = $request->all();
        Customer::create($input);

        return redirect()->route('customer.index')
                        ->with('success','Customer created successfully.');
    }


    public function show(Customer $customer)
    {
        return view('customer.show',compact('customer'));
    }


    public function edit(Customer $customer)
    {
        return view('customer.edit',compact('customer'));
    }


    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_no' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_no = $request->phone_no;
        $customer->address = $request->address;
        $customer->country = $request->country;
        $customer->state = $request->state;
        $customer->city = $request->city;
        $customer->postal_code = $request->postal_code;
        $customer->save();

        return redirect()->route('customer.index')
            ->with('success', 'Customer updated successfully.');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer.index')
                        ->with('success','Customer deleted successfully');
    }


}
