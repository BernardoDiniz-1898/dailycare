<?php

return 
[
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute field must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute field confirmation does not match.',
    'min' => [
        'numeric' => 'The :attribute field must be at least :min.',
        'string' => 'The :attribute field must be at least :min characters.',
    ],
    'max' => [
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string' => 'The :attribute field must not be greater than :max characters.',
    ],
    'between' => [
        'numeric' => 'The :attribute field must be between :min and :max.',
    ],
    'numeric' => 'The :attribute field must be a number.',
    'integer' => 'The :attribute field must be an integer.',
    'string' => 'The :attribute field must be a string.',
    'array' => 'The :attribute field must be an array.',
    'boolean' => 'The :attribute field must be true or false.',
    'date' => 'The :attribute field must be a valid date.',
    'after_or_equal' => 'The :attribute field must be a date after or equal to :date.',
    'exists' => 'The selected :attribute is invalid.',
    'in' => 'The selected :attribute is invalid.',
];