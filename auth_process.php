<?php

    require_once("globals.php");
    require_once("config.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // Resgata o tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    // Verificação do tipo de formulário
    if($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Verificação de dados mínimos
        if($name && $lastname && $email && $password) {

            // Verificar se as senhas são iguais
            if ($password === $confirmpassword) {

                // Verifica se o e-mail já está cadastrado
                if ($userDao->findByEmail($email) === false) {

                    $user = new User();

                    // Criação de Token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);
                    
                } else {

                    // Mensagem de erro, usuário já cadastrado
                    $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error" , "back");
                }
                

            } else {

                // Enviar uma mensagem caso as senhas não sejam iguais
                $message->setMessage("As senhas não são iguais.", "error" , "back");

            }

        } else {

            // Enviar uma mensagem de erro, de dados faltantes 
            $message->setMessage("Por favor, preencha todos os campos.", "error" , "back");

        }

    } else if($type === "login") {

        echo "Oi";

    }