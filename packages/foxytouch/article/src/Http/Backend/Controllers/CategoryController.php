<?php

namespace Foxytouch\Article\Http\Backend\Controllers;

use Foxytouch\Article\Models\Category;
use Foxytouch\Article\Repositories\Contracts\ArticleRepository;
use Foxytouch\Article\Repositories\Contracts\CategoryRepository;
use Foxytouch\Article\Http\Backend\Requests\CreateCategoryRequest;
use Foxytouch\Article\Http\Backend\Requests\UpdateCategoryRequest;

use Illuminate\Support\Facades\Redirect;

use Foxytouch\Http\Backend\Controllers\Controller;

/**
 * Controller for {@link \Foxytouch\Article\Models\Category category}.
 *
 * @see \Foxytouch\Article\Http\Models\Category
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package \Foxytouch\Article\Http\Backend\Controllers
 */
class CategoryController extends Controller
{
    /**
     * @var ArticleRepository
     */
    private $article;

    /**
     * @var CategoryRepository
     */
    private $category;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepository $category
     * @param ArticleRepository $article
     */
    public function __construct(CategoryRepository $category, ArticleRepository $article)
    {
        $this->category = $category;
        $this->article = $article;
    }

    /**
     * Get and show all {@link \Foxytouch\Article\Models\Category categories}.
     * Default sorting of categories is by category name.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->allSorted('name');
        
        return view('articles::backend.categories.index', compact('categories'));
    }

    /**
     * Show {@link \Foxytouch\Article\Models\Category category's} information.
     *
     * @param $name - category to show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($name)
    {
        $category = $this->category->findBy('name', $name);

        return view('articles::backend.categories.show', compact('category'));
    }

    /**
     * Store a new {@link \Foxytouch\Article\Models\Category category}.
     *
     * @param CreateCategoryRequest $request - form request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->category->create($request->all());

        return Redirect::route('auth.category.index')
                       ->with('success', trans('general.success_create'));
    }

    /**
     * Show the form for editing the specified {@link \Foxytouch\Article\Models\Category category}.
     *
     * @param $name - category to be edited
     * @return \Illuminate\View\View
     */
    public function edit($name)
    {
        $category = $this->category->findBy('name', $name);

        return view('articles::backend.categories.edit', compact('category'));
    }

    /**
     * Update the specified {@link \Foxytouch\Article\Models\Category category} in storage.
     *
     * @param UpdateCategoryRequest $request - form request
     * @param $name - category to be updated
     * @return \Illuminate\Http\Response
     */
    public function update($name, UpdateCategoryRequest $request)
    {
        $category = $this->category->findBy('name', $name);
        $this->category->update($category, $request->all());

        return Redirect::route('auth.category.index')
                       ->with('success', trans('general.success_update'));
    }

    /**
     * Delete a specific {@link \Foxytouch\Article\Models\Category category}.
     *
     * @param $name - category to delete
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $category = $this->category->findBy('name', $name);
        $id = $category->id;
        $this->category->destroy($category);

        $message = trans('general.success_destroy');
        $ajaxResponse = response()->json([
            'id' => $id, 'success' => $message
        ]);
        $redirect = Redirect::route('auth.category.index')->with('success', $message);

        return Request::ajax() ? $ajaxResponse : $redirect;
    }

    /**
     * Get all articles, which aren't associated with any category.
     * For these articles is created special <i>virtual</i> category.
     */
    public function uncategorized()
    {
        $name = trans('articles::category.uncategorized');
        $articles = $this->article->findAllWithoutCategory();
        $category = $this->createVirtualCategory($name, $articles);

        return view('articles::backend.categories.show', compact('category'));
    }

    private function createVirtualCategory($name = 'uncategorized', $articles = null)
    {
        /* Todo: should NOT use model directly here. */
        $category = new Category;
        $category->name = $name;
        $category->articles = $articles;

        return $category;
    }
}
