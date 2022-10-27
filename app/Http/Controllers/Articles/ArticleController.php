<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Articles\Tag;
use App\Models\Articles\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->topics || $request->tags) {
                $articles = $this->findArticlesDependingOnTopicsAndTags($request->topics, $request->tags);
            }
            // вивести теги прив'язані до новин якщо в фільтрі вибрані тільки теми
            if ($request->topics && is_null($request->tags)) {
                $tags = $this->findTagsDependingOnArticles($request->topics);
            }
            // вивести теми прив'язані до новин якщо в фільтрі вибрані тільки теги
            if ($request->tags && is_null($request->topics)) {
                $topics = $this->findTopicsDependingOnArticles($request->tags);
            }

            if ($request->tags) {
                //1. Витягнути всі новини по $request->tags і по $request->topics
                //если в фильтре есть темы, то теги нужно выводить в зависимости от выбранных тем
                if ($request->topics) {
                    //2. Отримати всі теги відповідаючи переданим топікам
                    $tags = $this->findTagsDependingOnTopics($request->topics, $request->tags);
                } else { //иначе выводить все доступные теги
                    $tags = $this->findAvailableTags();
                }
            }
            if ($request->topics) {
                //если в фильтре есть теги, то темы нужно выводить в зависимости от выбранных тегов
                if ($request->tags) {
//                    // 3. Якщо передані топіки і теги то витагнути топіки та теги згідно переданими топіками та тегами
                    $topics = $this->findTopicsDependingOnTags($request->topics, $request->tags);
                } else { //иначе выводить все доступные темы
                    $topics = $this->findAvailableTopics();
                }
            }
            //has context menu


            $html = view('articles.article-filter', [
                'articles'       => $articles,
                'tags'           => $tags,
                'topics'         => $topics,
                'checkedTopics'  => $request->topics,
                'checkedTags'    => $request->tags
            ])->render();

            return response()->json([
                'html'        => $html,
                // test data
                'articles'    => $articles,
                'tags'        => $tags,
                'topics'      => $topics,
            ]);
        }

        return view('articles.article', [
            'topics'    => $this->findAvailableTopics(),
            'tags'      => $this->findAvailableTags(),
            'articles'  => $this->findAvailableArticles()
        ]);
    }

    private function findAvailableArticles()
    {
        return Article::with('tags')
            ->where('published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->get();
    }

    private function findAvailableTopics()
    {
        return Topic::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->groupBy('topics.id')
            ->orderBy('topics.id', 'ASC')
            ->get();
    }

    private function findAvailableTags()
    {
        return Tag::leftJoin('article_tag', 'article_tag.tag_id', '=', 'tags.id')
            ->leftJoin('articles', 'article_tag.article_id', '=', 'articles.id')
            ->select('tags.*')
            ->where('articles.published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->groupBy('tags.id')
            ->orderBy('tags.id', 'ASC')
            ->get();
    }

    private function findTagsDependingOnArticles($topicIds)
    {
        /*
         * TODO - вивести теги прив'язані до новин якщо в фільтрі вибрані тільки теми
         */
        return Tag::join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->select('tags.id', 'tags.name')
            ->whereIn('article_tag.article_id', function ($query) use ($topicIds) {
                $query->from('articles')
                    ->join('topics', 'topics.id', '=', 'articles.topic_id')
                    ->select('articles.id')
                    ->where('articles.published', Article::PUBLISHED)
                    ->where('articles.active', Article::ACTIVE)
                    ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                    ->whereIn('topics.id', $topicIds);
            })
            ->groupBy('tags.id')
            ->orderBy('tags.id')
            ->get();
    }

    private function findTopicsDependingOnArticles($tagIds)
    {
        /*
         * TODO - вивести теми прив'язані до новин якщо в фільтрі вибрані тільки теги
         */
        return Topic::join('articles', 'topics.id', '=', 'articles.topic_id')
            ->select('topics.id', 'topics.name')
            ->whereIn('articles.id', function ($query) use ($tagIds) {
                $query->from('articles')
                    ->join('article_tag', 'articles.id', 'article_tag.article_id')
                    ->select('articles.id')
                    ->where('articles.published', Article::PUBLISHED)
                    ->where('articles.active', Article::ACTIVE)
                    ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                    ->whereIn('article_tag.tag_id', $tagIds);
            })
            ->groupBy('topics.id')
            ->orderBy('topics.id')
            ->get();
    }

    private function findArticlesDependingOnTopicsAndTags($topicIds = null, $tagIds = null)
    {
        /*
         * TODO - Витягнути всі новини по $request->tags і по $request->topics
         */
        if ($topicIds && is_null($tagIds)){
            return Article::with('tags')
                ->where('published', Article::PUBLISHED)
                ->where('articles.active', Article::ACTIVE)
                ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->whereIn('topic_id', $topicIds)
                ->get();
        }
        if ($tagIds && is_null($topicIds)) {
            return Article::with('tags')
                ->select('articles.*')
                ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                ->where('articles.published', Article::PUBLISHED)
                ->where('articles.active', Article::ACTIVE)
                ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->whereIn('article_tag.tag_id', $tagIds)
                ->groupBy('articles.id')
                ->orderBy('articles.id')
                ->get();
        }
        if (!is_null($tagIds) && !is_null($topicIds)) {
            return Article::with('tags')
                ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                ->select('articles.*')
                ->where('articles.published', Article::PUBLISHED)
                ->where('articles.active', Article::ACTIVE)
                ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->whereIn('article_tag.tag_id', $tagIds)
                ->whereIn('articles.topic_id', $topicIds)
                ->groupBy('articles.id')
                ->orderBy('articles.id')
                ->get();

        }
    }

    private function findTagsDependingOnTopics($topicIds, $tagIds)
    {
        /*
         * TODO - если в фильтре есть темы, то теги нужно выводить в зависимости от выбранных тем
         * TODO - Отримати всі теги відповідаючи переданим топікам
         */
        return Tag::join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->select('tags.id', 'tags.name')
            ->whereIn('article_tag.article_id', function ($query) use ($tagIds, $topicIds) {
                $query->from('articles')
                    ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                    ->join('topics', 'topics.id', '=', 'articles.topic_id')
                    ->select('articles.id')
                    ->where('articles.published', Article::PUBLISHED)
                    ->where('articles.active', Article::ACTIVE)
                    ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                    ->whereIn('article_tag.tag_id', $tagIds)
                    ->whereIn('topics.id', $topicIds);
            })
            ->groupBy('tags.id')
            ->orderBy('tags.id')
            ->get();
    }

    private function findTopicsDependingOnTags($topicIds, $tagIds)
    {
        /*
        * TODO - если в фильтре есть теги, то темы нужно выводить в зависимости от выбранных тегов
        * TODO - Якщо передані топіки і теги то витагнути топіки та теги згідно переданими топіками та тегами
        */
        return Topic::join('articles', 'topics.id', '=', 'articles.topic_id')
            ->select('topics.id', 'topics.name')
            ->whereIn('articles.id', function ($query) use ($tagIds, $topicIds) {
                $query->from('articles')
                    ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                    ->join('topics', 'topics.id', '=', 'articles.topic_id')
                    ->select('articles.id')
                    ->where('articles.published', Article::PUBLISHED)
                    ->where('articles.active', Article::ACTIVE)
                    ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                    ->whereIn('article_tag.tag_id', $tagIds)
                    ->whereIn('topics.id', $topicIds);
            })
            ->groupBy('topics.id')
            ->orderBy('topics.id')
            ->get();

    }
}
