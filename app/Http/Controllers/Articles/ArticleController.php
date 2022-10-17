<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\Tag;
use App\Models\Articles\Topic;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->topics) {
                $articles = Article::with('tags')
                    ->whereIn('topic_id', $request->topics)
                    ->get();

                $tags = Article::leftJoin('article_tag', 'article_tag.article_id', '=', 'articles.id')
                    ->leftJoin('tags', 'article_tag.tag_id', '=', 'tags.id')
                    ->select('tags.*')
                    ->whereIn('articles.topic_id', $request->topics)
                    ->groupBy('tags.id')
                    ->orderBy('tags.id', 'ASC')
                    ->get();

                $topics = $this->getTopics();
            }
            if ($request->tags) {
                $articles = Article::with('tags')
                    ->leftJoin('article_tag', 'articles.id', '=', 'article_tag.article_id')
                    ->select('articles.*')
                    ->whereIn('article_tag.tag_id', $request->tags)
                    ->groupBy('articles.id')
                    ->orderBy('articles.id')
                    ->get();

                $topics = Article::leftJoin('article_tag', 'article_tag.article_id', '=', 'articles.id')
                    ->leftJoin('topics', 'articles.topic_id', '=', 'topics.id')
                    ->select('topics.*')
                    ->whereIn('article_tag.tag_id', $request->tags)
                    ->groupBy('topics.id')
                    ->orderBy('topics.id')
                    ->get();

                $tags = $this->getTags();
            }

            $html = view('articles.article-filter', [
                'articles'       => !isset($articles) ? Article::with('tags')->get() : $articles,
                'tags'           => !isset($tags) ? $this->getTags() : $tags,
                'topics'         => !isset($topics) ? $this->getTopics() : $topics,
                'checkedTopics'  => $request->topics,
                'checkedTags'    => $request->tags
            ])->render();

            return response()->json([
                'html' => $html,
                // test data
                'articles' => !isset($articles) ? Article::with('tags')->get() : $articles,
                'tags'     => !isset($tags) ? $this->getTags() : $tags,
                'topics'   => !isset($topics) ? $this->getTopics() : $topics,
            ]);
        }

        $topics = $this->getTopics();
        $tags = $this->getTags();
        $articles = Article::with('tags')->get();

        return view('articles.article', [
            'topics'    => $topics,
            'tags'      => $tags,
            'articles'  => $articles
        ]);
    }

    private function getTopics()
    {
        return Topic::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED)
            ->groupBy('topics.id')
            ->orderBy('topics.id', 'ASC')
            ->get();
    }

    private function getTags()
    {
        return Tag::leftJoin('article_tag', 'article_tag.tag_id', '=', 'tags.id')
            ->leftJoin('articles', 'article_tag.article_id', '=', 'articles.id')
            ->select('tags.*')
            ->where('articles.published', Article::PUBLISHED)
            ->groupBy('tags.id')
            ->orderBy('tags.id', 'ASC')
            ->get();
    }
}
