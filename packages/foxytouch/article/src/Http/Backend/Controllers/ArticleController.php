<?php

namespace Foxytouch\Article\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;
use Foxytouch\Article\Repositories\Contracts\ArticleRepository;
use Foxytouch\Article\Repositories\Contracts\CategoryRepository;
use Foxytouch\Article\Http\Backend\Requests\UpdateArticleRequest;
use Foxytouch\Article\Http\Backend\Requests\CreateArticleRequest;
use Foxytouch\Article\Repositories\Contracts\ArticleStatusRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use Intervention\Image\Facades\Image;


/**
 * Controller for {@link \Foxytouch\Article\Models\Article article}.
 *
 * @see \Foxytouch\Article\Models\Article
 * @package \Foxytouch\Article\Http\Backend\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class ArticleController extends Controller
{
    /*
     * Path to default article thumb.
     */
    private $defaultThumb;
    
    /*
     * Path where article's picture will be stored.
     */
    private $picturesPath;

    /**
     * @var ArticleStatusRepository
     */
    private $status;
    
    /**
     * @var ArticleRepository
     */
    private $article;
    
    /**
     * @var CategoryRepository
     */
    private $category;

    /**
     * ArticleController constructor.
     *
     * @param ArticleRepository $article
     * @param CategoryRepository $category
     * @param ArticleStatusRepository $status
     */
    public function __construct(
        ArticleRepository $article,
        CategoryRepository $category,
        ArticleStatusRepository $status)
    {
        $this->article = $article;
        $this->category = $category;
        $this->status = $status;
        $this->defaultThumb = config()->get('article.default_thumb');
        $this->picturesPath = config()->get('article.pictures_path');
    }

    /**
     * Get and show all created articles sorted
     * descended by its creation date.
     *
     * @see \Foxytouch\Article\Models\Article
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $articles = $this->article->all();
        
        return view('articles::backend.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new {@link \Foxytouch\Article\Models\Article article}.
     *
     * @see \Foxytouch\Article\Models\Article
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->getViewWithValues('articles::backend.articles.create');
    }

    /**
     * Show the form for editing the specified {@link \Foxytouch\Article\Models\Article article}.
     *
     * @param $slug - of an article to be edited
     * @return \Illuminate\View\View
     */
    public function edit($slug)
    {
        $article = $this->article->findBy('slug', $slug);

        return $this->getViewWithValues('articles::backend.articles.edit', $article);
    }

    /*
     * Get specific view with values necessary for creating or update article.
     * Values like categories names, article statuses etc...
     *
     * Optional argument is article model, which is useful for update.
     */
    private function getViewWithValues($view, $article = null)
    {
        $categoriesNames = $this->category->findAllValues('name');
        // Add virtual Category
        if (empty($categoriesNames)) {
            array_push($categoriesNames, trans('articles::category.uncategorized'));
        }
        $categories = array_combine($categoriesNames, $categoriesNames);

        $statusesNames = $this->status->findAllValues('name');
        $statuses = array_combine($statusesNames, $statusesNames);

        return $article ? view($view, compact('categories', 'statuses', 'article')) :
                          view($view, compact('categories', 'statuses'));
    }

    /**
     * <p>
     * Store newly created {@link \Foxytouch\Article\Models\Article article}.
     * </p>
     *
     * <p>
     * After success, user will be <b>redirected</b to list of
     * all already created {@link \Foxytouch\Article\Models\Article articles}
     * </p>
     *
     * @see \Foxytouch\Article\Models\Article
     * @param \Foxytouch\Article\Http\Backend\Requests\CreateArticleRequest $request - form request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateArticleRequest $request)
    {
        $category = $this->category->findBy('name', $request->category);
        $status = $this->status->findBy('name', $request->status);
        $this->article->create($request->all(), $category, $status);

        return Redirect::route('auth.article.index')
                       ->with('success', trans('general.success_create'));
    }

    /**
     * Update the specified {@link \Foxytouch\Article\Models\Article article} in the storage.
     *
     * @param $slug - of an article to be updated
     * @param \Foxytouch\Article\Http\Backend\Requests\UpdateArticleRequest $request - form request
     * @return \Illuminate\Http\Response
     */
    public function update($slug, UpdateArticleRequest $request)
    {
        $article = $this->article->findBy('slug', $slug);

        $category = $this->category->findByName($request->category);
        $status = $this->status->findByName($request->status);
        $this->article->updateWithCategoryAndStatus($article, $request->all(), $category, $status);

        return Redirect::route('auth.article.edit', $article->slug)
                       ->with('success', trans('general.success_update'));
    }

    /**
     * Remove the specified {@link \Foxytouch\Article\Models\Article article} from storage.
     *
     * @param $slug - article to be deleted
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $article = $this->article->findBy('article', $slug);
        $this->article->destroy($article);
        
        return Redirect::route('auth.article.index')
                        ->with('success', trans('general.success_delete'));
    }
}
