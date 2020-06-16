<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\App\Entidades\Sca\M0001;
use Illuminate\Support\Facades\Hash;
use App\Entidades\Sca\M0002 as Perfil;


$factory->define(App\Entidades\Sca\M0001::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));
    $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
    $faker->addProvider(new \Faker\Provider\pt_BR\Internet($faker));
    $faker->addProvider(new \Faker\Provider\pt_BR\Payment($faker));
    $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
    $faker->addProvider(new \Faker\Provider\pt_BR\PhoneNumber($faker));

    return [
        'nm_usuario' => $faker->firstName. ' ' . $faker->lastName,
        'nm_apelido' => $faker->word,
        'nr_cpf' => $faker->cpf(false),
        'dt_nasc_usu' => $faker->dateTimeThisCentury()->format('Ymd'),
        'in_sexo_usu' => $faker->randomElement(['M', 'F']),
        'ds_email_usu' => $faker->unique()->safeEmail,
        'nr_cel_usu' => $faker->areaCode.$faker->unique()->cellPhone(false),
        //TODO: Pegar uma permissão do relacionamento
        // 'cd_perm_usu' => ,
        'cd_perm_usu' => Perfil::where('nm_perm_usu', 'Usuario')->first()->cd_perm_usu,
        'ds_snh_usu' => 'usuario1', // password
        'dt_exp_snh_usu' => now()->format('Ymd'),
        //Omitir ST_USU.
        'ds_snh_usu_ant' => ' ', // password
        'dt_exp_snh_usu_ant' => now()->format('Ymd'), // password
    ];
});
