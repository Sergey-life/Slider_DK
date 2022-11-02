<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use App\Repository\TopicRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleRepository;
    protected $tagRepository;
    protected $topicRepository;

    public function __construct(ArticleRepository $article, TopicRepository $topic, TagRepository $tag)
    {
        $this->articleRepository = $article;
        $this->topicRepository = $topic;
        $this->tagRepository = $tag;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->topics || $request->tags) {
                $articles = $this->articleRepository->findArticlesDependingOnTopicsAndTags($request->topics, $request->tags);
            }
            // вивести теги прив'язані до новин якщо в фільтрі вибрані тільки теми
            if ($request->topics && is_null($request->tags)) {
                $tags = $this->tagRepository->findTagsDependingOnArticles($request->topics);
            }
            // вивести теми прив'язані до новин якщо в фільтрі вибрані тільки теги
            if ($request->tags && is_null($request->topics)) {
                $topics = $this->topicRepository->findTopicsDependingOnArticles($request->tags);
            }

            if ($request->tags) {
                //1. Витягнути всі новини по $request->tags і по $request->topics
                //если в фильтре есть темы, то теги нужно выводить в зависимости от выбранных тем
                if ($request->topics) {
                    //2. Отримати всі теги відповідаючи переданим топікам
                    $tags = $this->tagRepository->findTagsDependingOnTopics($request->topics, $request->tags);
                } else { //иначе выводить все доступные теги
                    $tags = $this->tagRepository->findAvailableTags();
                }
            }
            if ($request->topics) {
                //если в фильтре есть теги, то темы нужно выводить в зависимости от выбранных тегов
                if ($request->tags) {
//                    // 3. Якщо передані топіки і теги то витагнути топіки та теги згідно переданими топіками та тегами
                    $topics = $this->topicRepository->findTopicsDependingOnTags($request->topics, $request->tags);
                } else { //иначе выводить все доступные темы
                    $topics = $this->topicRepository->findAvailableTopics();
                }
            }
            if (is_null($request->topics) && is_null($request->tags)) {
                $topics = $this->topicRepository->findAvailableTopics();
                $tags = $this->tagRepository->findAvailableTags();
                $articles = $this->articleRepository->findAvailableArticles();
            }


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
            'topics'    => $this->topicRepository->findAvailableTopics(),
            'tags'      => $this->tagRepository->findAvailableTags(),
            'articles'  => $this->articleRepository->findAvailableArticles()
        ]);
    }
}
