<?php

namespace Foxytouch\Article\Http\Backend\Requests;

use Foxytouch\Article\Repositories\Contracts\CategoryRepository;

use Illuminate\Foundation\Http\FormRequest;

/**
 * <p>
 * Update {@link \Foxytouch\Article\Models\Category category} form request.
 * </p>
 * <p>
 * Validation criteria:
 * <ul>
 *   <li><i>name</i> is <b>required</b>,</li>
 *   <li><i>name</i> must be <b>unique</b> per category,</li>
 *   <li><i>name</i> must be maximum 255 characters long.</li>
 * </ul>
 * </p>
 *
 * @see \Foxytouch\Article\Models\Category
 * @package \Foxytouch\Article\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UpdateCategoryRequest extends FormRequest
{
    /**
     * @var CategoryRepository
     */
    private $category;

    /**
     * UpdateCategoryRequest constructor.
     * 
     * @param CategoryRepository $category
     */
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->category = $category;
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
        $categoryId = $this->getCategoryIdFromRouteParameter();

        return [
            'name' => "required|max:255|unique:category,name,$categoryId",
        ];
    }

    private function getCategoryIdFromRouteParameter()
    {
        $name = $this->route()->getParameter('category');
        $category = $this->category->findBy('name', $name);

        return $category->id;
    }
}
