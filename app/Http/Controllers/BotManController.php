<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Conversations\ExampleConversation;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    public function handle()
    {
        // Load the driver(s) you want to use
        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
 
        $config = [
            // Your driver-specific configuration
            "telegram" => [
               "token" => env('TELEGRAM_TOKEN'),
            ]
        ];
        $botman = BotManFactory::create($config, new LaravelCache());
 
        $botman->hears('/start|start|mulai', function (BotMan $bot) {
            $user = $bot->getUser();
            $bot->reply('Assalamualaikum '.$user->getFirstName().', Selamat datang di Hadits Telegram Bot!. ');
            $bot->startConversation(new ExampleConversation());
        })->stopsConversation();
 
        $botman->hears('/kitab|kitab', function (BotMan $bot) {
            $bot->startConversation(new ExampleConversation());
        })->stopsConversation();
 
        $botman->hears('/lapor|lapor|laporkan', function (BotMan $bot) {
            $bot->reply('Silahkan laporkan di email weare@zalabs.my.id . Laporan kamu akan sangat berharga buat kemajuan bot ini.');
        })->stopsConversation();
 
        $botman->hears('/tentang|about|tentang', function (BotMan $bot) {
            $bot->reply('HaditsID Telegram Bot By ZaLabs. Mohon maaf jika server terasa lamban, dikarenakan menggunakan free hosting dari Heroku(.)com. Data didapatkan dari https://s.id/zXj6S .');
        })->stopsConversation();
 
        $botman->listen();
    }
}
