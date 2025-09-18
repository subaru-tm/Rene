<div class="qrcode-area">
    <button type="button" wire:click="openModal()" class="reservation-card__qrcord-button">QRコードを表示</button>

    @if($showModal || $errors->any())
        <div class="qrcode-area__modal">
            <h5>QRコード</h5>
            <p>{!! $qrCode !!}</p>
            <button 
                type="button"
                wire:click="closeModal()"
                class="qrcode-area__modal-close"
            >閉じる</button>
        </div>
    @endif

</div>
