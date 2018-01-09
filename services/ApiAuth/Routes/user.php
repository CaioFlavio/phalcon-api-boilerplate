<?php
use ApiAuth\Controller\UsersController;

/* Payload
    {
        "username" : (string)
        "webhook_url" : (string)
    }
   Expected Return
    {
        "id" : (int),
        "token_id": (int),
        "username": (string),
        "secret_ket": (string),
        "created_at": (date),
        "updated_at": (date),
        "active": (int)
    }
*/
$phalconRouter->addPost(
    '/user/new',
    [
        new UsersController,
        'newAction',
    ]
);

/* Payload
    {
        "secret_key" : (string)
    }
   Expected Return
    {
        "id" : (int),
        "user_id": (int),
        "token": (string),
        "expires_at": (date),
        "created_at": (date),
        "updated_at": (date),
        "active": (int)
    }
*/
$phalconRouter->addPut(
    '/user/activate',
    [
        new UsersController,
        'activateAction',
    ]
);