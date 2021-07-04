<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{

    /** @var array */
    protected $rules;

    /** @var array */
    protected $requiredAttributes = [];

    protected $assignedMethod = '';

    public function __construct (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->rules = $this->buildDefaultRules();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validatePerHtmlMethod();
        return $this->rules;
    }

    /**
     * Add required attributes dynamically based on extension if needed
     */
    protected function validatePerHtmlMethod () {
        if (!empty($this->assignedMethod) && $this->getMethod() === $this->assignedMethod) {
            $this->addRequiredAttributes();
        }
    }

    protected function addRequiredAttributes () {
        if (count($this->requiredAttributes) > 0) {
            foreach ($this->requiredAttributes as $field) {
                $this->rules[$field][] = 'required';
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public abstract function authorize();

    /**
     * Builds default rule list available for all types of requests
     *
     * @return array
     */
    protected abstract function buildDefaultRules();
}
