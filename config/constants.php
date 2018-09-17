<?php
return [
    'tables'=>[
        //admin 
        'masterAdmin' => 'admin_master',
        'categories' => 'categories',
        'countries' => 'countries',
        'profession' => 'profession',
        'currency' => 'currency',
        'user' => 'user',
        'user_service' => 'user_service',
        'user_token' => 'user_token',
        'staff' => 'staff',
        'client' => 'client',


    ],
    'google'=>[
        'apiKey'=>'AIzaSyDxyJd7QgKrzbLkzcidyq64H_YNyJaNoXA',
    ],

    // STRIPE //
    /***** Test Account *****/
    'stripe'=>[
        'CUSTOMER_ID'=>'ca_B8Dz9Fv0Ws0PWzP6eogVk91xdJpqELoi',
        'PUBLIC_KEY'=>'pk_test_yZ1a1tGQyNOv4sasz1ZvxnXB',
        'SECRET_KEY'=>'sk_test_mYBT9h1w3PgJOPLuBk9RErEe',
        'TOKEN_URI'=>'https://connect.stripe.com/oauth/token',
        'AUTHORIZE_URI'=>'https://connect.stripe.com/oauth/authorize',
    ],


];
?>
