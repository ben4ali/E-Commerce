<?php

function session_generate_token(): void
{
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf'])) {
        try {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } catch (Exception $exception) {
            $_SESSION['error'] = "Il y a eu un problème, veuillez réessayer : {$exception->getMessage()}";
            header('Location: user/auth.php');
            exit;
        }
    }
}

function session_match_tokens(string $token): void
{
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($token !== $_SESSION['csrf']) { // probable attaque CSRF
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    } else { // le jeton csrf est valide, regénéré pour la prochaine requête.
        session_generate_token();
    }
}