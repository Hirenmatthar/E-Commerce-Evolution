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
        $query = Customer::query();
        $customers = $query->latest()->paginate(5);
        Paginator::useBootstrap();

        return view('customer.index', compact('customers'))
            ->with('i', ($customers->currentPage() - 1) * 5);
    }
    public function getCustomers(Request $request)
    {
        // Read value
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $searchValue = $request->input('search.value');

        // Total records
        $totalRecords = Customer::count();

        // Apply search filter
        $filteredRecords = Customer::where('first_name', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records with pagination and search
        $records = Customer::where('first_name', 'like', '%' . $searchValue . '%')
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = [];
        $counter = $start + 1;

        foreach ($records as $record) {
            $row = [
                $counter,
                $record->first_name,
                $record->last_name,
                $record->email,
                $record->phone_no,
                $record->address,
                $record->country,
                $record->state,
                $record->city,
                $record->postal_code,
                '<a href="' . route('customer.edit', $record->id) . '" class="btn"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;' .
                '<a href="' . route('customer.show', $record->id) . '" class="btn"><i class="fa-solid fa-eye"></i></a>&nbsp;' .
                '<form action="' . route('customer.destroy', $record->id) . '" method="POST" style="display:inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn"><i class="fa-solid fa-trash-can"></i></button>
                </form>'
            ];
            $data[] = $row;
            $counter++;
        }
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
        return response()->json($response);
    }
    public function fetchState(Request $request){
        $data['states'] = State::where('country_id',$request->cid)->get(['name','id']);
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

        $country = Country::find($request->country);
        $state = State::find($request->state);
        $city = City::find($request->city);

        // Create a new Customer instance and populate the attributes
        $customer = new Customer();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_no = $request->phone_no;
        $customer->address = $request->address;
        $customer->country = $country->name; // Store the name of the country
        $customer->state = $state->name; // Store the name of the state
        $customer->city = $city->name; // Store the name of the city
        $customer->postal_code = $request->postal_code;
        $customer->save();
        return redirect()->route('customer.index')
                        ->with('success','Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customer.show',compact('customer'));
    }


    public function edit(Customer $customer)
    {
        $data['countries'] = Country::get(['name','id']);
        return view('customer.edit',compact('customer'),$data);
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

        $country = Country::find($request->country);
        $state = State::find($request->state);
        $city = City::find($request->city);

        // Create a new Customer instance and populate the attributes
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_no = $request->phone_no;
        $customer->address = $request->address;
        $customer->country = $country->name; // Store the name of the country
        $customer->state = $state->name; // Store the name of the state
        $customer->city = $city->name; // Store the name of the city
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
