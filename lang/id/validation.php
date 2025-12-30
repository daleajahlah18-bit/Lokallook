<?php

return [

    'accepted'        => ':attribute harus diterima.',
    'active_url'      => ':attribute bukan URL yang valid.',
    'after'           => ':attribute harus tanggal setelah :date.',
    'alpha'           => ':attribute hanya boleh berisi huruf.',
    'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
    'array'           => ':attribute harus berupa array.',
    'before'          => ':attribute harus tanggal sebelum :date.',
    'between' => [
        'numeric' => ':attribute harus antara :min dan :max.',
        'file'    => ':attribute harus antara :min dan :max kilobyte.',
        'string'  => ':attribute harus antara :min dan :max karakter.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'         => ':attribute harus bernilai benar atau salah.',
    'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
    'date'            => ':attribute bukan tanggal yang valid.',
    'email'           => ':attribute harus berupa alamat email yang valid.',
    'exists'          => ':attribute yang dipilih tidak valid.',
    'file'            => ':attribute harus berupa file.',
    'filled'          => ':attribute harus diisi.',
    'image'           => ':attribute harus berupa gambar.',
    'in'              => ':attribute yang dipilih tidak valid.',
    'integer'         => ':attribute harus berupa angka.',
    'max' => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobyte.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'file'    => ':attribute minimal :min kilobyte.',
        'string'  => ':attribute minimal :min karakter.',
        'array'   => ':attribute minimal memiliki :min item.',
    ],
    'not_in'          => ':attribute yang dipilih tidak valid.',
    'numeric'         => ':attribute harus berupa angka.',
    'required'        => ':attribute wajib diisi.',
    'same'            => ':attribute dan :other harus sama.',
    'size' => [
        'numeric' => ':attribute harus :size.',
        'file'    => ':attribute harus :size kilobyte.',
        'string'  => ':attribute harus :size karakter.',
        'array'   => ':attribute harus mengandung :size item.',
    ],
    'string'          => ':attribute harus berupa teks.',
    'timezone'        => ':attribute harus zona waktu valid.',
    'unique'          => ':attribute sudah digunakan.',
    'uploaded'        => ':attribute gagal diupload.',
    'url'             => ':attribute harus berupa URL valid.',

    'attributes' => [
        'email'    => 'email',
        'password' => 'kata sandi',
        'name'     => 'nama',
    ],

];
