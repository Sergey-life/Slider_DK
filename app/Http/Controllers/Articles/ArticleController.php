<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\Tag;
use App\Models\Articles\Topic;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $topics = Topic::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED)
            ->groupBy('topics.id')
            ->orderBy('topics.id', 'ASC')
            ->get();

        $tags = Tag::leftJoin('article_tag', 'article_tag.tag_id', '=', 'tags.id')
            ->leftJoin('articles', 'article_tag.article_id', '=', 'articles.id')
            ->select('tags.*')
            ->where('articles.published', Article::PUBLISHED)
            ->groupBy('tags.id')
            ->orderBy('tags.id', 'ASC')
            ->get();

        $articles = Article::all();

        return view('articles.article', [
            'topics'    => $topics,
            'tags'      => $tags,
            'articles'  => $articles
        ]);
    }
}
