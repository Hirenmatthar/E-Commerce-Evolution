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
        // $search = $request->input('search');
        // $query = Customer::query();

        // if ($search) {
        //     $query->where('name', 'LIKE', "%$search%");
        // }

        // $customers = $query->latest()->paginate(4);
        // Paginator::useBootstrap();

        // return view('customer.index', compact('customers', 'search'))
        //     ->with('i', ($customers->currentPage() - 1) * 5);
        $data['customers'] = Customer::all();

      return view('customer.index',$data);
    }

    public function getCustomers(Request $request){

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $records = Customer::select('count(*) as allcount');
        $totalRecords = $records->count();

        // Total records with filter
        $records = Customer::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%');
        $totalRecordswithFilter = $records->count();

        // Fetch records
        $records = Customer::orderBy($columnName,$columnSortOrder)
                   ->select('customers.*')
                   ->where('customers.name', 'like', '%' .$searchValue . '%');

        $customers = $records->skip($start)
                     ->take($rowperpage)
                     ->get();

        $data_arr = array();
        foreach($customers as $customer){

           $first_name = $customer->first_name;
           $last_name = $customer->last_name;
           $email = $customer->email;
           $phone_no = $customer->phone_no;
           $address = $customer->address;
           $country = $customer->address;
           $state = $customer->state;
           $city = $customer->city;
           $postal_code = $customer->postal_code;

           $data_arr[] = array(
               "first_name" => $first_name,
               "last_name" => $last_name,
               "email" => $email,
               "phone_no" => $phone_no,
               "address" => $address,
               "country" => $country,
               "state" => $state,
               "city" => $city,
               "postal_code" => $postal_code,
           );
        }

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr
        );

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
