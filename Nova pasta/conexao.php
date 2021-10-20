<?php
include 'User.php';
$parametros = (array)json_decode(file_get_contents('php://input'),true);
$link = pg_connect("host=localhost port=5432 dbname=teste_cadastro user=postgres password=2032418202lo");

if(empty($parametros)){
    $parametros = $_REQUEST;
}

if ($parametros['classe'] == 'user') {
    $usuario = new User($link, $parametros);

    switch ($parametros['acao']){

        case "selectEstado":
            $arrayResposta = $usuario->selectEstado();
            break;

        case "selectCidade":
            $arrayResposta = $usuario->selectCidade();
            break;

        case "insert":
            $arrayResposta = $usuario->insertCadastro();
            break;

        case "listarClientes":
            $arrayResposta = $usuario->listClientes();
            break;

        case "selectClient":
            $arrayResposta = $usuario->selectClient();
            break;

        case "alterar":
            $arrayResposta = $usuario->alterarClient();
            break;

        case "remove":
            $arrayResposta = $usuario->removeClient();
            break;

        default:
            echo 0;
    }
    echo json_encode($arrayResposta);
    exit;
}
echo json_encode([
    'codigo' => 0,
    'msg' => 'Informe uma acao e ferramenta para utilizar a api!',
    'dados' => []
]);