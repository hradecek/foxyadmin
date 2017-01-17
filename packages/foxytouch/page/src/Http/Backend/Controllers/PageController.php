<?php

namespace Foxytouch\Page\Http\Backend\Controllers;

use Foxytouch\Page\Repositories\Contracts\PageRepository;
use Foxytouch\Page\Http\Backend\Requests\CreatePageRequest;
use Foxytouch\Page\Http\Backend\Requests\UpdatePageRequest;

use Foxytouch\Http\Backend\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;

/**
 * Controller for {@link \Foxytouch\Page\Models\Page page}.
 *
 * @see \Foxytouch\Page\Models\Page
 * @package \Foxytouch\Page\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class PageController extends Controller
{
    /**
     * @var PageRepository
     */
    private $page;

    /**
     * PageController constructor.
     * @param PageRepository $page
     */
    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    /**
     * Get and show all created pages. Pages are sorted by
     * descending order by creation date.
     *
     * @see \Foxytouch\Page\Models\Page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pages = $this->page->allSorted();
        
        return view('pages::backend.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new {@link \Foxytouch\Page\Models\Page page}.
     *
     * @see \Foxytouch\Page\Http\Models\Page
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('pages::backend.pages.create');
    }

    /**
     * <p>
     * Store newly created {@link \Foxytouch\Page\Models\Page page}.
     * </p>
     *
     * <p>
     * After success, user will be <b>redirected</b to list of
     * all already created {@link \Foxytouch\Page\Models\Page pages}
     * </p>
     *
     * @see \Foxytouch\Page\Models\Article
     * @param CreatePageRequest $request
     */
    public function store(CreatePageRequest $request)
    {
        $this->page->create($request->all());

        return Redirect::route('auth.page.index')
                       ->with('success', trans('general.success_create'));
    }

    /**
     * Show the form for editing the specified {@link \Foxytouch\Page\Models\Page page}.
     *
     * @param $slug - page to be edited
     * @return \Illuminate\View\View
     */
    public function edit($slug)
    {
        $page = $this->page->findBy('slug', $slug);

        return view('pages::backend.pages.edit', compact('page'));
    }

    /**
     * Update the specified {@link \Foxytouch\Page\Models\Page page} in storage.
     *
     * @param $slug - of an page to be updated
     * @param UpdatePageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($slug, UpdatePageRequest $request)
    {
        $page = $this->page->findBy('slug', $slug);
        $this->page->update($page, $request->all());

        return Redirect::route('auth.page.edit', $page->slug)
                       ->with('success', trans('general.success_update'));
    }

    /**
     * Remove the specified {@link \Foxytouch\Page\Models\Page page} from storage.
     *
     * @param $slug - page to be removed
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $page = $this->page->findBy('slug', $slug);
        $page->delete();

        return Redirect::route('auth.page.index')
                       ->with('success', trans('general.success_delete'));
    }
}
