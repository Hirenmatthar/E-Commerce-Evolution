<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\File;
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
                $image = $record->image ? '<img src="' . asset($record->image) . '" alt="Customer Image" width="100">' : 'No Image',
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(\public_path('customer_images'), $imageName);
            $customer->image = 'customer_images/' . $imageName;
        }

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
        $data1['states'] = State::get(['name','id']);
        return view('customer.edit',compact('customer'),$data,$data1);
    }
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $previousImage = $customer->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('d-m-y') . "_" . $image->getClientOriginalName();
            $destinationPath = 'customer_images';
            $path = $image->move($destinationPath, $imageName);

            if ($previousImage) {
                // Delete the previous image
                File::delete(public_path($previousImage));
            }

            $customer->image = $path;
        } elseif ($request->has('delete_image')) {
            // Delete the image if delete_image checkbox is selected
            if ($previousImage) {
                File::delete(public_path($previousImage));
            }
            $customer->image = null;
        } else {
            // No new image selected and delete_image checkbox not selected, keep the previous image
            $customer->image = $previousImage;
        }
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
        if (method_field('DELETE')) {
            // Delete the image if delete_image checkbox is selected
            $previousImage = $customer->image;
            if ($previousImage) {
                File::delete(public_path($previousImage));
            }
            $customer->image = null;
            $customer->delete();
            return redirect()->route('customer.index')
                            ->with('success','Customer deleted successfully');
        }
    }
}
