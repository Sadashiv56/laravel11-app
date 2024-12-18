<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\OrderMeta;
use App\Models\Teacher;
use App\Models\Product;
use App\Models\User; 
use App\Http\Requests\CheckoutRequest;
use Session;
use Stripe;
use Stripe\PaymentIntent;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
class BookingController extends Controller
{
    public function index()
    {
       return view('front.stripe.create');
    }
    private function createUser(Request $request)
    {
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $request->phone_number,
        ]);
        // dd($user);
        return $user->id;
    }
    /*public function storeBooking(Request $request)
    {
        $createUser = $request->has('create_user');
        $userId = $createUser ? $this->createUser($request) : Auth::id();
        $bookings = [];
        foreach ($request->selected_slots as $slot) {
            list($start_time, $end_time) = explode('|', $slot);
            $booking = Booking::create([
                'date' => $request->date,
                'teacher_id' => $request->teacher_id,
                'product_id' => $request->product_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'user_id' => $userId,
                'payment_status' => 'pending', 
            ]);
            $bookings[] = $booking;
        }

        $totalPrice = $request->product_price * count($request->selected_slots);
        session(['bookingData' => [
            'user_id' => $userId,
            'product_name' => $request->product_name,
            'total' => $totalPrice,
            'payment_id' => $bookings[0]->id, 
        ]]);

        return redirect()->route('checkout.payment');
    }*/
    public function storeBooking(Request $request)
    {
        $createUser = $request->has('create_user');
        $userId = $createUser ? $this->createUser($request) : Auth::id();
        $firstSlot = null;
        foreach ($request->selected_slots as $index => $slot) {
            list($start_time, $end_time) = explode('|', $slot);
            if ($index === 0) {
                $firstSlot = [
                    'date' => $request->date,
                    'teacher_id' => $request->teacher_id,
                    'product_id' => $request->product_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'user_id' => $userId,
                    'payment_status' => 'pending',
                ];
            }
            OrderMeta::create([
                'date' => $request->date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'user_id' => $userId,
                'teacher_id' => $request->teacher_id, 
            ]);
        }
        if ($firstSlot) {
            $booking = Booking::create($firstSlot);
            $orderMetas = OrderMeta::where('user_id', $userId)->get();
            foreach ($orderMetas as $orderMeta) {
                $orderMeta->book_id = $booking->id; 
                $orderMeta->save();
            }
        }
        $totalPrice = $request->product_price * count($request->selected_slots);
        session(['bookingData' => [
            'user_id' => $userId,
            'product_name' => $request->product_name,
            'total' => $totalPrice,
            'payment_id' => $booking->id,
        ]]);
        return redirect()->route('checkout.payment');
    }
    public function createPaymentIntent(PaymentRequest $request)
    {
        // dd($request->all());
            Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            // dd($stripe);
            $totalAmount = session('bookingData.total') * 100;
           /* $payment = Stripe\Charge::create([
                "amount" => $totalAmount,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment for booking."
            ]);*/
            $paymentIntent = \Stripe\PaymentIntent::create([
                    "amount" => $totalAmount,
                    "currency" => "usd",
                    'automatic_payment_methods' => ['enabled' => true],
            ]);
            // dd($paymentIntent);
            $booking = Booking::find(session('bookingData.payment_id'));
            if ($booking) {
                $booking->payment_id = $paymentIntent->id; 
                // $booking->payment_method_id = $paymentIntent->payment_method; 
                $booking->payment_status = 'pending'; 
                $booking->save();
            }
            // dd($booking);
            /*return back()->with('success', 'Payment successful! Your booking has been confirmed.');*/
            return redirect()->route('front.home')->with('success', 'Payment successful! Your booking has been confirmed.');
    }
    public function stripeSuccess(Request $request)
    {
        
    }
    public function stripeCancel()
    {
        return "Your payment was canceled. Please try again.";
    }
}
