<?php

return [
    'required'           => 'حقل :attribute مطلوب.',
    'email'              => 'حقل :attribute يجب أن يكون بريداً إلكترونياً صحيحاً.',
    'unique'             => 'قيمة :attribute مستخدمة من قبل.',
    'min'                => [
        'string' => 'حقل :attribute يجب أن يكون على الأقل :min أحرف.',
    ],
    'max'                => [
        'string' => 'حقل :attribute يجب ألا يتجاوز :max أحرف.',
    ],
    'confirmed'          => 'تأكيد :attribute غير متطابق.',
    'email_taken'        => 'البريد الإلكتروني مستخدم بالفعل.',
    'phone_taken'        => 'رقم الهاتف مستخدم بالفعل.',
    'password_confirmed' => 'كلمة المرور وتأكيدها غير متطابقتين.',

    'attributes' => [
        'name'     => 'الاسم',
        'email'    => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone'    => 'رقم الهاتف',
    ],
];