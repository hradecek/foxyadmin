<?php

namespace Foxytouch\Article\Http\Backend\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Foxytouch\Article\Repositories\Contracts\ArticleRepository;

/**
 * <p>
 * Update {@link \Foxytouch\Article\Models\Article article} form request.
 * </p>
 * <p>
 * Validation criteria:
 * <ul>
 *   <li><i>title</i> is <b>required</b>,</li>
 *   <li><i>title</i> must be max 255 characters long,</li>
 *   <li><i>slug</i> must be unique per article,</li>
 *   <li><i>slug</i> must be max 255 characters long,</li>
 *   <li><i>thumb</i> must be an image.</li>
 * </ul>
 * </p>
 *
 * @see \Foxytouch\Article\Models\Article
 * @package \Foxytouch\Article\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UpdateArticleRequest extends FormRequest
{
    /**
     * @var ArticleRepository
     */
    private $article;

    /**
     * UpdateArticleRequest constructor.
     *
     * @param ArticleRepository $article
     */
    public function __construct(ArticleRepository $article)
    {
        parent::__construct();
        $this->article = $article;
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
        $articleId = $this->getArticleIdFromRouteParameter();
        $articleTable = config('articles.table.name.article', 'article');

        return [
            'slug'    => "max:255|unique:$articleTable,slug,$articleId",
            'title'   => 'required|max:255',
            'thumb'   => 'image',
        ];
    }

    private function getArticleIdFromRouteParameter()
    {
        $slug = $this->route()->getParameter('article');
        $article = $this->article->findBy('slug', $slug);

        return $article->id;
    }
}
