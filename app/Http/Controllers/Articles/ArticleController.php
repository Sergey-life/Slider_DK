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
            if ($request->topics || $request->tags) {
                $articles = $this->findArticlesDependingOnTopicsAndTags($request->topics, $request->tags);
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
                'articles'       => !isset($articles) ? $this->findAvailableArticles() : $articles,
                'tags'           => !isset($tags) ? $this->findAvailableTags() : $tags,
                'topics'         => !isset($topics) ? $this->findAvailableTopics() : $topics,
                'checkedTopics'  => $request->topics,
                'checkedTags'    => $request->tags
            ])->render();

            return response()->json([
                'html' => $html,
                // test data
                'articles' => $articles,
                'tags'     => !isset($tags) ? $this->findAvailableTags() : $tags,
                'topics'   => !isset($topics) ? $this->findAvailableTopics() : $topics,
                'checkedTags'    => $request->tags
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
            ->get();
    }

    private function findAvailableTopics()
    {
        return Topic::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED)
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
            ->groupBy('tags.id')
            ->orderBy('tags.id', 'ASC')
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
                ->whereIn('topic_id', $topicIds)
                ->get();
        }
        if ($tagIds && is_null($topicIds)) {
            return Article::with('tags')
                ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                ->where('articles.published', Article::PUBLISHED)
                ->whereIn('article_tag.tag_id', $tagIds)
                ->get();
        }
        if (!is_null($tagIds) && !is_null($topicIds)) {
            /*
             * TODO - Витягнути всі новини по $request->tags і по $request->topics
             */
        }
    }

    private function findTagsDependingOnTopics($topicIds, $tagIds)
    {
        /*
         * TODO - если в фильтре есть темы, то теги нужно выводить в зависимости от выбранных тем
         * TODO - Отримати всі теги відповідаючи переданим топікам
         */
    }

    private function findTopicsDependingOnTags($tagIds, $topicIds)
    {
        /*
         * TODO - если в фильтре есть теги, то темы нужно выводить в зависимости от выбранных тегов
         * TODO - Якщо передані топіки і теги то витагнути топіки та теги згідно переданими топіками та тегами
         */
    }
}
