<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForumPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to create/edit forum posts
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category_id' => 'required|exists:forum_categories,id',
            'type' => 'required|in:discussion,idea,question,showcase,job,review',
            'tags' => 'nullable|string|max:500',
            'edit_reason' => 'nullable|string|max:255',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề',
            'content' => 'nội dung',
            'category_id' => 'danh mục',
            'type' => 'loại bài viết',
            'tags' => 'tags',
            'edit_reason' => 'lý do chỉnh sửa',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => ':attribute là bắt buộc.',
            'title.max' => ':attribute không được vượt quá :max ký tự.',
            'content.required' => ':attribute là bắt buộc.',
            'content.min' => ':attribute phải có ít nhất :min ký tự.',
            'category_id.required' => 'Vui lòng chọn :attribute.',
            'category_id.exists' => ':attribute được chọn không hợp lệ.',
            'type.required' => 'Vui lòng chọn :attribute.',
            'type.in' => ':attribute được chọn không hợp lệ.',
            'tags.max' => ':attribute không được vượt quá :max ký tự.',
            'edit_reason.max' => ':attribute không được vượt quá :max ký tự.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean HTML content but keep basic formatting
        if ($this->has('content')) {
            $content = $this->input('content');
            // Basic HTML sanitization - remove dangerous tags but keep formatting
            $content = strip_tags($content, '<p><br><strong><em><u><ul><ol><li><a><code><pre><blockquote><h1><h2><h3><h4><h5><h6>');
            $this->merge([
                'content' => $content,
            ]);
        }

        // Clean and format tags
        if ($this->has('tags') && $this->input('tags')) {
            $tags = $this->input('tags');
            $tagArray = explode(',', $tags);
            $tagArray = array_map('trim', $tagArray);
            $tagArray = array_filter($tagArray); // Remove empty tags
            $tagArray = array_unique($tagArray); // Remove duplicates
            $tagArray = array_slice($tagArray, 0, 10); // Limit to 10 tags max
            
            $this->merge([
                'tags' => implode(',', $tagArray),
            ]);
        }
    }
}
