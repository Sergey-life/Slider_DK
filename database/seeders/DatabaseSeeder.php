<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Articles\Article;
use App\Models\Articles\Tag;
use App\Models\Articles\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private $topics = [
        ['name' => 'КСВ'],
        ['name' => 'Магазини Фора'],
        ['name' => 'Фора Club']
    ];

    private $tags = [
        ['name' => 'Акція'],
        ['name' => 'Бали'],
        ['name' => 'Бонуси'],
        ['name' => 'Відкриття'],
        ['name' => 'Гості'],
        ['name' => 'Діти'],
        ['name' => 'Допомога'],
        ['name' => 'Команда'],
        ['name' => 'Проєкт'],
        ['name' => 'Фора Пей'],
    ];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Topic::insert($this->topics);
        Tag::insert($this->tags);
        $articles = Article::factory(50)->create();
        $tags = Tag::all();

        foreach ($articles as $article) {
            $tagsIds = $tags->random(5)->pluck('id');
            $article->tags()->attach($tagsIds);
        }
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
