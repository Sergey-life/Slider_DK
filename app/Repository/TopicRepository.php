<?php

namespace App\Repository;

use App\Models\Articles\Article;
use App\Models\Articles\Topic;
use Carbon\Carbon;

class TopicRepository extends BaseRepository
{
    public function __construct(Topic $model)
    {
        parent::__construct($model);
    }

    public function findAvailableTopics()
    {
        return $this->model::leftJoin('articles', 'articles.topic_id', '=', 'topics.id')
            ->select('topics.*')
            ->where('articles.published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->groupBy('topics.id')
            ->orderBy('topics.id', 'ASC')
            ->get();
    }

    public function findTopicsDependingOnArticles($tagIds)
    {
        /*
         * TODO - вивести теми прив'язані до новин якщо в фільтрі вибрані тільки теги
         */
        return $this->model::join('articles', 'topics.id', '=', 'articles.topic_id')
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

    public function findTopicsDependingOnTags($topicIds, $tagIds)
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
