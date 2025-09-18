<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendMail {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(test) send mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cancel_flug_off = '0';
        $today = Carbon::now()->format('y-m-d');

        $today_reservations = Reservation::with('user')
            ->with('restaurant')
            ->ReservationOfToday($cancel_flug_off, $today)
            ->get();

        foreach($today_reservations as $reservation) {

            $restaurant_name = $reservation->restaurant->name;
            $user_name = $reservation->user->name;
            $reservation_time = Carbon::parse($reservation->time)->format('H:i');

            $subject = "ご予約の当日になりました　店舗名:{$restaurant_name}";
            $content = "{$user_name}  様<br />
                　本日のご予約をお知らせいたします。<br />
                　　店舗名 : {$restaurant_name}<br />
                　ご予約日 : {$reservation->date}<br />
                　予約時間 : 　　{$reservation_time}<br />
                　　　人数 : 　　　{$reservation->number}人<br />
                <br />
                　スタッフ一同、ご来店を心よりお待ちしております。<br />
                　{$restaurant_name}<br />";

            $user_email = $reservation->user->email;

            // ユーザー名、予約内容をメールに入れるため、予約一つずつメールを送信。
            Mail::to($user_email)->queue(new NotifyMail($subject, $content));

            sleep(20);
              // mailtrapのsandbox制約と思いますが、短い間隔での連続送信は
              // エラーとなるため、間隔をあけるための処置です。
        }

        return 0;
    }
}
