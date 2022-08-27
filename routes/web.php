<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserPayment;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = User::first();
    if(!$user){
        for($i=1;$i<=3;$i++){
            $user = User::create([
                'name' => 'name '.$i,
                'email' => 'email.'.$i.'@gmail.com',
                'password' => bcrypt('1234'),
            ]);
            for($a=1;$a<=3;$a++){
                UserPayment::create([
                    'user_id' => $user->id,
                    'charge' => 100,
                    'payment_date' => date('Y-m').'-0'.$a,
                ]);
            }
        }
    } else {

        $users = User::whereDoesntHave('payments', function ($query) {
            //$query->whereIn('payment_date', ['2022-08-04', '2022-08-02', '2022-08-03']);
            //$query->whereDate('payment_date', '>', '2022-08-01');
            //$query->whereDate('payment_date', '<', '2022-08-03');
            $query->whereBetween('payment_date', ['2022-08-01', '2022-08-03']);
        })->get();
        $users = User::whereBetween('payment_date', ['2022-08-01', '2022-08-03'])->get();
        dd($users);

    }
    return view('welcome');
    
});
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
