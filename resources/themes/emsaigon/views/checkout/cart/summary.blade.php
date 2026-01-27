<div class="w-[380px] max-w-full rounded-xl border border-zinc-200 bg-zinc-50 p-6 max-md:w-full max-md:p-4">
    <!-- DEBUG: emsaigon/checkout/cart/summary.blade.php -->
    <p class="text-xl font-semibold max-md:text-base">
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </p>

    <div class="mt-5 grid gap-3 max-md:mt-3 max-md:gap-2.5">
        <!-- Sub Total -->
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm">
                @lang('shop::app.checkout.cart.summary.sub-total')
            </p>
            <p class="text-base font-medium max-sm:text-sm">
                @{{ cart.formatted_sub_total }}
            </p>
        </div>

        <!-- Tax -->
        <div class="flex justify-between text-right">
            <p class="text-base max-sm:text-sm">
                @lang('shop::app.checkout.cart.summary.tax')
            </p>
            <p class="text-base font-medium max-sm:text-sm">
                @{{ cart.formatted_tax_total }}
            </p>
        </div>

        <!-- Grand Total -->
        <div class="flex justify-between text-right border-t border-zinc-200 pt-3 mt-2">
            <p class="text-lg font-semibold max-md:text-base">
                @lang('shop::app.checkout.cart.summary.grand-total')
            </p>
            <p class="text-lg font-semibold max-md:text-base">
                @{{ cart.formatted_grand_total }}
            </p>
        </div>

        <!-- Buttons -->
        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="primary-button mt-4 w-full rounded-2xl px-11 py-3 text-center max-md:rounded-lg max-md:py-3 max-md:text-sm"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>

        <a
            href="{{ route('shop.home.index') }}"
            class="secondary-button mt-2 w-full rounded-2xl px-11 py-3 text-center max-md:rounded-lg max-md:py-3 max-md:text-sm"
        >
            @lang('shop::app.checkout.cart.index.continue-shopping')
        </a>
    </div>
</div>
