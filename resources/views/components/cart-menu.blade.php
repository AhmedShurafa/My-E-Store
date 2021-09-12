<div class="ps-cart">
    <a class="ps-cart__toggle" href="#">
        <span><i>{{ $cart->all()->count() }}</i></span>
        <i class="ps-icon-shopping-cart"></i>
    </a>

    <div class="ps-cart__listing">
        <div class="ps-cart__content">
            @foreach ($cart->all() as $item)
                @if ($loop->index == 3)
                    @break
                @endif
                <div class="ps-cart-item">
                    <a class="ps-cart-item__close" href="{{ $item->product->permalink }}"></a>

                    <div class="ps-cart-item__thumbnail">
                        <a href="{{ $item->product->permalink }}"></a>
                        <img src="{{ $item->product->image_url }}" alt="">
                    </div>

                    <div class="ps-cart-item__content">
                        <a class="ps-cart-item__title"
                            href="{{ $item->product->permalink }}">{{ $item->product->name }}</a>

                        <p> <span>Quantity:<i>{{ $item->quantity }}</i></span>
                            <span>Total:<i>£{{ $item->quantity * $item->product->price }}</i></span>
                        </p>
                    </div>
                </div>

            @endforeach

        </div>
        <div class="ps-cart__total">
            <p>Number of items:<span>{{ $cart->quantity() }}</span></p>
            <p>Item Total:<span>£{{ $cart->total() }}</span></p>
        </div>
        <div class="ps-cart__footer"><a class="ps-btn" href="{{ route('cart.show') }}">Check out<i
                    class="ps-icon-arrow-left"></i></a></div>
    </div>
</div>
