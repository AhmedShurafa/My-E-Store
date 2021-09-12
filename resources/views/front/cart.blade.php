<x-store-front-layout :title="__('Cart')">

    <div class="ps-content pt-80 pb-80">
        <div class="ps-container">
            <div class="ps-cart-listing">

                <div class="form-group text-right">
                    <a href="{{ route('cart.clear') }}" class="ps-btn ps-btn--gray" style="color: #fff;
                            background-color: #d9534f;
                            border-color: #d43f3a;">
                        <i class="fa fa-trash"></i>
                        Clear All Cart
                    </a>
                </div>

                <table class="table ps-cart__table">
                    <thead>
                        <tr>
                            <th>All Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)

                            <tr>
                                <td>
                                    <a class="ps-product__preview"
                                        href="{{ route('product.show', $cart->product->slug) }}">

                                        <img class="mr-15" width="100px"
                                            src="{{ asset($cart->product->image_url) }}" alt="">

                                        {{ $cart->product->name }}
                                    </a>
                                </td>
                                <td>${{ $cart->product->price }}</td>

                                <td>
                                    <div class="form-group--number">
                                        <button class="minus"><span>-</span></button>
                                        <input class="form-control" type="text" name="qty"
                                            value="{{ $cart->quantity }}">
                                        <button class="plus"><span>+</span></button>
                                    </div>
                                </td>

                                <td>${{ $cart->product->price * $cart->product->quantity }}</td>

                                <td>
                                    <a href="{{ route('cart.delete',$cart->id) }}">
                                        <div class="ps-remove"></div>
                                    </a>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                <div class="ps-cart__actions">

                    <div class="ps-cart__promotion">

                        <div class="form-group">

                            <div class="ps-form--icon"><i class="fa fa-angle-right"></i>
                                <input class="form-control" type="text" placeholder="Promo Code">
                            </div>

                        </div>

                        <div class="form-group">
                            <a href="{{ route('home') }}" class="ps-btn ps-btn--gray">Continue Shopping</a>
                        </div>



                    </div>

                    <div class="ps-cart__total">
                        <h3>Total Price: <span> {{ $total }}$</span></h3>

                        <a class="ps-btn" href="{{ route('checkout') }}">
                            Process to checkout
                            <i class="ps-icon-next"></i>
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-store-front-layout>
