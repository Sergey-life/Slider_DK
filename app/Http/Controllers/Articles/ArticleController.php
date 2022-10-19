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

            if ($request->topics) {
                $articlesIds = [];
                $tagsIds = [];

                $articles = Article::with('tags')
                    ->whereIn('topic_id', $request->topics)
                    ->get();

                foreach ($articles as $article) {
                    $articlesIds[] = $article->id; //get articles ids
                    foreach ($article->tags as $tag) {
                        if (!in_array($tag->id, $tagsIds)) {
                            $tagsIds[] = $tag->id; // get tags ids
                        }
                    }
                }

                $tags = Tag::leftJoin('article_tag' , 'article_tag.tag_id', '=', 'tags.id')
                    ->select('tags.*')
                    ->whereIn('article_tag.tag_id', $tagsIds)
                    ->groupBy('tags.id')
                    ->orderBy('tags.id', 'ASC')
                    ->get();

//                $tags = Tag::whereHas('articles', function ($query) use ($tagsIds) {
//                    $query->whereIn('tags.id', $tagsIds);
//                })->get();

//                dump($tags);
//                $topics = $this->getTopics();
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

//                $tags = $this->getTags();
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

        $topics = $this->findAvailableTopics();
        $tags = $this->findAvailableTags();
        $articles = Article::with('tags')
            ->where('published', Article::PUBLISHED)
            ->get();

        return view('articles.article', [
            'topics'    => $topics,
            'tags'      => $tags,
            'articles'  => $articles
        ]);
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

    private function findArticlesDependingOnTopicsAndTags($topicIds, $tagIds)
    {
        /*
         * TODO - Витягнути всі новини по $request->tags і по $request->topics
         */
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
