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

    'accepted'             => 'El campo debe ser aceptado.',
    'active_url'           => 'El campo no es una URL válida.',
    'after'                => 'El campo debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo solo puede contener letras.',
    'alpha_dash'           => 'El campo solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => 'El campo solo puede contener letras y números.',
    'array'                => 'El campo debe ser un array.',
    'before'               => 'El campo debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'Entre :min y :max.',
        'file'    => 'Entre :min y :max kilobytes.',
        'string'  => 'Entre :min y :max caracters',
        'array'   => 'Entre :min y :max elementos.',
    ],
    'boolean'              => 'El campo debe ser verdadero o falso.',
    'confirmed'            => 'El campo confirmación de no coincide.',
    'date'                 => 'El campo no corresponde con una fecha válida.',
    'date_equals'          => 'El campo debe ser una fecha igual a :date.',
    'date_format'          => 'El campo no corresponde con el formato de fecha :format.',
    'different'            => 'Los campos y :other deben ser diferentes.',
    'digits'               => 'El campo debe ser un número de :digits dígitos.',
    'digits_between'       => 'El campo debe contener entre :min y :max dígitos.',
    'dimensions'           => 'El campo tiene dimensiones de imagen inválidas.',
    'distinct'             => 'El campo tiene un valor duplicado.',
    'email'                => 'El campo debe ser una dirección de correo válida.',
    'ends_with'            => 'El campo debe finalizar con alguno de los siguientes valores: :values',
    'exists'               => 'no existe.',
    'file'                 => 'El campo debe ser un archivo.',
    'filled'               => 'El campo debe tener un valor.',
    'gt'                   => [
        'numeric' => 'El campo debe ser mayor a :value.',
        'file'    => 'El archivo debe pesar más de :value kilobytes.',
        'string'  => 'El campo debe contener más de :value caracteres.',
        'array'   => 'El campo debe contener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'El campo debe ser mayor o igual a :value.',
        'file'    => 'El archivo debe pesar :value o más kilobytes.',
        'string'  => 'El campo debe contener :value o más caracteres.',
        'array'   => 'El campo debe contener :value o más elementos.',
    ],
    'image'                => 'El campo debe ser una imagen.',
    'in'                   => 'El campo es inválido.',
    'in_array'             => 'El campo no existe en :other.',
    'integer'              => 'El campo debe ser un número entero.',
    'ip'                   => 'El campo debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo debe ser una cadena de texto JSON válida.',
    'lt'                   => [
        'numeric' => 'El campo debe ser menor a :value.',
        'file'    => 'El archivo debe pesar menos de :value kilobytes.',
        'string'  => 'El campo debe contener menos de :value caracteres.',
        'array'   => 'El campo debe contener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'El campo debe ser menor o igual a :value.',
        'file'    => 'El archivo debe pesar :value o menos kilobytes.',
        'string'  => 'El campo debe contener :value o menos caracteres.',
        'array'   => 'El campo debe contener :value o menos elementos.',
    ],
    'max'                  => [
        'numeric' => 'Máximo :max.',
        'file'    => 'Tamaño máximo :max kilobytes.',
        'string'  => 'Máx. :max caracteres.',
        'array'   => 'Máx. :max elementos.',
    ],
    'mimes'                => 'El campo debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El campo debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'Mínimo :min.',
        'file'    => 'El archivo debe pesar al menos :min kilobytes.',
        'string'  => 'El campo debe contener al menos :min caracteres.',
        'array'   => 'El campo debe contener al menos :min elementos.',
    ],
    'not_in'               => 'El valor es inválido.',
    'not_regex'            => 'El formato  es inválido.',
    'numeric'              => 'El valor debe ser un número.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => 'El campo debe estar presente.',
    'regex'                => 'El formato del campo es inválido.',
    'required'             => 'Falta el campo  requerido.',
    'required_if'          => 'El campo es obligatorio cuando el campo :other es :value.',
    'required_unless'      => 'El campo es requerido a menos que :other se encuentre en :values.',
    'required_with'        => 'El campo es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo es obligatorio cuando ninguno de los campos :values están presentes.',
    'same'                 => 'Las contraseñas deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo debe ser :size.',
        'file'    => 'El archivo debe pesar :size kilobytes.',
        'string'  => 'El campo debe contener :size caracteres.',
        'array'   => 'El campo debe contener :size elementos.',
    ],
    'starts_with'          => 'El campo debe comenzar con uno de los siguientes valores: :values',
    'string'               => 'El campo debe ser una cadena de caracteres.',
    'timezone'             => 'El campo debe ser una zona horaria válida.',
    'unique'               => 'El valor del campo ya está en uso.',
    'uploaded'             => 'El archivo no se pudo subir.',
    'url'                  => 'El formato del campo es inválido.',
    'uuid'                 => 'El campo debe ser un UUID válido.',

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
        'form.cant'=>"Cantidad",
        'form.price'=>"Precio",
        'form.cost'=>"Costo",
        'form.type'=>"Tipo",
        'form.name'=>"Nombre",
        'form.lastname'=>"Apellidos",
        'form.description'=>"Descripción",
        'form.provider_id'=>"Proveedor",
        'form.unit_id'=>"Unidad",
        'form.client_id'=>"Cliente",
        'form.product_id'=>"Producto",
    ],

];