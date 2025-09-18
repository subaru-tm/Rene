<?php

namespace App\Http\Livewire;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Reservation;

class QrcodeShow extends Component
{
    public $showModal=false;
    public $reservation_id;
    public $data;
    protected $qrCode;

    public function __construct($reservation_id){
        $this->reservation_id = $reservation_id;
    }

    public function openModal(){
        $this->showModal=true;
    }

    public function closeModal(){
        $this->showModal=false;
    }

    public function render()
    {
        $reservation = Reservation::find($this->reservation_id);
        $date = $reservation->date;
        $time = $reservation->time;
        $number = $reservation->number;
        $restaurant_id = $reservation->restaurant_id;

        // ここでは予約情報を文字列としてQRコード生成します。
        $data = "reservation_id : $this->reservation_id,
            reservation_date : $date,
            reservation_time : $time,
            reservation_number : $number,
            restaurant_id : $restaurant_id";
        $qrCode = QrCode::size(200)->generate($data);
            
        return view('livewire.qrcode-show')->with('qrCode', $qrCode);
    }
}
