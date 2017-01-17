<?php

namespace Foxytouch\Page\Http\Backend\Requests;

use Foxytouch\Page\Repositories\Contracts\PageRepository;

use Illuminate\Foundation\Http\FormRequest;


/**
 * <p>
 * Update {@link \Foxytouch\Page\Models\Page page} form request.
 * </p>
 * <p>
 * Validation criteria:
 * <ul>
 *   <li><i>name</i> is <b>required</b>,</li>
 *   <li><i>name</i> must be max 255 characters long,</li>
 *   <li><i>slug</i> must be unique per page,</li>
 *   <li><i>slug</i> must be max 255 characters long.</li>
 * </ul>
 * </p>
 *
 * @package \Foxytouch\Page\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UpdatePageRequest extends FormRequest
{
    /**
     * @var PageRepository
     */
    private $page;

    /**
     * UpdatePageRequest constructor.
     *
     * @param PageRepository $page
     */
    public function __construct(PageRepository $page)
    {
        parent::__construct();
        $this->page = $page;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $pageId = $this->getPageIdFromRouteParameter();
        $pageTable = config('pages.table.name.page', 'page');

        return [
            'slug'  => "max:255|unique:$pageTable,slug,$pageId",
            'name'  => 'max:255|required',
            'title' => 'max:255',
        ];
    }

    private function getPageIdFromRouteParameter()
    {
        $slug = $this->route()->getParameter('page');
        $page = $this->page->findBy('slug', $slug);

        return $page->id;
    }
}
