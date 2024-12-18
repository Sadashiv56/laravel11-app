<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::post('/categories/checkName', [CategoryController::class, 'checkName'])->name('categories.checkName');
    Route::post('/categories/children', [CategoryController::class, 'getChildren'])->name('categories.getChildren');
    Route::resource('products', ProductController::class);
    Route::post('/check-sku', [ProductController::class, 'checkSku'])->name('products.checkSku');
    Route::get('/teacher/calendar/{id}', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/availability/{teacher_id}', [CalendarController::class, 'availability'])->name('calendar.create-availability');
    Route::post('/calendar/availability/{teacher_id}', [CalendarController::class, 'storeAvailability'])->name('calendar.store-availability');
    Route::get('/calendar/unavailability/{teacher_id}', [CalendarController::class, 'editUnavailability'])->name('calendar.edit-unavailability');
    Route::post('/calendar/unavailability/{teacher_id}', [CalendarController::class, 'updateUnavailability'])->name('calendar.update-unavailability');
    Route::get('calendar/unavailable-days/{teacher_id}', [CalendarController::class, 'createUnavailableDays'])->name('calendar.create-unavailable-days');
    Route::post('calendar/store-unavailable-days/{teacher_id}', [CalendarController::class, 'storeUnavailableDays'])->name('calendar.store-unavailable-days');

    Route::get('/calendar/get-day-records', [CalendarController::class, 'getDayRecords'])->name('calendar.get-day-records');

    
    Route::get('/calendar/view/{teacher_id}', [CalendarController::class, 'showCalendar'])->name('calendar.show-calendar');

    Route::post('/book-slots', [BookingController::class, 'bookSlots'])->name('book-slots');

    Route::resource('teachers', TeacherController::class);

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
    Route::post('/order/update', [OrderController::class, 'update'])->name('order.update');
    Route::post('order/approve/{id}', [OrderController::class, 'approve'])->name('order.approve');
    Route::post('order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');




});
/*Route::get('/user/login', [AuthController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'userlogin']);
Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
Route::post('/user/register', [AuthController::class, 'userregister']);
Route::post('/UserLogout', [AuthController::class, 'UserLogout'])->name('UserLogout');*/

/*front rout start*/
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/product_view', [FrontController::class, 'showAllProducts'])->name('front.products');
Route::get('/product_detail/{id}', [FrontController::class, 'detailProducts'])->name('front.product_detail');
Route::get('/teachers_list', [FrontController::class, 'showAllTeachers'])->name('front.show_teachers');
Route::get('/teachers_list/{teacher_id}', [CalendarController::class, 'showCalendar'])->name('calendar.teachers_list');
Route::get('/calendar/front-get-day-records', [CalendarController::class, 'getDayRecords'])->name('calendar.front-get-day-records');

Route::post('/front/checkout', [FrontController::class, 'checkout'])->name('front.checkout');
Route::get('/front/confirm-booking', [FrontController::class, 'confirmBooking'])->name('front.confirm-booking');


Route::post('/book-now', [FrontController::class, 'bookNow'])->name('front.book_now');
Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');

Route::get('/subscriptions/create', [SubscriptionController::class, 'show'])->name('subscriptions.create');
Route::post('/subscriptions', [SubscriptionController::class, 'create'])->name('subscriptions.store');
Route::post('/stripe/webhook', [SubscriptionController::class, 'handleWebhook']);


//payment//
Route::post('/book-slots', [BookingController::class, 'storeBooking'])->name('book-slots');
Route::post('/checkout-store', [BookingController::class, 'storeBooking'])->name('checkout.store');

Route::get('payment', [BookingController::class, 'index'])->name('checkout.payment');
Route::post('/create-payment-intent', [BookingController::class, 'createPaymentIntent'])->name('stripe.create.payment.intent');
Route::get('/stripe-success', [BookingController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/stripe-cancel', [BookingController::class, 'stripeCancel'])->name('stripe.cancel');



/*front rout end*/
require __DIR__.'/auth.php';



