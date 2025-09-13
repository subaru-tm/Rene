<div>
    <button class="review-button" wire:click="openModal()" type="button">
        評価する
    </button>

    @if($showModal)
        <div class="review-modal__background">
            <div class="review-modal__header">
                <div class="review-modal__header-message">
                    <p>お食事はいかがでしたか？</p>
                    <p>満足度を５段階で評価してください</p>
                </div>
                <div class="review-modal__header-close">
                    <button wire:click="closeModal()" type="button">×</button> 
                </div>
            </div>
            <div class="review-modal__body">
                <form class="modal-form" action="/review/{{ $reservation_id }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-form__questionnaire">
                        <div class="modal-form__rating">
                            <input class="form-rating__input" id="star1" name="rating" type="radio" value="1">
                            <input class="form-rating__input" id="star2" name="rating" type="radio" value="2">
                            <input class="form-rating__input" id="star3" name="rating" type="radio" value="3">
                            <input class="form-rating__input" id="star4" name="rating" type="radio" value="4">
                            <input class="form-rating__input" id="star5" name="rating" type="radio" value="5">

                            <!-- マウスオンおよびクリックで★の色変更のため
                               - lableをまとめ、順番を逆転させている -->
                            <label class="form-rating__label5" for="star5">★</label>
                            <label class="form-rating__label4" for="star4">★</label>
                            <label class="form-rating__label3" for="star3">★</label>
                            <label class="form-rating__label2" for="star2">★</label>
                            <label class="form-rating__label1" for="star1">★</i></label>
                        </div>
                    </div>
                    <div class="modal-form__comment">
                        <p>コメント</p>
                        <textarea name="comment"></textarea>
                    </div>
                    <div class="modal-form__footer">
                        <button type="submit">送信する</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
