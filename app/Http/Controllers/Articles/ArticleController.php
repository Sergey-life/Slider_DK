<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\Topic;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $topic = Topic::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED_ARTICLE)
            ->groupBy('topics.id')
            ->get();
//        $topic = Topic::with('articles')->get();
        dump($topic);
    }
}
