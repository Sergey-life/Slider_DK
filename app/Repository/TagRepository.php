<?php

namespace App\Repository;

use App\Models\Articles\Article;
use App\Models\Articles\Tag;
use Carbon\Carbon;

class TagRepository extends BaseRepository
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }

    public function findAvailableTags()
    {
        return $this->model::leftJoin('article_tag', 'article_tag.tag_id', '=', 'tags.id')
            ->leftJoin('articles', 'article_tag.article_id', '=', 'articles.id')
            ->select('tags.*')
            ->where('articles.published', Article::PUBLISHED)
            ->where('articles.active', Article::ACTIVE)
            ->where('articles.created_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
            ->groupBy('tags.id')
            ->orderBy('tags.id', 'ASC')
            ->get();
    }

    public function findTagsDependingOnArticles($topicIds)
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

    public function findTagsDependingOnTopics($topicIds, $tagIds)
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
}
