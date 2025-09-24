<?php

namespace Api\Workspaces\Requests;

use Auth;
use Api\Core\BaseFormRequest;
use Illuminate\Validation\Rule;

class CrupdateWorkspaceRequest extends BaseFormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $required = $this->getMethod() === 'POST' ? 'required' : '';
        $ignore = $this->getMethod() === 'PUT' ? $this->route('workspace')->id : '';
        $userId = $this->route('workspace') ? $this->route('workspace')->user_id : Auth::id();

        return [
            'name' => [
                $required, 'string', 'min:3',
                Rule::unique('workspaces')->where('owner_id', $userId)->ignore($ignore)
            ],
        ];
    }
}

