<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Mặc định',
            ],

            'attribute-groups' => [
                'description'       => 'Mô tả',
                'general'           => 'Chung',
                'inventories'       => 'Tồn kho',
                'meta-description'  => 'Meta Description',
                'price'             => 'Giá',
                'settings'          => 'Cài đặt',
                'shipping'          => 'Vận chuyển',
            ],

            'attributes' => [
                'brand'                => 'Thương hiệu',
                'color'                => 'Màu sắc',
                'cost'                 => 'Giá gốc',
                'description'          => 'Mô tả',
                'featured'             => 'Nổi bật',
                'guest-checkout'       => 'Checkout không cần tài khoản',
                'height'               => 'Chiều cao',
                'length'               => 'Chiều dài',
                'manage-stock'         => 'Quản lý tồn kho',
                'meta-description'     => 'Meta Description',
                'meta-keywords'        => 'Meta Keywords',
                'meta-title'           => 'Meta Title',
                'name'                 => 'Tên',
                'new'                  => 'Mới',
                'price'                => 'Giá',
                'product-number'       => 'Mã sản phẩm',
                'short-description'    => 'Mô tả ngắn',
                'size'                 => 'Kích thước',
                'sku'                  => 'SKU',
                'special-price'        => 'Giá khuyến mãi',
                'special-price-from'   => 'Giá khuyến mãi từ',
                'special-price-to'     => 'Giá khuyến mãi đến',
                'status'               => 'Trạng thái',
                'tax-category'         => 'Danh mục thuế',
                'url-key'              => 'URL Key',
                'visible-individually' => 'Hiển thị riêng biệt',
                'weight'               => 'Trọng lượng',
                'width'                => 'Chiều rộng',
            ],

            'attribute-options' => [
                'black'  => 'Đen',
                'green'  => 'Xanh lá',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Đỏ',
                's'      => 'S',
                'white'  => 'Trắng',
                'xl'     => 'XL',
                'yellow' => 'Vàng',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Mô tả danh mục gốc',
                'name'        => 'Root',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Nội dung trang Về chúng tôi',
                    'title'   => 'Về chúng tôi',
                ],

                'contact-us' => [
                    'content' => 'Nội dung trang Liên hệ',
                    'title'   => 'Liên hệ',
                ],

                'customer-service' => [
                    'content' => 'Nội dung trang Dịch vụ khách hàng',
                    'title'   => 'Dịch vụ khách hàng',
                ],

                'payment-policy' => [
                    'content' => 'Nội dung trang Chính sách thanh toán',
                    'title'   => 'Chính sách thanh toán',
                ],

                'privacy-policy' => [
                    'content' => 'Nội dung trang Chính sách bảo mật',
                    'title'   => 'Chính sách bảo mật',
                ],

                'refund-policy' => [
                    'content' => 'Nội dung trang Chính sách hoàn tiền',
                    'title'   => 'Chính sách hoàn tiền',
                ],

                'return-policy' => [
                    'content' => 'Nội dung trang Chính sách đổi trả',
                    'title'   => 'Chính sách đổi trả',
                ],

                'shipping-policy' => [
                    'content' => 'Nội dung trang Chính sách vận chuyển',
                    'title'   => 'Chính sách vận chuyển',
                ],

                'terms-conditions' => [
                    'content' => 'Nội dung trang Điều khoản & Điều kiện',
                    'title'   => 'Điều khoản & Điều kiện',
                ],

                'terms-of-use' => [
                    'content' => 'Nội dung trang Điều khoản sử dụng',
                    'title'   => 'Điều khoản sử dụng',
                ],

                'whats-new' => [
                    'content' => 'Nội dung trang Có gì mới',
                    'title'   => 'Có gì mới',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Mặc định',
                'meta-title'       => 'Cửa hàng demo',
                'meta-keywords'    => 'Từ khóa meta cửa hàng demo',
                'meta-description' => 'Mô tả meta cửa hàng demo',
            ],

            'currencies' => [
                'AED' => 'Dirham Các Tiểu vương quốc Ả Rập Thống nhất',
                'ARS' => 'Peso Argentina',
                'AUD' => 'Đô la Úc',
                'BDT' => 'Taka Bangladesh',
                'BHD' => 'Dinar Bahrain',
                'BRL' => 'Real Brazil',
                'CAD' => 'Đô la Canada',
                'CHF' => 'Franc Thụy Sĩ',
                'CLP' => 'Peso Chile',
                'CNY' => 'Nhân dân tệ Trung Quốc',
                'COP' => 'Peso Colombia',
                'CZK' => 'Koruna Cộng hòa Séc',
                'DKK' => 'Krone Đan Mạch',
                'DZD' => 'Dinar Algeria',
                'EGP' => 'Bảng Ai Cập',
                'EUR' => 'Euro',
                'FJD' => 'Đô la Fiji',
                'GBP' => 'Bảng Anh',
                'HKD' => 'Đô la Hồng Kông',
                'HUF' => 'Forint Hungary',
                'IDR' => 'Rupiah Indonesia',
                'ILS' => 'Shekel mới Israel',
                'INR' => 'Rupee Ấn Độ',
                'JOD' => 'Dinar Jordan',
                'JPY' => 'Yên Nhật',
                'KRW' => 'Won Hàn Quốc',
                'KWD' => 'Dinar Kuwait',
                'KZT' => 'Tenge Kazakhstan',
                'LBP' => 'Bảng Lebanon',
                'LKR' => 'Rupee Sri Lanka',
                'LYD' => 'Dinar Libya',
                'MAD' => 'Dirham Morocco',
                'MUR' => 'Rupee Mauritius',
                'MXN' => 'Peso Mexico',
                'MYR' => 'Ringgit Malaysia',
                'NGN' => 'Naira Nigeria',
                'NOK' => 'Krone Na Uy',
                'NPR' => 'Rupee Nepal',
                'NZD' => 'Đô la New Zealand',
                'OMR' => 'Rial Oman',
                'PAB' => 'Balboa Panama',
                'PEN' => 'Sol mới Peru',
                'PHP' => 'Peso Philippines',
                'PKR' => 'Rupee Pakistan',
                'PLN' => 'Zloty Ba Lan',
                'PYG' => 'Guarani Paraguay',
                'QAR' => 'Rial Qatar',
                'RON' => 'Leu Romania',
                'RUB' => 'Ruble Nga',
                'SAR' => 'Riyal Ả Rập Saudi',
                'SEK' => 'Krona Thụy Điển',
                'SGD' => 'Đô la Singapore',
                'THB' => 'Baht Thái Lan',
                'TND' => 'Dinar Tunisia',
                'TRY' => 'Lira Thổ Nhĩ Kỳ',
                'TWD' => 'Đô la Đài Loan mới',
                'UAH' => 'Hryvnia Ukraine',
                'USD' => 'Đô la Mỹ',
                'UZS' => 'Som Uzbekistan',
                'VEF' => 'Bolívar Venezuela',
                'VND' => 'Đồng Việt Nam',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand Nam Phi',
                'ZMW' => 'Kwacha Zambia',
            ],

            'locales'    => [
                'ar'    => 'Tiếng Ả Rập',
                'bn'    => 'Tiếng Bengali',
                'ca'    => 'Tiếng Canada',
                'de'    => 'Tiếng Đức',
                'en'    => 'Tiếng Anh',
                'es'    => 'Tiếng Tây Ban Nha',
                'fa'    => 'Tiếng Ba Tư',
                'fr'    => 'Tiếng Pháp',
                'he'    => 'Tiếng Hebrew',
                'hi_IN' => 'Tiếng Hindi',
                'it'    => 'Tiếng Ý',
                'ja'    => 'Tiếng Nhật',
                'nl'    => 'Tiếng Hà Lan',
                'pl'    => 'Tiếng Ba Lan',
                'pt_BR' => 'Tiếng Bồ Đào Nha (Brazil)',
                'ru'    => 'Tiếng Nga',
                'sin'   => 'Tiếng Sinhala',
                'tr'    => 'Tiếng Thổ Nhĩ Kỳ',
                'uk'    => 'Tiếng Ukraine',
                'vi'    => 'Tiếng Việt',
                'zh_CN' => 'Tiếng Trung Quốc',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Chung',
                'guest'     => 'Khách',
                'wholesale' => 'Bán sỉ',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Mặc định',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tất cả sản phẩm',

                    'options' => [
                        'title' => 'Tất cả sản phẩm',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Xem bộ sưu tập',
                        'description' => 'Giới thiệu bộ sưu tập táo bạo mới của chúng tôi! Nâng tầm phong cách của bạn với thiết kế táo bạo và tuyên bố sống động. Khám phá các mẫu nổi bật và màu sắc táo bạo để định nghĩa lại tủ đồ của bạn. Hãy sẵn sàng đón nhận điều phi thường!',
                        'title'       => 'Sẵn sàng cho bộ sưu tập táo bạo mới của chúng tôi!',
                    ],

                    'name' => 'Bộ sưu tập táo bạo',
                ],

                'categories-collections' => [
                    'name' => 'Bộ sưu tập danh mục',
                ],

                'featured-collections' => [
                    'name' => 'Bộ sưu tập nổi bật',

                    'options' => [
                        'title' => 'Sản phẩm nổi bật',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liên kết footer',

                    'options' => [
                        'about-us'         => 'Về chúng tôi',
                        'contact-us'       => 'Liên hệ',
                        'customer-service' => 'Dịch vụ khách hàng',
                        'payment-policy'   => 'Chính sách thanh toán',
                        'privacy-policy'   => 'Chính sách bảo mật',
                        'refund-policy'    => 'Chính sách hoàn tiền',
                        'return-policy'    => 'Chính sách đổi trả',
                        'shipping-policy'  => 'Chính sách vận chuyển',
                        'terms-conditions' => 'Điều khoản & Điều kiện',
                        'terms-of-use'     => 'Điều khoản sử dụng',
                        'whats-new'        => 'Có gì mới',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-2' => 'Bộ sưu tập của chúng tôi',
                        'title'       => 'Trò chơi với những bổ sung mới của chúng tôi!',
                    ],

                    'name' => 'Game Container',
                ],

                'image-carousel' => [
                    'name' => 'Băng chuyền hình ảnh',

                    'sliders' => [
                        'title' => 'Sẵn sàng cho bộ sưu tập mới',
                    ],
                ],

                'new-products' => [
                    'name' => 'Sản phẩm mới',

                    'options' => [
                        'title' => 'Sản phẩm mới',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'GIẢM TỚI 40% cho đơn hàng đầu tiên MUA NGAY',
                    ],

                    'name' => 'Thông tin ưu đãi',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'EMI miễn phí khả dụng trên tất cả thẻ tín dụng chính',
                        'free-shipping-info'   => 'Miễn phí vận chuyển cho tất cả đơn hàng',
                        'product-replace-info' => 'Dễ dàng thay thế sản phẩm!',
                        'time-support-info'    => 'Hỗ trợ 24/7 chuyên dụng qua chat và email',
                    ],

                    'name' => 'Nội dung dịch vụ',

                    'title' => [
                        'emi-available'   => 'EMI khả dụng',
                        'free-shipping'   => 'Miễn phí vận chuyển',
                        'product-replace' => 'Thay thế sản phẩm',
                        'time-support'    => 'Hỗ trợ 24/7',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-2' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-3' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-4' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-5' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-6' => 'Bộ sưu tập của chúng tôi',
                        'title'       => 'Trò chơi với những bổ sung mới của chúng tôi!',
                    ],

                    'name' => 'Bộ sưu tập hàng đầu',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'Người dùng với vai trò này sẽ có tất cả quyền truy cập',
                'name'        => 'Quản trị viên',
            ],

            'users' => [
                'name' => 'Ví dụ',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'Quản trị',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'Xác nhận mật khẩu',
                'email'            => 'Email',
                'email-address'    => 'admin@example.com',
                'password'         => 'Mật khẩu',
                'title'            => 'Tạo tài khoản quản trị',
            ],

            'environment-configuration' => [
                'title'           => 'Cấu hình cửa hàng',
                'bagisto'         => 'Bagisto',
                'default-locale'  => 'Ngôn ngữ mặc định',
                'default-timezone' => 'Múi giờ mặc định',
                'default-currency' => 'Tiền tệ mặc định',
            ],

            'installation-processing' => [
                'bagisto'      => 'Cài đặt Bagisto',
                'bagisto-info' => 'Tạo bảng cơ sở dữ liệu, có thể mất vài phút',
                'title'        => 'Cài đặt',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Bảng điều khiển quản trị',
                'bagisto-forums'             => 'Diễn đàn Bagisto',
                'customer-panel'             => 'Bảng điều khiển khách hàng',
                'explore-bagisto-extensions' => 'Khám phá tiện ích mở rộng Bagisto',
                'title'                      => 'Cài đặt hoàn tất',
                'title-info'                 => 'Bagisto đã được cài đặt thành công trên hệ thống của bạn.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Tạo bảng cơ sở dữ liệu',
                'install'                 => 'Cài đặt',
                'install-info'            => 'Bagisto để cài đặt',
                'install-info-button'     => 'Nhấp vào nút bên dưới để',
                'populate-database-table' => 'Điền dữ liệu vào bảng cơ sở dữ liệu',
                'start-installation'      => 'Bắt đầu cài đặt',
                'title'                   => 'Sẵn sàng cài đặt',
            ],

            'start' => [
                'locale'        => 'Ngôn ngữ',
                'main'          => 'Bắt đầu',
                'select-locale' => 'Chọn ngôn ngữ',
                'title'         => 'Cài đặt Bagisto của bạn',
                'welcome-title' => 'Chào mừng đến với Bagisto',
            ],

            'server-requirements' => [
                'title'       => 'Yêu cầu hệ thống',
                'php'         => 'PHP',
                'php-version' => '8.1 trở lên',
            ],

            'arabic'                   => 'Tiếng Ả Rập',
            'back'                     => 'Quay lại',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'một dự án cộng đồng bởi',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Tiếng Bengali',
            'chinese'                  => 'Tiếng Trung',
            'continue'                 => 'Tiếp tục',
            'dutch'                    => 'Tiếng Hà Lan',
            'english'                  => 'Tiếng Anh',
            'french'                   => 'Tiếng Pháp',
            'german'                   => 'Tiếng Đức',
            'hebrew'                   => 'Tiếng Hebrew',
            'hindi'                    => 'Tiếng Hindi',
            'installation-description' => 'Cài đặt Bagisto thường bao gồm nhiều bước. Đây là tổng quan chung về quy trình cài đặt Bagisto',
            'installation-info'        => 'Chúng tôi rất vui khi thấy bạn ở đây!',
            'installation-title'       => 'Chào mừng đến với cài đặt',
            'italian'                  => 'Tiếng Ý',
            'japanese'                 => 'Tiếng Nhật',
            'persian'                  => 'Tiếng Ba Tư',
            'polish'                   => 'Tiếng Ba Lan',
            'portuguese'               => 'Tiếng Bồ Đào Nha Brazil',
            'russian'                  => 'Tiếng Nga',
            'sinhala'                  => 'Tiếng Sinhala',
            'spanish'                  => 'Tiếng Tây Ban Nha',
            'title'                    => 'Trình cài đặt Bagisto',
            'turkish'                  => 'Tiếng Thổ Nhĩ Kỳ',
            'ukrainian'                => 'Tiếng Ukraine',
            'vietnamese'               => 'Tiếng Việt',
            'webkul'                   => 'Webkul',
        ],
    ],
];
