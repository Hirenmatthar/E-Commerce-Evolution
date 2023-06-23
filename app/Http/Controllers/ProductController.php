<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        $products = $query->latest()->paginate(5);
        Paginator::useBootstrap();

        return view('product.index', compact('products'))
            ->with('i', ($products->currentPage() - 1) * 5);
    }

    public function getProducts(Request $request)
    {
        // Read value
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $searchValue = $request->input('search.value');

        // Total records
        $totalRecords = Product::count();

        // Apply search filter
        $filteredRecords = Product::where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records with pagination and search
        $records = Product::where('name', 'like', '%' . $searchValue . '%')
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = [];
        $counter = $start + 1;

        foreach ($records as $record) {
            $status = $record->status == 1 ? '<span class="badge rounded-pill text-success bg-success text-light">Active</span>' : '<span class="badge rounded-pill text-danger bg-danger text-light">Inactive</span>';
            $category = Category::find($record->cat_id);
            $categoryName = $category ? $category->name : 'N/A';
            $row = [
                $counter,
                $record->name,
                $record->brand,
                $record->code,
                $thumbnail = $record->thumbnail ? '<img src="' . asset($record->thumbnail) . '" alt="Product Thumbnail" width="100">' : 'No Thumbnail',
                $record->price,
                $record->description,
                $record->quantity,
                $status,
                $categoryName,
                '<a href="' . route('product.edit', $record->id) . '" class="btn"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;' .
                '<a href="' . route('product.show', $record->id) . '" class="btn"><i class="fa-solid fa-eye"></i></a>&nbsp;' .
                '<form action="' . route('product.destroy', $record->id) . '" method="POST" style="display:inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn"><i class="fa-solid fa-trash-can"></i></button>
                </form>' .
                '<a href="' . route('product.show', $record->id) . '" class="btn"><i class="fa-solid fa-thumbnails"></i></a>&nbsp;'
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

    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cat_name' => 'required',
            'name' => 'required',
            'brand' => 'required',
            'code' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'images.*' => 'required|image|max:2048',
            'price' => 'required|numeric',
            'description' => 'required',
            'status' => 'required',
            'quantity' => 'required|integer',
        ]);

        $product = new Product;
        $product->cat_id = $request->cat_name;
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->code = $request->code;

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_' . $thumbnail->getClientOriginalName();
            $thumbnail->move(\public_path('thumbnail'), $thumbnailName);
            $product->thumbnail = 'thumbnail/' . $thumbnailName;
        }

        $product->price = $request->price;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->status = $request->status;
        $product->save();

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(\public_path('uploaded_images'), $imageName);
                $imageRecord = new Image();
                $imageRecord->product_id = $product->id;
                $imageRecord->product_img = 'uploaded_images/'.$imageName;
                $imageRecord->save();
            }
        }
        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $productImages = Image::where('product_id', $product->id)->get();
        return view('product.show', compact('product', 'productImages'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $productImages = Image::where('product_id', $product->id)->get();
        return view('product.edit', compact('product', 'categories', 'productImages'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',
            'code' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'status' => 'required',
            'cat_name' => 'required',
        ]);

        $previousThumbnail = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . "_" . $thumbnail->getClientOriginalName();
            $destinationPath = 'thumbnail';
            $path = $thumbnail->move($destinationPath, $thumbnailName);

            if ($previousThumbnail) {
                // Delete the previous thumbnail
                File::delete(public_path($previousThumbnail));
            }

            $product->thumbnail = $path;
        } elseif ($request->has('delete_thumbnail')) {
            // Delete the thumbnail if delete_thumbnail checkbox is selected
            if ($previousThumbnail) {
                File::delete(public_path($previousThumbnail));
            }
            $product->thumbnail = null;
        } else {
            // No new thumbnail selected and delete_thumbnail checkbox not selected, keep the previous thumbnail
            $product->thumbnail = $previousThumbnail;
        }

        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->code = $request->code;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->status = $request->status;
        $product->cat_id = $request->cat_name;
        $product->save();
        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }
    public function destroy(Product $product)
    {
        // Delete the thumbnail if delete_thumbnail checkbox is selected
        $previousThumbnail = $product->thumbnail;
        if ($previousThumbnail) {
            File::delete(public_path($previousThumbnail));
        }

        // Delete the uploaded images from file directory
        $productImages = $product->images;
        foreach ($productImages as $productImage) {
            $imagePaths = explode(',', $productImage->product_img);
            foreach ($imagePaths as $imagePath) {
                $imagePath = public_path($imagePath);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        }

        // Delete the images from the database
        $product->images()->delete();

        // Delete the product record
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product and associated images deleted successfully');
    }
    public function delete($image)
    {
        $productImage = Image::where('id', $image)->first();
        if ($productImage) {
            if (File::exists($productImage->product_img)) {
            File::delete($productImage->product_img);
        }
            $productImage->delete();
            return redirect()->back()->with('success', 'Image deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Image not found.');
        }
    }
    public function storeImage(Request $request, $productId)
    {
        // Validate the image file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Retrieve the uploaded image file
        $imageFile = $request->file('image');
        // Generate a unique filename for the image
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        // Store the image file in the 'uploaded_images' folder
        $imageFile->move(public_path('uploaded_images'), $imageName);
        $productImage = new Image;
        $productImage->product_img = 'uploaded_images/'.$imageName;
        $productImage->product_id = $productId;
        $productImage->save();
        // Create a new ProductImages instance
        return redirect()->back()->with('success', 'Image inserted successfully.');
    }
}
