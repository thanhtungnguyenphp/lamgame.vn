<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    <div class="mx-4">
        <x-shop::layouts.account.navigation />
    </div>

    <span class="mb-5 mt-2 w-full border-t border-zinc-300"></span>

    <!--Customers logout-->
    @auth('customer')
        <div class="mx-4">
            <div class="mx-auto w-[400px] rounded-lg border border-navyBlue py-2.5 text-center max-sm:w-full max-sm:py-1.5">
                <form
                    method="POST"
                    action="{{ route('shop.customer.session.destroy') }}"
                    id="customerLogout"
                    style="margin:0"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-1.5 text-base hover:bg-gray-100"
                        style="background:none;border:none;cursor:pointer;padding:4px 0;color:inherit"
                    >
                        @lang('shop::app.components.layouts.header.logout')
                    </button>
                </form>
            </div>
        </div>
    @endauth

</x-shop::layouts.accounts>