<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->deduplicateApplications();

        Schema::table('applications', function (Blueprint $table) {
            $table->unique(['user_id', 'project_id'], 'applications_user_project_unique');
            $table->index(['project_id', 'status'], 'applications_project_status_index');
            $table->index(['user_id', 'status'], 'applications_user_status_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['project_id', 'created_at'], 'comments_project_created_at_index');
            $table->index(['user_id', 'created_at'], 'comments_user_created_at_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'created_at'], 'messages_conversation_created_at_index');
            $table->index(['user_id', 'created_at'], 'messages_user_created_at_index');
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            $table->index('conversation_id', 'conversation_user_conversation_id_index');
            $table->index(['conversation_id', 'read_at'], 'conversation_user_conversation_read_at_index');
        });

        Schema::table('follower_user', function (Blueprint $table) {
            $table->index('following_id', 'follower_user_following_id_index');
        });

        Schema::table('project_saves', function (Blueprint $table) {
            $table->index('project_id', 'project_saves_project_id_index');
            $table->index(['user_id', 'created_at'], 'project_saves_user_created_at_index');
        });

        Schema::table('project_likes', function (Blueprint $table) {
            $table->index('project_id', 'project_likes_project_id_index');
            $table->index(['user_id', 'created_at'], 'project_likes_user_created_at_index');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'projects_user_created_at_index');
            $table->index(['status', 'created_at'], 'projects_status_created_at_index');
            $table->index(['is_remote', 'created_at'], 'projects_is_remote_created_at_index');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->index(['user_id', 'start_date'], 'experiences_user_start_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropIndex('experiences_user_start_date_index');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_user_created_at_index');
            $table->dropIndex('projects_status_created_at_index');
            $table->dropIndex('projects_is_remote_created_at_index');
        });

        Schema::table('project_likes', function (Blueprint $table) {
            $table->dropIndex('project_likes_project_id_index');
            $table->dropIndex('project_likes_user_created_at_index');
        });

        Schema::table('project_saves', function (Blueprint $table) {
            $table->dropIndex('project_saves_project_id_index');
            $table->dropIndex('project_saves_user_created_at_index');
        });

        Schema::table('follower_user', function (Blueprint $table) {
            $table->dropIndex('follower_user_following_id_index');
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropIndex('conversation_user_conversation_id_index');
            $table->dropIndex('conversation_user_conversation_read_at_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_created_at_index');
            $table->dropIndex('messages_user_created_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_project_created_at_index');
            $table->dropIndex('comments_user_created_at_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_user_project_unique');
            $table->dropIndex('applications_project_status_index');
            $table->dropIndex('applications_user_status_index');
        });
    }

    private function deduplicateApplications(): void
    {
        $duplicates = DB::table('applications')
            ->select([
                'user_id',
                'project_id',
                DB::raw('MIN(id) as keep_id'),
            ])
            ->groupBy('user_id', 'project_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('applications')
                ->where('user_id', $duplicate->user_id)
                ->where('project_id', $duplicate->project_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }
    }
};
