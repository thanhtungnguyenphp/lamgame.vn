{{-- Lemon.js for checkout overlay --}}
<script src="https://app.lemonsqueezy.com/js/lemon.js" defer></script>

<script
    type="text/x-template"
    id="v-lemon-squeezy-button-template"
>
        <div class="w-full">
            <button
                type="button"
                class="primary-button w-full rounded-2xl bg-[#7c3aed] px-11 py-3 text-center text-white hover:bg-[#6d28d9] max-md:mb-4 max-md:w-full max-md:max-w-full max-md:rounded-lg max-sm:py-1.5"
                :disabled="isProcessing || !lemonReady"
                @click="startCheckout"
            >
                <span v-if="isProcessing">
                    <svg class="mr-2 inline h-4 w-4 animate-spin" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Đang xử lý...
                </span>
                <span v-else-if="!lemonReady">
                    Đang tải cổng thanh toán...
                </span>
                <span v-else>
                    💳 Thanh toán quốc tế (Visa, MC, PayPal)
                </span>
            </button>

            <p class="mt-2 text-center text-xs text-zinc-500">
                Powered by Lemon Squeezy — Apple Pay, Google Pay
            </p>
        </div>
    </script>

    <script type="module">
        app.component('v-lemon-squeezy-button', {
            template: '#v-lemon-squeezy-button-template',

            data() {
                return {
                    isProcessing: false,
                    lemonReady: false,
                };
            },

            mounted() {
                this.waitForLemonJs();
            },

            methods: {
                waitForLemonJs() {
                    const check = () => {
                        if (window.LemonSqueezy) {
                            this.setupEventHandler();
                            this.lemonReady = true;
                        } else {
                            setTimeout(check, 200);
                        }
                    };
                    check();
                },

                setupEventHandler() {
                    window.createLemonSqueezy?.();

                    window.LemonSqueezy.Setup({
                        eventHandler: (event) => {
                            if (event.event === 'Checkout.Success') {
                                window.location.href = "{{ route('lemonsqueezy.checkout.success') }}";
                            }
                        },
                    });
                },

                async startCheckout() {
                    this.isProcessing = true;

                    try {
                        const response = await this.$axios.post("{{ route('lemonsqueezy.checkout.create') }}");

                        const checkoutUrl = response.data.checkout_url;

                        if (! checkoutUrl) {
                            throw new Error('No checkout URL');
                        }

                        // LS-306: Mobile → redirect (overlay UX kém trên mobile)
                        const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent)
                            || window.innerWidth < 768;

                        if (!isMobile && window.LemonSqueezy) {
                            window.LemonSqueezy.Url.Open(checkoutUrl);
                        } else {
                            window.location.href = checkoutUrl;
                        }
                    } catch (error) {
                        const message = error.response?.data?.error || 'Có lỗi xảy ra. Vui lòng thử lại.';

                        this.$emitter.emit('add-flash', { type: 'error', message });
                    } finally {
                        this.isProcessing = false;
                    }
                },
            },
        });
    </script>
