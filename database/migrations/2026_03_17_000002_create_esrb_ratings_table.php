<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esrb_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();   // E, E10+, T, M, AO, RP
            $table->string('name');                  // Tên đầy đủ
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });

        // Dữ liệu mặc định của ESRB
        $ratings = [
            ['code' => 'RP',  'name' => 'Rating Pending',    'description' => 'Chưa được xếp loại chính thức.',                                                 'min_age' => null, 'order' => 0],
            ['code' => 'E',   'name' => 'Everyone',          'description' => 'Nội dung phù hợp với mọi lứa tuổi. Có thể có bạo lực hoạt hình mức độ nhẹ.',    'min_age' => 6,    'order' => 1],
            ['code' => 'E10+','name' => 'Everyone 10+',      'description' => 'Nội dung phù hợp từ 10 tuổi trở lên. Có thể có bạo lực hoặc nội dung nhẹ.',     'min_age' => 10,   'order' => 2],
            ['code' => 'T',   'name' => 'Teen',              'description' => 'Nội dung phù hợp từ 13 tuổi trở lên. Có thể có bạo lực, ngôn ngữ nhẹ.',         'min_age' => 13,   'order' => 3],
            ['code' => 'M',   'name' => 'Mature 17+',        'description' => 'Nội dung phù hợp từ 17 tuổi trở lên. Có thể có bạo lực mạnh, nội dung người lớn.','min_age' => 17,   'order' => 4],
            ['code' => 'AO',  'name' => 'Adults Only 18+',   'description' => 'Chỉ dành cho người trưởng thành từ 18 tuổi. Có thể có nội dung tình dục, cờ bạc.','min_age' => 18,   'order' => 5],
        ];

        foreach ($ratings as $rating) {
            DB::table('esrb_ratings')->insert(array_merge($rating, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('esrb_ratings');
    }
};
