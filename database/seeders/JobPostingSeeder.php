<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeGroup;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;
use Webkul\Attribute\Models\AttributeTranslation;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
// Direct DB insert for product_categories
use DB;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createJobCategories();
        $this->createJobAttributes();
        // $this->createSampleJobPostings(); // Skip to avoid duplicates
    }

    /**
     * Create job-related categories
     */
    private function createJobCategories()
    {
        $viLocale = Locale::where('code', 'vi')->first();
        $channel = Channel::first();

        // Job categories structure for game industry recruitment
        $categories = [
            'viec-lam' => [
                'name_vi' => 'Việc Làm',
                'name_en' => 'Jobs',
                'description_vi' => 'Cơ hội việc làm trong ngành game development',
                'description_en' => 'Job opportunities in game development industry',
                'children' => [
                    'game-programming' => [
                        'name_vi' => 'Lập Trình Game',
                        'name_en' => 'Game Programming',
                        'description_vi' => 'Vị trí lập trình viên game, engine developer',
                        'description_en' => 'Game programmer, engine developer positions'
                    ],
                    'game-design' => [
                        'name_vi' => 'Thiết Kế Game',
                        'name_en' => 'Game Design',
                        'description_vi' => 'Game designer, level designer, gameplay designer',
                        'description_en' => 'Game designer, level designer, gameplay designer'
                    ],
                    'game-art' => [
                        'name_vi' => 'Game Art & Graphics',
                        'name_en' => 'Game Art & Graphics',
                        'description_vi' => '2D/3D artist, UI/UX designer, animator',
                        'description_en' => '2D/3D artist, UI/UX designer, animator'
                    ],
                    'qa-testing' => [
                        'name_vi' => 'QA & Testing',
                        'name_en' => 'QA & Testing',
                        'description_vi' => 'Game tester, QA engineer, bug hunter',
                        'description_en' => 'Game tester, QA engineer, bug hunter'
                    ],
                    'project-management' => [
                        'name_vi' => 'Quản Lý Dự Án',
                        'name_en' => 'Project Management',
                        'description_vi' => 'Project manager, producer, scrum master',
                        'description_en' => 'Project manager, producer, scrum master'
                    ],
                    'marketing-publishing' => [
                        'name_vi' => 'Marketing & Publishing',
                        'name_en' => 'Marketing & Publishing',
                        'description_vi' => 'Game marketing, community manager, publisher',
                        'description_en' => 'Game marketing, community manager, publisher'
                    ],
                    'mobile-game' => [
                        'name_vi' => 'Mobile Game',
                        'name_en' => 'Mobile Game',
                        'description_vi' => 'Phát triển game mobile Android/iOS',
                        'description_en' => 'Mobile game development Android/iOS'
                    ],
                    'web-game' => [
                        'name_vi' => 'Web Game',
                        'name_en' => 'Web Game',
                        'description_vi' => 'Phát triển game web, HTML5, browser game',
                        'description_en' => 'Web game development, HTML5, browser game'
                    ],
                    'freelance' => [
                        'name_vi' => 'Freelance',
                        'name_en' => 'Freelance',
                        'description_vi' => 'Công việc freelance, part-time, remote',
                        'description_en' => 'Freelance, part-time, remote work'
                    ],
                    'internship' => [
                        'name_vi' => 'Thực Tập',
                        'name_en' => 'Internship',
                        'description_vi' => 'Vị trí thực tập sinh game development',
                        'description_en' => 'Game development internship positions'
                    ]
                ]
            ]
        ];

        $this->createCategoryTree($categories, 1); // Parent ID = 1 (Root)
    }

    /**
     * Recursively create category tree
     */
    private function createCategoryTree($categories, $parentId)
    {
        $viLocale = Locale::where('code', 'vi')->first();
        
        foreach ($categories as $slug => $categoryData) {
            // Create category
            $category = Category::create([
                'parent_id' => $parentId,
                'position' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Vietnamese translation - use slug as url_path for simplicity
            CategoryTranslation::create([
                'category_id' => $category->id,
                'name' => $categoryData['name_vi'],
                'slug' => $slug,
                'url_path' => $slug,
                'description' => $categoryData['description_vi'],
                'meta_title' => $categoryData['name_vi'],
                'meta_description' => $categoryData['description_vi'],
                'meta_keywords' => $categoryData['name_vi'],
                'locale_id' => $viLocale->id,
            ]);

            // Create children if exists
            if (isset($categoryData['children'])) {
                $this->createCategoryTree($categoryData['children'], $category->id);
            }
        }
    }

    /**
     * Create job-specific product attributes
     */
    private function createJobAttributes()
    {
        $viLocale = Locale::where('code', 'vi')->first();

        // Get default attribute family
        $attributeFamily = \Webkul\Attribute\Models\AttributeFamily::where('code', 'default')->first();
        
        // Get or create attribute groups for job postings
        $jobInfoGroup = AttributeGroup::firstOrCreate(
            ['code' => 'job_info', 'attribute_family_id' => $attributeFamily->id],
            [
                'name' => 'Job Information',
                'column' => 1,
                'is_user_defined' => 1,
                'position' => 20,
            ]
        );

        $jobRequirementsGroup = AttributeGroup::firstOrCreate(
            ['code' => 'job_requirements', 'attribute_family_id' => $attributeFamily->id],
            [
                'name' => 'Job Requirements',
                'column' => 2,
                'is_user_defined' => 1,
                'position' => 21,
            ]
        );

        $jobBenefitsGroup = AttributeGroup::firstOrCreate(
            ['code' => 'job_benefits', 'attribute_family_id' => $attributeFamily->id],
            [
                'name' => 'Benefits & Application',
                'column' => 3,
                'is_user_defined' => 1,
                'position' => 22,
            ]
        );

        // Job-specific attributes
        $jobAttributes = [
            // Job Information Group
            [
                'code' => 'job_type',
                'admin_name' => 'Job Type',
                'type' => 'select',
                'group_id' => $jobInfoGroup->id,
                'position' => 1,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Loại Hình Công Việc']
                ],
                'options' => [
                    ['vi' => 'Full-time'],
                    ['vi' => 'Part-time'],
                    ['vi' => 'Contract'],
                    ['vi' => 'Freelance'],
                    ['vi' => 'Internship'],
                    ['vi' => 'Remote'],
                    ['vi' => 'Hybrid']
                ]
            ],
            [
                'code' => 'experience_level',
                'admin_name' => 'Experience Level',
                'type' => 'select',
                'group_id' => $jobInfoGroup->id,
                'position' => 2,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Cấp Độ Kinh Nghiệm']
                ],
                'options' => [
                    ['vi' => 'Fresher (0-1 năm)'],
                    ['vi' => 'Junior (1-3 năm)'],
                    ['vi' => 'Middle (3-5 năm)'],
                    ['vi' => 'Senior (5+ năm)'],
                    ['vi' => 'Lead/Manager (7+ năm)'],
                    ['vi' => 'Director (10+ năm)']
                ]
            ],
            [
                'code' => 'salary_range',
                'admin_name' => 'Salary Range',
                'type' => 'select',
                'group_id' => $jobInfoGroup->id,
                'position' => 3,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Mức Lương']
                ],
                'options' => [
                    ['vi' => 'Dưới 10 triệu'],
                    ['vi' => '10-20 triệu'],
                    ['vi' => '20-30 triệu'],
                    ['vi' => '30-50 triệu'],
                    ['vi' => '50-80 triệu'],
                    ['vi' => 'Trên 80 triệu'],
                    ['vi' => 'Thỏa thuận']
                ]
            ],
            [
                'code' => 'job_location',
                'admin_name' => 'Job Location',
                'type' => 'select',
                'group_id' => $jobInfoGroup->id,
                'position' => 4,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Địa Điểm Làm Việc']
                ],
                'options' => [
                    ['vi' => 'Hồ Chí Minh'],
                    ['vi' => 'Hà Nội'],
                    ['vi' => 'Đà Nẵng'],
                    ['vi' => 'Cần Thơ'],
                    ['vi' => 'Biên Hòa'],
                    ['vi' => 'Nha Trang'],
                    ['vi' => 'Remote'],
                    ['vi' => 'Toàn Quốc']
                ]
            ],
            [
                'code' => 'company_size',
                'admin_name' => 'Company Size',
                'type' => 'select',
                'group_id' => $jobInfoGroup->id,
                'position' => 5,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Quy Mô Công Ty']
                ],
                'options' => [
                    ['vi' => 'Startup (1-10 người)'],
                    ['vi' => 'Nhỏ (10-50 người)'],
                    ['vi' => 'Trung bình (50-200 người)'],
                    ['vi' => 'Lớn (200-1000 người)'],
                    ['vi' => 'Tập đoàn (1000+ người)']
                ]
            ],
            
            // Job Requirements Group
            [
                'code' => 'required_skills',
                'admin_name' => 'Required Skills',
                'type' => 'multiselect',
                'group_id' => $jobRequirementsGroup->id,
                'position' => 6,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Kỹ Năng Yêu Cầu']
                ],
                'options' => [
                    ['vi' => 'Unity'],
                    ['vi' => 'Unreal Engine'],
                    ['vi' => 'C#'],
                    ['vi' => 'C++'],
                    ['vi' => 'JavaScript'],
                    ['vi' => 'Python'],
                    ['vi' => 'Java'],
                    ['vi' => 'Swift'],
                    ['vi' => 'Kotlin'],
                    ['vi' => 'HTML5/CSS3'],
                    ['vi' => 'React Native'],
                    ['vi' => 'Flutter'],
                    ['vi' => 'Photoshop'],
                    ['vi' => '3ds Max'],
                    ['vi' => 'Maya'],
                    ['vi' => 'Blender'],
                    ['vi' => 'Git'],
                    ['vi' => 'Agile/Scrum'],
                    ['vi' => 'Game Design'],
                    ['vi' => 'Level Design']
                ]
            ],
            [
                'code' => 'education_level',
                'admin_name' => 'Education Level',
                'type' => 'select',
                'group_id' => $jobRequirementsGroup->id,
                'position' => 7,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Trình Độ Học Vấn']
                ],
                'options' => [
                    ['vi' => 'Không yêu cầu'],
                    ['vi' => 'Trung cấp/Cao đẳng'],
                    ['vi' => 'Đại học'],
                    ['vi' => 'Thạc sĩ'],
                    ['vi' => 'Tiến sĩ']
                ]
            ],
            [
                'code' => 'english_level',
                'admin_name' => 'English Level',
                'type' => 'select',
                'group_id' => $jobRequirementsGroup->id,
                'position' => 8,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Trình Độ Tiếng Anh']
                ],
                'options' => [
                    ['vi' => 'Không yêu cầu'],
                    ['vi' => 'Cơ bản'],
                    ['vi' => 'Giao tiếp tốt'],
                    ['vi' => 'Thành thạo'],
                    ['vi' => 'Bản ngữ']
                ]
            ],

            // Benefits & Application Group
            [
                'code' => 'job_benefits',
                'admin_name' => 'Job Benefits',
                'type' => 'multiselect',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 9,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Phúc Lợi']
                ],
                'options' => [
                    ['vi' => 'Bảo hiểm sức khỏe'],
                    ['vi' => 'Bảo hiểm xã hội'],
                    ['vi' => 'Thưởng hiệu suất'],
                    ['vi' => 'Du lịch hàng năm'],
                    ['vi' => 'Nghỉ phép có lương'],
                    ['vi' => 'Đào tạo & phát triển'],
                    ['vi' => 'Làm việc từ xa'],
                    ['vi' => 'Giờ làm việc linh hoạt'],
                    ['vi' => 'Máy tính/laptop công ty'],
                    ['vi' => 'Phụ cấp ăn trua'],
                    ['vi' => 'Team building'],
                    ['vi' => 'Game room']
                ]
            ],
            [
                'code' => 'application_deadline',
                'admin_name' => 'Application Deadline',
                'type' => 'date',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 10,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Hạn Nộp Hồ Sơ']
                ]
            ],
            [
                'code' => 'contact_email',
                'admin_name' => 'Contact Email',
                'type' => 'text',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 11,
                'is_filterable' => 0,
                'translations' => [
                    'vi' => ['name' => 'Email Liên Hệ']
                ]
            ],
            [
                'code' => 'contact_phone',
                'admin_name' => 'Contact Phone',
                'type' => 'text',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 12,
                'is_filterable' => 0,
                'translations' => [
                    'vi' => ['name' => 'Số Điện Thoại']
                ]
            ],
            [
                'code' => 'company_website',
                'admin_name' => 'Company Website',
                'type' => 'text',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 13,
                'is_filterable' => 0,
                'translations' => [
                    'vi' => ['name' => 'Website Công Ty']
                ]
            ],
            [
                'code' => 'is_urgent',
                'admin_name' => 'Urgent Job',
                'type' => 'boolean',
                'group_id' => $jobInfoGroup->id,
                'position' => 14,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Tuyển Gấp']
                ]
            ],
            [
                'code' => 'is_featured',
                'admin_name' => 'Featured Job',
                'type' => 'boolean',
                'group_id' => $jobInfoGroup->id,
                'position' => 15,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Tin Nổi Bật']
                ]
            ],
            [
                'code' => 'application_method',
                'admin_name' => 'Application Method',
                'type' => 'select',
                'group_id' => $jobBenefitsGroup->id,
                'position' => 16,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Cách Thức Ứng Tuyển']
                ],
                'options' => [
                    ['vi' => 'Gửi email'],
                    ['vi' => 'Ứng tuyển online'],
                    ['vi' => 'Liên hệ trực tiếp'],
                    ['vi' => 'Qua website công ty']
                ]
            ]
        ];

        foreach ($jobAttributes as $attributeData) {
            // Check if attribute already exists
            $existingAttribute = Attribute::where('code', $attributeData['code'])->first();
            if ($existingAttribute) {
                continue; // Skip if already exists
            }

            // Create attribute
            $attribute = Attribute::create([
                'code' => $attributeData['code'],
                'admin_name' => $attributeData['admin_name'],
                'type' => $attributeData['type'],
                'position' => $attributeData['position'],
                'is_required' => 0,
                'is_unique' => 0,
                'is_filterable' => $attributeData['is_filterable'],
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
            ]);

            // Add attribute to group
            DB::table('attribute_group_mappings')->insert([
                'attribute_id' => $attribute->id,
                'attribute_group_id' => $attributeData['group_id'],
                'position' => $attributeData['position'],
            ]);

            // Create Vietnamese translation
            AttributeTranslation::create([
                'attribute_id' => $attribute->id,
                'name' => $attributeData['translations']['vi']['name'],
                'locale' => $viLocale->code,
            ]);

            // Create options for select/multiselect attributes
            if (in_array($attributeData['type'], ['select', 'multiselect']) && isset($attributeData['options'])) {
                foreach ($attributeData['options'] as $index => $optionData) {
                    $option = AttributeOption::create([
                        'attribute_id' => $attribute->id,
                        'sort_order' => $index + 1,
                    ]);

                    // Use Vietnamese label
                    AttributeOptionTranslation::create([
                        'attribute_option_id' => $option->id,
                        'locale' => $viLocale->code,
                        'label' => $optionData['vi'],
                    ]);
                }
            }
        }
    }

    /**
     * Create sample job postings
     */
    private function createSampleJobPostings()
    {
        $viLocale = Locale::where('code', 'vi')->first();
        $channel = Channel::first();
        
        // Get job categories
        $gameProgrammingCategory = Category::whereHas('translations', function($q) {
            $q->where('slug', 'game-programming');
        })->first();

        $gameDesignCategory = Category::whereHas('translations', function($q) {
            $q->where('slug', 'game-design');
        })->first();

        if (!$gameProgrammingCategory || !$gameDesignCategory) {
            echo "Job categories not found for sample postings.\n";
            return;
        }

        // Sample job postings
        $sampleJobs = [
            [
                'sku' => 'JOB_UNITY_DEV_001',
                'name' => 'Unity Developer - Game Studio ABC',
                'description' => '<h2>Mô tả công việc</h2>
                <p>Game Studio ABC đang tìm kiếm Unity Developer tài năng để tham gia phát triển các dự án game mobile hấp dẫn.</p>
                
                <h3>📋 Trách nhiệm chính:</h3>
                <ul>
                <li>🎮 Phát triển game mobile sử dụng Unity Engine</li>
                <li>💻 Viết code C# clean, tối ưu và dễ maintain</li>
                <li>🔧 Tối ưu hiệu năng game cho các thiết bị mobile</li>
                <li>🤝 Làm việc cùng team Art, Design để implement game features</li>
                <li>🐛 Debug và fix bugs trong quá trình phát triển</li>
                <li>📝 Viết documentation cho code và features</li>
                </ul>
                
                <h3>✅ Yêu cầu:</h3>
                <ul>
                <li>🎓 Tốt nghiệp Đại học chuyên ngành CNTT hoặc tương đương</li>
                <li>⭐ Tối thiểu 2 năm kinh nghiệm với Unity</li>
                <li>💡 Thành thạo C# và OOP</li>
                <li>📱 Kinh nghiệm phát triển mobile game (Android/iOS)</li>
                <li>🌐 Tiếng Anh giao tiếp tốt</li>
                <li>🎯 Đam mê game và công nghệ</li>
                </ul>
                
                <h3>🎁 Phúc lợi:</h3>
                <ul>
                <li>💰 Lương 20-30 triệu + thưởng dự án</li>
                <li>🏥 Bảo hiểm sức khỏe cao cấp</li>
                <li>🏖️ 15 ngày nghỉ phép/năm</li>
                <li>🎮 Game room & team building</li>
                <li>💻 Máy tính & thiết bị làm việc hiện đại</li>
                </ul>',
                'short_description' => 'Tuyển Unity Developer kinh nghiệm 2+ năm phát triển mobile game. Lương 20-30 triệu + thưởng.',
                'price' => 25000000, // 25 million VND (average salary)
                'weight' => 0,
                'status' => 1,
                'featured' => 1,
                'new' => 1,
                'visible_individually' => 1,
                'meta_title' => 'Unity Developer - Game Studio ABC | LamGame Jobs',
                'meta_description' => 'Cơ hội làm Unity Developer tại Game Studio ABC. Lương 20-30 triệu, môi trường chuyên nghiệp.',
                'meta_keywords' => 'unity developer, game programmer, mobile game, tuyển dụng',
                'category_ids' => [$gameProgrammingCategory->id],
                'attributes' => [
                    'job_type' => 'Full-time',
                    'experience_level' => 'Middle (3-5 năm)',
                    'salary_range' => '20-30 triệu',
                    'job_location' => 'Hồ Chí Minh',
                    'company_size' => 'Trung bình (50-200 người)',
                    'required_skills' => ['Unity', 'C#', 'Mobile Game'],
                    'education_level' => 'Đại học',
                    'english_level' => 'Giao tiếp tốt',
                    'job_benefits' => ['Bảo hiểm sức khỏe', 'Thưởng hiệu suất', 'Máy tính/laptop công ty'],
                    'application_deadline' => date('Y-m-d', strtotime('+30 days')),
                    'contact_email' => 'hr@gamestudioabc.com',
                    'contact_phone' => '0123456789',
                    'company_website' => 'https://gamestudioabc.com',
                    'is_urgent' => 1,
                    'is_featured' => 1,
                    'application_method' => 'Gửi email'
                ]
            ],
            [
                'sku' => 'JOB_GAME_DESIGNER_001', 
                'name' => 'Game Designer - Indie Game Team',
                'description' => '<h2>Về chúng tôi</h2>
                <p>Indie Game Team là studio độc lập chuyên tạo ra những game indie sáng tạo và độc đáo. Chúng tôi đang tìm kiếm Game Designer đam mê để cùng tạo nên những trải nghiệm game tuyệt vời.</p>
                
                <h3>🎯 Vai trò công việc:</h3>
                <ul>
                <li>🎮 Thiết kế gameplay mechanics và game systems</li>
                <li>📋 Viết game design document chi tiết</li>
                <li>🎨 Collaborating với team Art để tạo game assets</li>
                <li>⚖️ Balance game difficulty và progression</li>
                <li>🧪 Playtesting và iterate design dựa trên feedback</li>
                <li>📊 Phân tích metrics và player behavior</li>
                </ul>
                
                <h3>👨‍💻 Yêu cầu ứng viên:</h3>
                <ul>
                <li>🎓 Không yêu cầu bằng cấp cụ thể, ưu tiên kinh nghiệm</li>
                <li>⭐ 1-2 năm kinh nghiệm game design</li>
                <li>🎮 Đam mê chơi game nhiều thể loại</li>
                <li>💡 Tư duy logic và khả năng giải quyết vấn đề</li>
                <li>📝 Kỹ năng viết document và presentation</li>
                <li>🤝 Tinh thần teamwork và communication tốt</li>
                </ul>
                
                <h3>🌟 Điểm cộng:</h3>
                <ul>
                <li>💻 Biết sử dụng Unity, Construct 3 hoặc game engines</li>
                <li>🎨 Có kiến thức về UI/UX design</li>
                <li>📊 Hiểu về game analytics và monetization</li>
                <li>🌐 Portfolio game projects cá nhân</li>
                </ul>',
                'short_description' => 'Tuyển Game Designer cho indie game team. Junior level, đam mê game design, remote friendly.',
                'price' => 15000000, // 15 million VND
                'weight' => 0,
                'status' => 1,
                'featured' => 0,
                'new' => 1,
                'visible_individually' => 1,
                'meta_title' => 'Game Designer - Indie Game Team | LamGame Jobs',
                'meta_description' => 'Cơ hội trở thành Game Designer tại indie game team. Remote friendly, môi trường sáng tạo.',
                'meta_keywords' => 'game designer, indie game, game design, remote work',
                'category_ids' => [$gameDesignCategory->id],
                'attributes' => [
                    'job_type' => 'Remote',
                    'experience_level' => 'Junior (1-3 năm)',
                    'salary_range' => '10-20 triệu',
                    'job_location' => 'Remote',
                    'company_size' => 'Startup (1-10 người)',
                    'required_skills' => ['Game Design', 'Unity'],
                    'education_level' => 'Không yêu cầu',
                    'english_level' => 'Cơ bản',
                    'job_benefits' => ['Làm việc từ xa', 'Giờ làm việc linh hoạt', 'Đào tạo & phát triển'],
                    'application_deadline' => date('Y-m-d', strtotime('+21 days')),
                    'contact_email' => 'jobs@indiegameteam.vn',
                    'contact_phone' => '0987654321',
                    'company_website' => 'https://indiegameteam.vn',
                    'is_urgent' => 0,
                    'is_featured' => 0,
                    'application_method' => 'Ứng tuyển online'
                ]
            ]
        ];

        foreach ($sampleJobs as $jobData) {
            // Create job posting as product
            $job = Product::create([
                'sku' => $jobData['sku'],
                'type' => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create attribute values
            $this->createJobAttributeValues($job, $jobData, $viLocale->id, $channel->id);

            // Assign to categories
            foreach ($jobData['category_ids'] as $categoryId) {
                DB::table('product_categories')->insert([
                    'product_id' => $job->id,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }

    /**
     * Create job posting attribute values
     */
    private function createJobAttributeValues($job, $jobData, $localeId, $channelId)
    {
        $simpleAttributes = [
            'sku' => $jobData['sku'],
            'name' => $jobData['name'],
            'description' => $jobData['description'],
            'short_description' => $jobData['short_description'],
            'price' => $jobData['price'],
            'weight' => $jobData['weight'],
            'status' => $jobData['status'],
            'featured' => $jobData['featured'],
            'new' => $jobData['new'],
            'visible_individually' => $jobData['visible_individually'],
            'meta_title' => $jobData['meta_title'],
            'meta_description' => $jobData['meta_description'],
            'meta_keywords' => $jobData['meta_keywords'],
        ];

        foreach ($simpleAttributes as $code => $value) {
            $attribute = Attribute::where('code', $code)->first();
            if ($attribute) {
                ProductAttributeValue::create([
                    'product_id' => $job->id,
                    'attribute_id' => $attribute->id,
                    'channel' => 'default',
                    'locale' => 'vi',
                    'text_value' => in_array($attribute->type, ['price']) ? null : (string)$value,
                    'float_value' => in_array($attribute->type, ['price']) ? (float)$value : null,
                    'integer_value' => in_array($attribute->type, ['boolean']) ? (int)$value : null,
                    'boolean_value' => in_array($attribute->type, ['boolean']) ? (bool)$value : null,
                ]);
            }
        }

        // Handle job-specific attributes
        if (isset($jobData['attributes'])) {
            foreach ($jobData['attributes'] as $code => $value) {
                $attribute = Attribute::where('code', $code)->first();
                if ($attribute) {
                    if (in_array($attribute->type, ['select', 'multiselect'])) {
                        $values = is_array($value) ? $value : [$value];
                        $optionIds = [];
                        
                        foreach ($values as $val) {
                            $option = AttributeOption::whereHas('translations', function($q) use ($val) {
                                $q->where('label', $val);
                            })->where('attribute_id', $attribute->id)->first();
                            
                            if ($option) {
                                $optionIds[] = $option->id;
                            }
                        }
                        
                        if (!empty($optionIds)) {
                            ProductAttributeValue::create([
                                'product_id' => $job->id,
                                'attribute_id' => $attribute->id,
                                'channel' => 'default',
                                'locale' => 'vi',
                                'text_value' => implode(',', $optionIds),
                            ]);
                        }
                    } elseif ($attribute->type === 'boolean') {
                        ProductAttributeValue::create([
                            'product_id' => $job->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'vi',
                            'integer_value' => (int)$value,
                            'boolean_value' => (bool)$value,
                        ]);
                    } else {
                        ProductAttributeValue::create([
                            'product_id' => $job->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'vi',
                            'text_value' => (string)$value,
                        ]);
                    }
                }
            }
        }
    }
}
