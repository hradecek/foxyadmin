<?php

namespace Foxytouch\Article\Http\Backend\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * <p>
 * Create {@link \Foxytouch\Article\Models\Category category} form request.
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
class CreateCategoryRequest extends FormRequest
{
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
        return [
            'name' => 'required|max:255|unique:category'
        ];
    }
}
