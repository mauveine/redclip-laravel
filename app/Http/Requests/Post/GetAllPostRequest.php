<?php


namespace App\Http\Requests\Post;


use Illuminate\Validation\Rule;

class GetAllPostRequest extends BasePostRequest
{
    protected $method = 'GET';

    public function buildDefaultRules () {
        $rules = parent::buildDefaultRules();
        return array_merge(
            $rules,
            [
                'page' => [
                    'min:1',
                    'numeric'
                ],
                'perPage' => [
                    'min:2',
                    'numeric',
                    'max:50'
                ],
                'order' => [
                    'nullable',
                    Rule::in(['asc', 'desc'])
                ]
            ]
        );
    }

}