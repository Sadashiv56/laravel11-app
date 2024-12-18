<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Teacher;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $data = Order::select(['id', 'first_name', 'last_name', 'email', 'start_time', 'end_time', 'payment_status'])->get();
            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $edit = route('order.edit', $data->id);
                    $approveButton = $data->payment_status === 'pending' ?
                    '<button class="btn btn-warning btn-sm change-status" data-id="' . $data->id . '" data-status="' . $data->payment_status . '">Approve Payment</button>' :
                    '';

                return '
                    <a href="' . $edit . '" class="edit btn btn-primary btn-sm">Edit</a>
                    ' . $approveButton;
            })
                ->rawColumns(['action'])
                ->make(true);
        }
            return view('order.index');
    }
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $products = Product::all(); 
        $teachers = Teacher::all(); 
        return view('order.edit', compact('order', 'products', 'teachers')); 
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'order_id' => 'required|exists:books,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $order = Order::findOrFail($request->input('order_id'));
        $order->first_name = $request->input('first_name');
        $order->last_name = $request->input('last_name');
        $order->email = $request->input('email');
        $order->phone_number = $request->input('phone_number');
        $order->address = $request->input('address');
        $order->product_id = $request->input('product_id');
        $order->teacher_id = $request->input('teacher_id');
        $order->save();
        // dd($order);
        return redirect()->route('order.index')->with('success', 'Order updated successfully!');
    }
    public function approve($id)
    {
        $order = Order::find($id);
        if ($order) {
            if ($order->payment_status === 'pending') {
                try {
                    $stripeClient = new \Stripe\StripeClient(config('services.stripe.secret'));
                    // dd($stripeClient);
                    $paymentIntent = $stripeClient->paymentIntents->confirm($order->payment_id, [
                        'payment_method' => 'pm_card_visa',
                        'return_url' => route('order.index'), 
                    ]);
                    $paymentIntent = $stripeClient->paymentIntents->retrieve($order->payment_id);
                    // dd($paymentIntent->status,$paymentIntent->status === 'succeeded');
                    if ($paymentIntent->status === 'succeeded') {
                        $order->payment_status = 'completed';
                        // dd($order);
                        $order->save();

                        return response()->json(['success' => true, 'message' => 'Payment approved successfully.']);
                    } elseif ($paymentIntent->status === 'requires_action') {
                        return response()->json(['success' => true, 'requires_action' => true, 'payment_intent' => $paymentIntent->id], 200);
                    }

                    return response()->json(['success' => false, 'message' => 'Payment not completed.'], 400);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Failed to confirm payment: ' . $e->getMessage()], 500);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Payment is not in pending status.'], 400);
            }
        }
        return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    }
    public function cancel($id)
    {
        $order = Order::find($id);
        if ($order) {
            try {
                $stripeClient = new \Stripe\StripeClient(config('services.stripe.secret'));
                // dd($stripeClient);
                $paymentIntent = $stripeClient->paymentIntents->retrieve($order->payment_id);
                // dd($paymentIntent);
                if ($paymentIntent->status === 'requires_payment_method' || $paymentIntent->status === 'pending') {
                    $stripeClient->paymentIntents->cancel($paymentIntent->id);
                }

                $order->payment_status = 'canceled';
                $order->save();
                // dd($order);

                return response()->json(['success' => true, 'message' => 'Order has been canceled.']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to cancel payment: ' . $e->getMessage()], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    }
}
