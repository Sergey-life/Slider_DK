<?php

namespace App\Repository;

use App\Models\Articles\Article;
use Carbon\Carbon;

class ArticleRepository extends BaseRepository
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function findAvailableArticles()
    {
        return $this->model::with('tags')
            ->where('published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->get();
    }

    public function findArticlesDependingOnTopicsAndTags($topicIds = null, $tagIds = null)
    {
        /*
         * TODO - Витягнути всі новини по $request->tags і по $request->topics
         */
        if ($topicIds && is_null($tagIds)){
            return $this->model::with('tags')
                ->where('published', Article::PUBLISHED)
                ->where('articles.active', Article::ACTIVE)
                ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->whereIn('topic_id', $topicIds)
                ->get();
        }
        if ($tagIds && is_null($topicIds)) {
            return $this->model::with('tags')
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
            return $this->model::with('tags')
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


}
