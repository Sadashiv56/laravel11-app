<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Teacher;
use App\Models\Booking;

class FrontController extends Controller
{
    public function index()
    {
        $product = Product::limit(3)->get();
        return view('front.home', compact('product'));
    }
    public function showAllProducts()
    {
        $product = Product::all();
        $selectedProductId = session('selected_product_id');
        return view('front.products', compact('product','selectedProductId'));
    }
    public function menu()
    {
        return view('front.menu');
    } 
    public function detailProducts($id)
    {
        $product = Product::findOrFail($id); 
        $selectedProductId = session('selected_product_id');
    return view('front.product_detail', compact('product', 'selectedProductId'));
    }
    public function bookNow(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);

    $productId = $request->input('product_id');


   /*  $product = Product::find($productId);
    if (!$product) {
        return redirect()->route('front.home')->with('error', 'Product not found.');
    }*/

    session()->put('selected_product_id', $productId);

    return response()->json([
        'success' => true,
        'message' => 'Product ID received successfully.',
         'redirect_url' => route('front.show_teachers')
    ]);
}

    public function showAllTeachers()
    {
        /*$teachers = Teacher::all();
        return view('front.teacher_list', compact('teachers'));*/
         $productId = session('selected_product_id');
          if (!$productId || !Product::find($productId)) {
        return redirect()->route('front.products')->with('error', 'Please select Product.');
        }
        $teachers = Teacher::all();
        return view('front.teacher_list', compact('teachers'));
    }
    public function register()
    {
        return view('front.register');
    }
    public function login()
    {
        return view('front.login');
    } 
public function checkout(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'teacher_id' => 'required|exists:teachers,id',
        'product_id' => 'required|exists:products,id',
        'selected_slots' => 'required|array',
    ]);


    $product = Product::find($request->product_id);
    $teacher = Teacher::find($request->teacher_id);
    

    if (!$product || !$teacher) {
        return redirect()->route('front.home')->with('error', 'Invalid booking data.');
    }
    session()->put('bookingData', [
        'date' => $request->date,
        'teacher_id' => $request->teacher_id,
        'product_id' => $request->product_id,
        'selected_slots' => $request->selected_slots,
        'product_name' => $product->title,
        'product_price' => $product->price, 
        'teacher_name' => $teacher->name,
    ]);

    $bookingData = session('bookingData');
        return redirect()->route('front.confirm-booking')->with('success', 'Proceed to confirmation page.');

}



public function confirmBooking(){
     $productId = session('selected_product_id');
          if (!$productId || !Product::find($productId)) {
        return redirect()->route('front.products')->with('error', 'Please select Product.');
        }
    return view('front.checkout');

}










     


}
