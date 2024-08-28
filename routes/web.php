<?php
use App\Http\Middleware\UserAuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\EmailConfigController;
use App\Http\Controllers\ScheduleMailController;

/* Authentication and Authorization routes*/
Route::get('/',function(){
    return redirect('/login');
})->middleware(UserAuthMiddleware::class);
Route::get('/login', function () {
    return view('login');
})->middleware(UserAuthMiddleware::class)->name('login.form');
Route::post("/login",[UserAuthController::class,'check']);
Route::get('/register',function(){
    return view("register");
})->middleware(UserAuthMiddleware::class);
Route::post('/register',[UserAuthController::class,'send_otp']);
Route::get('/verify-otp',function(){
    return view('otp');
});
Route::post('/verify-otp',[UserAuthController::class,'verify_otp']);

Route::get('/forget',function(){
    return view('forget');
});
Route::post('/forget',[UserAuthController::class,'forget']);
Route::get("/verify_forget",function(){
    return view("otp_forget");
});
Route::post('/verify_forget',[UserAuthController::class,'verify_forget']);
Route::get("/change_password",function(){
    return view('change_password');
});
Route::post("/change_password",[UserAuthController::class,'change_password']);
Route::get('/logout',[UserAuthController::class,'logout'])->name('logout')->middleware(UserAuthMiddleware::class);



/*home(dashboard) management and configuration*/
Route::get("/dashboard",[EmailConfigController::class,'dashboard'])->name('dashboard')->middleware(UserAuthMiddleware::class);

Route::get("/add_email",function(){
    return view('addEmail');
})->middleware(UserAuthMiddleware::class);
Route::post("/add_email",[EmailConfigController::class,'add_email'])->name('email.otp')->middleware(UserAuthMiddleware::class);
Route::get("/verify-add-Email-otp",function(){
    return view('add_email_otp');
})->middleware(UserAuthMiddleware::class);
Route::post("/verify-add-Email-otp",[EmailConfigController::class,'verify_otp'])->middleware(UserAuthMiddleware::class);

/*main work scheduling emails and managing queue*/
Route::get("/start_warmupping",[ScheduleMailController::class,'start_jobs'])->middleware(UserAuthMiddleware::class);
Route::get("/stop_warmupping",[ScheduleMailController::class,'stop_jobs'])->middleware(UserAuthMiddleware::class);