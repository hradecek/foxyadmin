<?php

namespace Foxytouch\Page\Http\Backend\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * <p>
 * Create {@link \Foxytouch\Page\Models\Page page} form request. 
 * </p>
 * <p>
 * Validation criteria:
 * <ul>
 *   <li><i>name</i> is <b>required</b>,</li>
 *   <li><i>name</i> must be max 255 characters long,</li>
 *   <li><i>slug</i> must be unique per article,</li>
 *   <li><i>slug</i> must be max 255 characters long.</li>
 * </ul>
 * </p>
 *
 * @see \Foxytouch\Page\Models\Page
 * @package \Foxytouch\Page\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class CreatePageRequest extends FormRequest
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
        $pageTable = config('pages.table.name.page', 'page');
        
        return [
            'slug'  => "max:255|required|unique:$pageTable",
            'name'  => 'max:255|required',
            'title' => 'max:255',
        ];
    }
}
