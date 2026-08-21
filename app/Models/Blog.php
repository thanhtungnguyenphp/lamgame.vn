<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT     = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED  = 'archived';

    const VALID_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SCHEDULED,
        self::STATUS_PUBLISHED,
        self::STATUS_ARCHIVED,
    ];

    protected $table = 'blogs';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'channels',
        'default_category',
        'categorys',
        'tags',
        'author',
        'author_id',
        'src',
        'locale',
        'status',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Scope for published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('published_at', '<=', now());
    }

    /**
     * Get the author that owns the blog (E-E-A-T)
     */
    public function authorModel()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Get the category that owns the blog
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'default_category');
    }

    /**
     * Get all categories for this blog (from categorys field)
     */
    public function getCategories()
    {
        if (!$this->categorys) {
            return collect();
        }
        
        $categoryIds = explode(',', $this->categorys);
        return BlogCategory::whereIn('id', $categoryIds)->get();
    }

    /**
     * Get all tags for this blog (from tags field)
     */
    public function getTags()
    {
        if (!$this->tags) {
            return collect();
        }
        
        $tagIds = explode(',', $this->tags);
        return BlogTag::whereIn('id', $tagIds)->get();
    }

    /**
     * Get featured image URL
     */
    public function getFeaturedImageAttribute()
    {
        if (!$this->src) {
            return 'https://via.placeholder.com/800x400?text=' . urlencode($this->name);
        }

        // Handle old format paths that start with /storage/ or storage/
        if (str_starts_with($this->src, '/storage/') || str_starts_with($this->src, 'storage/')) {
            // Remove leading slash and storage/ prefix to avoid double storage path
            $cleanPath = ltrim($this->src, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8); // Remove 'storage/' prefix
            }
            return asset('storage/' . $cleanPath);
        }

        // Handle new format paths that are just the relative path within storage
        return asset('storage/' . $this->src);
    }

    /**
     * Extract FAQ items from blog content (looks for FAQ section with Q&A pattern)
     */
    public function extractFaqs(): array
    {
        $content = $this->description ?? '';
        $faqs = [];

        // Match patterns: <strong>Q: ...</strong> followed by answer text, or <h3>question</h3><p>answer</p>
        // Pattern 1: FAQ with "Hỏi:" or "Q:" prefix
        if (preg_match_all('/<(?:strong|b|h[34])[^>]*>\s*(?:Hỏi|Q)\s*[:：]\s*(.*?)<\/(?:strong|b|h[34])>\s*(?:<br\s*\/?>)?\s*(?:<p>)?\s*(?:(?:Trả lời|A)\s*[:：]\s*)?(.*?)(?:<\/p>|<(?:strong|b|h[34]))/si', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $question = trim(strip_tags($match[1]));
                $answer = trim(strip_tags($match[2]));
                if ($question && $answer) {
                    $faqs[] = ['question' => $question, 'answer' => $answer];
                }
            }
        }

        return $faqs;
    }

    /**
     * Get blog URL
     */
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Get excerpt from description
     */
    public function getExcerptAttribute($length = 200)
    {
        $text = strip_tags($this->description);
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }

    /**
     * Get reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));
        $minutes = ceil($wordCount / 200); // Average reading speed 200 words/minute
        return $minutes;
    }

    /**
     * Format published date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at->format('d/m/Y');
    }

    /**
     * Get relative published date
     */
    public function getRelativeDateAttribute()
    {
        return $this->published_at->diffForHumans();
    }
}
