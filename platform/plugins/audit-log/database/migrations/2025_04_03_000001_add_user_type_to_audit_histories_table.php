<?php

use Guestcms\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('audit_histories', 'actor_id')) {
            Schema::table('audit_histories', function (Blueprint $table) {
                $table->renameColumn('reference_user', 'actor_id');
            });
        }

        if (! Schema::hasColumn('audit_histories', 'user_type')) {
            Schema::table('audit_histories', function (Blueprint $table) {
                $table->string('user_type')->nullable()->after('user_id')->default(addslashes(User::class));
                $table->string('actor_type')->nullable()->after('actor_id')->default(addslashes(User::class));
            });
        }
    }

    public function down(): void
    {
        Schema::table('audit_histories', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'actor_type']);
        });

        Schema::table('audit_histories', function (Blueprint $table) {
            $table->renameColumn('actor_id', 'reference_user');
        });
    }
};
