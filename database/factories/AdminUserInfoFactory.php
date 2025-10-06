<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AdminUserInfo;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminUserInfo>
 */
class AdminUserInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminUserInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vietnameseCities = [
            'Ho Chi Minh City', 'Hanoi', 'Da Nang', 'Hai Phong', 'Can Tho',
            'Bien Hoa', 'Hue', 'Nha Trang', 'Buon Ma Thuot', 'Quy Nhon'
        ];

        $vietnameseStates = [
            'Ho Chi Minh', 'Ha Noi', 'Da Nang', 'Hai Phong', 'Can Tho',
            'Thua Thien Hue', 'Khanh Hoa', 'Dong Nai', 'Binh Duong', 'Ba Ria-Vung Tau'
        ];

        $jobTitles = [
            'Software Engineer', 'Senior Developer', 'Product Manager', 'UI/UX Designer',
            'DevOps Engineer', 'Data Analyst', 'Marketing Manager', 'Sales Director',
            'Business Analyst', 'Project Manager', 'Team Lead', 'CTO', 'CEO'
        ];

        $companies = [
            'TechViet Solutions', 'Saigon Technology', 'FPT Software', 'VNG Corporation',
            'Tiki', 'Shopee Vietnam', 'Grab Vietnam', 'Momo', 'VinTech', 'Base.vn',
            'Framgia Vietnam', 'KMS Technology', 'ELCA Vietnam', 'Golden Owl'
        ];

        $socialPlatforms = ['facebook', 'linkedin', 'twitter', 'instagram', 'youtube', 'tiktok'];
        
        $socialLinks = [];
        foreach ($socialPlatforms as $platform) {
            if ($this->faker->boolean(40)) { // 40% chance of having each social link
                $socialLinks[$platform] = "https://{$platform}.com/" . $this->faker->userName;
            }
        }

        return [
            // Personal Information
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'phone' => $this->generateVietnamesePhone(),
            
            // Address Information
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->randomElement($vietnameseCities),
            'state' => $this->faker->randomElement($vietnameseStates),
            'country' => 'Vietnam',
            'postal_code' => $this->faker->randomNumber(6, true),
            
            // Professional Information
            'bio' => $this->faker->paragraph(3),
            'website' => $this->faker->boolean(30) ? $this->faker->url : null,
            'job_title' => $this->faker->randomElement($jobTitles),
            'company' => $this->faker->randomElement($companies),
            
            // Social Links
            'social_links' => empty($socialLinks) ? null : $socialLinks,
            
            // User Preferences
            'preferences' => [
                'language' => $this->faker->randomElement(['vi', 'en']),
                'timezone' => 'Asia/Ho_Chi_Minh',
                'date_format' => $this->faker->randomElement(['d/m/Y', 'm/d/Y', 'Y-m-d']),
                'time_format' => $this->faker->randomElement(['H:i', 'h:i A']),
                'notifications' => [
                    'email' => $this->faker->boolean(80),
                    'push' => $this->faker->boolean(70),
                    'sms' => $this->faker->boolean(20),
                ],
                'privacy' => [
                    'show_phone' => $this->faker->boolean(30),
                    'show_email' => $this->faker->boolean(60),
                    'show_address' => $this->faker->boolean(20),
                ]
            ],
            
            // Emergency Contact
            'emergency_contact' => [
                'name' => $this->faker->name,
                'phone' => $this->generateVietnamesePhone(),
                'relationship' => $this->faker->randomElement([
                    'Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Relative'
                ]),
            ],
            
            // Custom Fields (for extensibility)
            'custom_fields' => $this->faker->boolean(20) ? [
                'linkedin_verified' => $this->faker->boolean(),
                'years_of_experience' => $this->faker->numberBetween(1, 20),
                'programming_languages' => $this->faker->randomElements(
                    ['PHP', 'JavaScript', 'Python', 'Java', 'C#', 'Go', 'Ruby', 'Swift'],
                    $this->faker->numberBetween(1, 4)
                ),
            ] : null,
            
            // Status Fields
            'profile_completed_at' => $this->faker->boolean(60) ? $this->faker->dateTimeThisYear : null,
            'is_public' => $this->faker->boolean(25),
            
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Generate a Vietnamese phone number.
     */
    private function generateVietnamesePhone(): string
    {
        $prefixes = ['090', '091', '092', '093', '094', '095', '096', '097', '098', '099',
                    '070', '076', '077', '078', '079', '081', '082', '083', '084', '085', '088'];
        
        $prefix = $this->faker->randomElement($prefixes);
        $suffix = $this->faker->numerify('#######');
        
        return $prefix . $suffix;
    }

    /**
     * Create a complete profile (all fields filled).
     */
    public function complete(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-20 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->generateVietnamesePhone(),
            'address' => $this->faker->streetAddress,
            'city' => 'Ho Chi Minh City',
            'state' => 'Ho Chi Minh',
            'bio' => $this->faker->paragraph(4),
            'website' => $this->faker->url,
            'job_title' => 'Senior Developer',
            'company' => 'Tech Company Vietnam',
            'profile_completed_at' => $this->faker->dateTimeThisYear,
        ]);
    }

    /**
     * Create a minimal profile (only required fields).
     */
    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $this->generateVietnamesePhone(),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', '-25 years'),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->randomElement(['Ho Chi Minh City', 'Hanoi', 'Da Nang']),
            // Other fields remain null
            'gender' => null,
            'state' => null,
            'bio' => null,
            'website' => null,
            'job_title' => null,
            'company' => null,
            'social_links' => null,
            'custom_fields' => null,
        ]);
    }

    /**
     * Create a public profile.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
            'preferences' => array_merge(
                $attributes['preferences'] ?? [],
                [
                    'privacy' => [
                        'show_phone' => true,
                        'show_email' => true,
                        'show_address' => false,
                    ]
                ]
            ),
        ]);
    }
}
