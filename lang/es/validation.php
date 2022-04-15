<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El campo <b>:attribute</b> debe ser aceptado.',
    'active_url'           => 'El campo <b>:attribute</b> no es una URL válida.',
    'after'                => 'El campo <b>:attribute</b> debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo <b>:attribute</b> debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo <b>:attribute</b> solo puede contener letras.',
    'alpha_dash'           => 'El campo <b>:attribute</b> solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => 'El campo <b>:attribute</b> solo puede contener letras y números.',
    'array'                => 'El campo <b>:attribute</b> debe ser un array.',
    'before'               => 'El campo <b>:attribute</b> debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo <b>:attribute</b> debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo <b>:attribute</b> debe ser un valor entre :min y :max.',
        'file'    => 'El archivo debe pesar entre :min y :max kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener entre :min y :max caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener entre :min y :max elementos.',
    ],
    'boolean'              => 'El campo <b>:attribute</b> debe ser verdadero o falso.',
    'confirmed'            => 'El campo <b>:attribute</b> confirmación de no coincide.',
    'date'                 => 'El campo <b>:attribute</b> no corresponde con una fecha válida.',
    'date_equals'          => 'El campo <b>:attribute</b> debe ser una fecha igual a :date.',
    'date_format'          => 'El campo <b>:attribute</b> no corresponde con el formato de fecha :format.',
    'different'            => 'Los campo <b>:attribute</b>s y :other deben ser diferentes.',
    'digits'               => 'El campo <b>:attribute</b> debe ser un número de :digits dígitos.',
    'digits_between'       => 'El campo <b>:attribute</b> debe contener entre :min y :max dígitos.',
    'dimensions'           => 'El campo <b>:attribute</b> tiene dimensiones de imagen inválidas.',
    'distinct'             => 'El campo <b>:attribute</b> tiene un valor duplicado.',
    'email'                => 'El campo <b>:attribute</b> debe ser una dirección de correo válida.',
    'ends_with'            => 'El campo <b>:attribute</b> debe finalizar con alguno de los siguientes valores: :values',
    'exists'               => 'no existe.',
    'file'                 => 'El campo <b>:attribute</b> debe ser un archivo.',
    'filled'               => 'El campo <b>:attribute</b> debe tener un valor.',
    'gt'                   => [
        'numeric' => 'El campo <b>:attribute</b> debe ser mayor a :value.',
        'file'    => 'El archivo debe pesar más de :value kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener más de :value caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'El campo <b>:attribute</b> debe ser mayor o igual a :value.',
        'file'    => 'El archivo debe pesar :value o más kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener :value o más caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener :value o más elementos.',
    ],
    'image'                => 'El campo <b>:attribute</b> debe ser una imagen.',
    'in'                   => 'El campo <b>:attribute</b> es inválido.',
    'in_array'             => 'El campo <b>:attribute</b> no existe en :other.',
    'integer'              => 'El campo <b>:attribute</b> debe ser un número entero.',
    'ip'                   => 'El campo <b>:attribute</b> debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo <b>:attribute</b> debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo <b>:attribute</b> debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo <b>:attribute</b> debe ser una cadena de texto JSON válida.',
    'lt'                   => [
        'numeric' => 'El campo <b>:attribute</b> debe ser menor a :value.',
        'file'    => 'El archivo debe pesar menos de :value kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener menos de :value caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'El campo <b>:attribute</b> debe ser menor o igual a :value.',
        'file'    => 'El archivo debe pesar :value o menos kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener :value o menos caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener :value o menos elementos.',
    ],
    'max'                  => [
        'numeric' => 'El campo <b>:attribute</b> no debe ser mayor a :max.',
        'file'    => 'El archivo no debe pesar más de :max kilobytes.',
        'string'  => 'El campo <b>:attribute</b> no debe contener más de :max caracteres.',
        'array'   => 'El campo <b>:attribute</b> no debe contener más de :max elementos.',
    ],
    'mimes'                => 'El campo <b>:attribute</b> debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El campo <b>:attribute</b> debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El campo <b>:attribute</b> debe ser al menos :min.',
        'file'    => 'El archivo debe pesar al menos :min kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener al menos :min caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener al menos :min elementos.',
    ],
    'not_in'               => 'El campo <b>:attribute</b> seleccionado es inválido.',
    'not_regex'            => 'El formato del campo <b>:attribute</b> es inválido.',
    'numeric'              => 'El campo <b>:attribute</b> debe ser un número.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => 'El campo <b>:attribute</b> debe estar presente.',
    'regex'                => 'El formato del campo <b>:attribute</b> es inválido.',
    'required'             => 'Falta el Campo <b>:attribute</b> que es requerido.',
    'required_if'          => 'El campo <b>:attribute</b> es obligatorio cuando el campo <b>:attribute</b> :other es :value.',
    'required_unless'      => 'El campo <b>:attribute</b> es requerido a menos que :other se encuentre en :values.',
    'required_with'        => 'El campo <b>:attribute</b> es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo <b>:attribute</b> es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo <b>:attribute</b> es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo <b>:attribute</b> es obligatorio cuando ninguno de los campo <b>:attribute</b>s :values están presentes.',
    'same'                 => 'Las contraseñas deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo <b>:attribute</b> debe ser :size.',
        'file'    => 'El archivo debe pesar :size kilobytes.',
        'string'  => 'El campo <b>:attribute</b> debe contener :size caracteres.',
        'array'   => 'El campo <b>:attribute</b> debe contener :size elementos.',
    ],
    'starts_with'          => 'El campo <b>:attribute</b> debe comenzar con uno de los siguientes valores: :values',
    'string'               => 'El campo <b>:attribute</b> debe ser una cadena de caracteres.',
    'timezone'             => 'El campo <b>:attribute</b> debe ser una zona horaria válida.',
    'unique'               => 'El valor del campo <b>:attribute</b> ya está en uso.',
    'uploaded'             => 'El campo <b>:attribute</b> no se pudo subir.',
    'url'                  => 'El formato del campo <b>:attribute</b> es inválido.',
    'uuid'                 => 'El campo <b>:attribute</b> debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for using the
    | convention rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a givenrule.
    |
    */

    'custom' => [
        'name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swapplace-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'=>'Nombre',
        'lastname'=>'Apellido',
        'price'=>'Precio',
        'description'=>'Descripción',
        'cost'=>"Costo",
        "email"=>"Correo",
        'password'=>"Contraseña",
        'phone'=>"Teléfono",
        'cant'=>"Cantidad",
        'code'=>"Código",
        'form.product_id'=>"Producto",
        'form.cant'=>"Cantidad",
        'form.price'=>"Precio",
        'form.cost'=>"Costo",
        'form.name'=>"Nombre",
        'form.lastname'=>"Apellidos",
        'form.description'=>"Descripción",
        'form.provider_id'=>"Proveedor",
        'form.unit_id'=>"Unidad",
        'form.client_id'=>"Cliente",
        'form.product_id'=>"Producto",
    ],

];