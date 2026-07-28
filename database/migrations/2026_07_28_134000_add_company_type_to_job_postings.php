<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('company_type', 50)->nullable()->after('company_logo');
            // Values: game_studio, outsource, it_company, startup, agency, headhunter, other
        });

        // Auto-tag existing jobs based on title/description keywords
        $gameStudioKeywords = ['game studio', 'game company', 'phát triển game', 'sản xuất game', 'game publisher'];
        $outsourceKeywords = ['outsource', 'outsourcing', 'offshore'];
        $headhunterKeywords = ['headhunter', 'tuyển dụng', 'recruitment', 'hr agency'];

        $jobs = \DB::table('job_postings')->get(['id', 'title', 'description', 'company_name']);
        foreach ($jobs as $job) {
            $text = strtolower(($job->title ?? '') . ' ' . ($job->description ?? '') . ' ' . ($job->company_name ?? ''));
            $type = 'it_company'; // default

            foreach ($gameStudioKeywords as $kw) {
                if (str_contains($text, $kw)) { $type = 'game_studio'; break; }
            }
            if ($type === 'it_company') {
                foreach ($outsourceKeywords as $kw) {
                    if (str_contains($text, $kw)) { $type = 'outsource'; break; }
                }
            }
            if ($type === 'it_company') {
                foreach ($headhunterKeywords as $kw) {
                    if (str_contains($text, $kw)) { $type = 'headhunter'; break; }
                }
            }
            // If has game-related skills, mark as game_studio
            $gameSkills = \DB::table('job_posting_skills')
                ->where('job_posting_id', $job->id)
                ->whereIn('skill_name', ['Unity', 'Unreal', 'Godot', 'Game Design', 'C#', '3D Modeling', 'Animation', 'Pixel Art'])
                ->exists();
            if ($gameSkills) $type = 'game_studio';

            \DB::table('job_postings')->where('id', $job->id)->update(['company_type' => $type]);
        }
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('company_type');
        });
    }
};
