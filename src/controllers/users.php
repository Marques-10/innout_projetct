<?php
//  ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
session_start();
requireValidSession(true);

$exception = null;
if(isset($_GET['delete'])){
    try {
        User::deleteById($_GET['delete']);
        addSuccessMsg('Usuário excluído com sucesso.');
    } catch(Exception $e) {
        if(stripos($e->getMessage(), 'FOREIGN KEY')) {
            addErrorMsg('Não é possível excluir o usuário com registro de ponto.');
        } else {
            if($e->getMessage() == 'FOREIGN KEY'){
                addErrorMsg('Não é possível excluir o usuário com registro de ponto.');
            } else {
                $exception = $e;
            }
        }
    }
}

$users = User::get();
foreach($users as $user){
    $user->start_date = (new DateTime($user->start_date))->format('d/m/Y');
    if($user->end_date){
        $user->end_date = (new DateTime($user->end_date))->format('d/m/Y');
    }
}

loadTemplateView('users', ['users' => $users, 'exception' => $exception]);