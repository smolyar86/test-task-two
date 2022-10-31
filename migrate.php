<?php
require 'boot.php';

$db = \App\Connection::getInstance()->getPdo();

// create table
$db->prepare(
    'create table if not exists users
    (
        id int auto_increment,
        name varchar(64) not null,
        email varchar(256) not null,
        constraint users_pk
            primary key (id),
        constraint users_email
            unique (email)
    );'
)->execute();

//clear data
$db->prepare('truncate table users')->execute();

//insert data
$faker = Faker\Factory::create();
for ($i = 1; $i <=5; $i++) {
    $name = $faker->unique()->name;
    $email = $faker->unique()->email;
    $db->prepare("insert into users(name, email) values ('$name', '$email');")->execute();
}





