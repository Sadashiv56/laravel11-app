<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\File; 

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::all(); 
            return datatables()->of($data)
                ->addColumn('action', function (Product $data) {
                    $edit = route('products.edit', $data->id);
                    $deleteLink = route('products.destroy', $data->id);
                    $btn = '<a href="' . $edit . '" class="edit btn btn-success btn-sm">Edit</a>';
                    $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm delete-button">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('products.index');
    }
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $allProducts = Product::all();
        return view('products.create', compact('categories', 'allProducts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'specifications' => 'nullable|array',
            'categories' => 'required|array',
            'product_ids' => 'nullable|array',
        ]);

        $categoryIds = implode(',', $request->categories);
        $specifications = $request->specifications ? json_encode($request->specifications) : null;
        $productIds = $request->product_ids ? implode(',', $request->product_ids) : null;
        $uploadDir = public_path('products_photo');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }
        $filename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadDir, $filename);
        }
        Product::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'specifications' => $specifications,
            'image' => $filename, 
            'category_ids' => $categoryIds,
            'product_ids' => $productIds,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }
    public function edit($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $allProducts = Product::all();
        return view('products.create', compact('product', 'categories', 'allProducts'));
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'specifications' => 'nullable|array',
            'categories' => 'required|array',
            'product_ids' => 'nullable|array',
        ]);
        $categoryIds = implode(',', $request->categories);
        $specifications = $request->specifications ? json_encode($request->specifications) : null;
        $productIds = $request->product_ids ? implode(',', $request->product_ids) : null;
        $uploadDir = public_path('products_photo');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }
        $filename = $product->image;
        if ($request->hasFile('image')) {
            if ($filename && File::exists($uploadDir . '/' . $filename)) {
                File::delete($uploadDir . '/' . $filename);
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadDir, $filename);
        }
        $product->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'specifications' => $specifications,
            'image' => $filename,
            'category_ids' => $categoryIds,
            'product_ids' => $productIds,
        ]);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $product = Product::findOrFail($id);
                $product->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    public function checkSku(Request $request)
    {
        $sku = $request->input('sku');
        $productId = $request->input('product_id'); 
        $exists = Product::where('sku', $sku)
                         ->when($productId, function($query) use ($productId) {
                             return $query->where('id', '!=', $productId);
                         })
                         ->exists();
        return response()->json(['exists' => $exists]);
    }

}
