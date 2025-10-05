{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer class="mt-9 bg-lightOrange max-sm:mt-10">
    <div class="flex justify-between gap-x-6 gap-y-8 p-[60px] max-1060:flex-col-reverse max-md:gap-5 max-md:p-8 max-sm:px-4 max-sm:py-5">
        <!-- Brand Section with Logo -->
        <div class="flex flex-col gap-4 max-1060:order-2">
            <div class="flex items-center gap-3">
                <x-logo size="50" variant="horizontal" 
                       class="h-[40px] max-h-[40px] w-auto footer-logo" 
                       alt="LamGame.vn - Nền tảng Game Development" />
            </div>
            <div class="text-sm text-zinc-600 max-w-[280px] max-1060:max-w-none">
                <p class="mb-2 font-medium text-navyBlue">LamGame.vn</p>
                <p class="mb-1">Nền tảng học lập trình game và phát triển ứng dụng hàng đầu Việt Nam.</p>
                <p class="text-xs">Cộng đồng developer game Việt Nam 🇻🇳</p>
            </div>
            <!-- Social Links -->
            <div class="flex gap-3 mt-2">
                <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Facebook">
                    <i class="fab fa-facebook-f text-xl"></i>
                </a>
                <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Youtube">
                    <i class="fab fa-youtube text-xl"></i>
                </a>
                <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Discord">
                    <i class="fab fa-discord text-xl"></i>
                </a>
                <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="GitHub">
                    <i class="fab fa-github text-xl"></i>
                </a>
            </div>
        </div>
        
        <!-- For Desktop View -->
        <div class="flex flex-wrap items-start gap-24 max-1180:gap-6 max-1060:hidden max-1060:order-1">
            @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                    <ul class="grid gap-5 text-sm">
                        @php
                            usort($footerLinkSection, function ($a, $b) {
                                return $a['sort_order'] - $b['sort_order'];
                            });
                        @endphp

                        @foreach ($footerLinkSection as $link)
                            <li>
                                <a href="{{ $link['url'] }}" 
                                   class="hover:text-navyBlue transition-colors duration-200">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @else
                <!-- Default Vietnamese Footer Links -->
                <ul class="grid gap-5 text-sm">
                    <li><strong class="text-navyBlue">Sản phẩm</strong></li>
                    <li><a href="{{ route('lamgame.source-game') }}" class="hover:text-navyBlue transition-colors">Source Game</a></li>
                    <li><a href="{{ route('lamgame.blog') }}" class="hover:text-navyBlue transition-colors">Blog</a></li>
                    <li><a href="{{ route('forum.index') }}" class="hover:text-navyBlue transition-colors">Diễn đàn</a></li>
                    <li><a href="{{ route('lamgame.viec-lam-game') }}" class="hover:text-navyBlue transition-colors">Việc làm Game</a></li>
                </ul>
                <ul class="grid gap-5 text-sm">
                    <li><strong class="text-navyBlue">Hỗ trợ</strong></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Liên hệ</a></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Trợ giúp</a></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Câu hỏi thường gặp</a></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Báo lỗi</a></li>
                </ul>
                <ul class="grid gap-5 text-sm">
                    <li><strong class="text-navyBlue">Pháp lý</strong></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Điều khoản sử dụng</a></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Chính sách bảo mật</a></li>
                    <li><a href="#" class="hover:text-navyBlue transition-colors">Quy định cộng đồng</a></li>
                </ul>
            @endif
        </div>

        <!-- For Mobile view -->
        <div class="hidden max-1060:block max-1060:order-1 w-full">
            <!-- Mobile Brand Section -->
            <div class="flex flex-col items-center text-center mb-6 max-sm:mb-4">
                <x-logo size="60" variant="horizontal" 
                       class="h-[35px] max-h-[35px] w-auto mb-3" 
                       alt="LamGame.vn - Nền tảng Game Development" />
                <p class="text-sm text-zinc-600 max-w-[300px]">
                    Nền tảng học lập trình game và phát triển ứng dụng hàng đầu Việt Nam.
                </p>
                <!-- Mobile Social Links -->
                <div class="flex gap-4 mt-3">
                    <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Facebook">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Youtube">
                        <i class="fab fa-youtube text-lg"></i>
                    </a>
                    <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="Discord">
                        <i class="fab fa-discord text-lg"></i>
                    </a>
                    <a href="#" class="text-navyBlue hover:text-zinc-800 transition-colors" aria-label="GitHub">
                        <i class="fab fa-github text-lg"></i>
                    </a>
                </div>
            </div>
            
            <x-shop::accordion
                :is-active="false"
                class="!w-full rounded-xl !border-2 !border-[#e9decc] max-sm:rounded-lg"
            >
                <x-slot:header class="rounded-t-lg bg-[#F1EADF] font-medium max-md:p-2.5 max-sm:px-3 max-sm:py-2 max-sm:text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sitemap text-navyBlue"></i>
                        <span>Menu trang web</span>
                    </div>
                </x-slot>

                <x-slot:content class="!bg-transparent !p-4">
                    <div class="grid grid-cols-2 gap-4 max-sm:grid-cols-1">
                        @if ($customization?->options)
                            @foreach ($customization->options as $footerLinkSection)
                                <ul class="grid gap-3 text-sm">
                                    @php
                                        usort($footerLinkSection, function ($a, $b) {
                                            return $a['sort_order'] - $b['sort_order'];
                                        });
                                    @endphp

                                    @foreach ($footerLinkSection as $link)
                                        <li>
                                            <a
                                                href="{{ $link['url'] }}"
                                                class="text-sm font-medium hover:text-navyBlue transition-colors max-sm:text-xs">
                                                <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>
                                                {{ $link['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        @else
                            <!-- Default Mobile Links -->
                            <ul class="grid gap-3 text-sm">
                                <li><strong class="text-navyBlue text-xs uppercase tracking-wide">Sản phẩm</strong></li>
                                <li><a href="{{ route('lamgame.source-game') }}" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Source Game
                                </a></li>
                                <li><a href="{{ route('lamgame.blog') }}" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Blog
                                </a></li>
                                <li><a href="{{ route('forum.index') }}" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Diễn đàn
                                </a></li>
                                <li><a href="{{ route('lamgame.viec-lam-game') }}" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Việc làm Game
                                </a></li>
                            </ul>
                            <ul class="grid gap-3 text-sm">
                                <li><strong class="text-navyBlue text-xs uppercase tracking-wide">Hỗ trợ</strong></li>
                                <li><a href="#" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Liên hệ
                                </a></li>
                                <li><a href="#" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>Trợ giúp
                                </a></li>
                                <li><a href="#" class="hover:text-navyBlue transition-colors text-xs">
                                    <i class="fas fa-angle-right text-xs mr-2 text-zinc-400"></i>FAQ
                                </a></li>
                            </ul>
                        @endif
                    </div>
                </x-slot>
            </x-shop::accordion>
        </div>

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="grid gap-2.5">
                <p
                    class="max-w-[288px] text-3xl italic leading-[45px] text-navyBlue max-md:text-2xl max-sm:text-lg"
                    role="heading"
                    aria-level="2"
                >
                    @lang('shop::app.components.layouts.footer.newsletter-text')
                </p>

                <p class="text-xs">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <div>
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="mt-2.5 rounded max-sm:mt-0"
                    >
                        <div class="relative w-full">
                            <x-shop::form.control-group.control
                                type="email"
                                class="block w-[420px] max-w-full rounded-xl border-2 border-[#e9decc] bg-[#F1EADF] px-5 py-4 text-base max-1060:w-full max-md:p-3.5 max-sm:mb-0 max-sm:rounded-lg max-sm:border-2 max-sm:p-2 max-sm:text-sm"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                            />
    
                            <x-shop::form.control-group.error control-name="email" />
    
                            <button
                                type="submit"
                                class="absolute top-1.5 flex w-max items-center rounded-xl bg-white px-7 py-2.5 font-medium hover:bg-zinc-100 max-md:top-1 max-md:px-5 max-md:text-xs max-sm:mt-0 max-sm:rounded-lg max-sm:px-4 max-sm:py-2 ltr:right-2 rtl:left-2"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <div class="flex justify-between bg-[#F1EADF] px-[60px] py-3.5 max-md:justify-center max-sm:px-5">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="text-sm text-zinc-600 max-md:text-center">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
