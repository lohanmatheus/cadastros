<?php

class User
{
    private $dbConnect = null;
    private $parametros = null;

    public function __construct($dbConnect, $parametros)
    {
        if ($parametros)
            $this->parametros = $parametros;

        if ($dbConnect)
            $this->dbConnect = $dbConnect;
    }

    public function selectEstado()
    {
        $query = "SELECT * FROM teste_cadastro.cadastro.estados ORDER BY id";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }


            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Registros selecionado com sucesso!',
                'dados' => $resultSet
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function selectCidade()
    {
        $idEstado = (int)$this->parametros['idEstado'];
        if (!$idEstado) {
            return [
                'codigo' => 0,
                'msg' => 'Id do estado nao chegou ao sistema!',
                'dados' => []
            ];
        }

        $query = "SELECT * FROM teste_cadastro.cadastro.municipio WHERE id_estado = '$idEstado'";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro de cidades nao encontrado!',
                    'dados' => []
                ];
            }

            $resultSet = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultSet[] = $row;
            }
            $contadorCidades = count($resultSet);

            return [
                'codigo' => 1,
                'msg' => 'Registros selecionado com sucesso!',
                'dados' => $resultSet,
                'quantidade' => $contadorCidades
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function insertCadastro()
    {
        $dataUser = (array)$this->parametros['data'];
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cpf = filter_var($dataUser['cpf'], FILTER_SANITIZE_SPECIAL_CHARS);
        $rg = filter_var($dataUser['rg'], FILTER_SANITIZE_SPECIAL_CHARS);
        $tel = filter_var($dataUser['tel'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($dataUser['email'], FILTER_SANITIZE_SPECIAL_CHARS);
        $rua = filter_var($dataUser['rua'], FILTER_SANITIZE_SPECIAL_CHARS);
        $numero = filter_var($dataUser['numero'], FILTER_SANITIZE_SPECIAL_CHARS);
        $bairro = filter_var($dataUser['bairro'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cidade = filter_var($dataUser['cidade'], FILTER_SANITIZE_SPECIAL_CHARS);

        if ($cidade == 'Selecione') {
            return [
                'codigo' => 0,
                'msg' => 'Selecione a cidade!',
                'dados' => []
            ];
        }

        if (strlen($cpf) > 14 or strlen($cpf) < 14) {
            return [
                'codigo' => 0,
                'msg' => 'CPF incorreto!',
                'dados' => []
            ];
        }

        $queryCpf = "SELECT * FROM teste_cadastro.cadastro.cliente WHERE cpf = '$cpf' ";
        $queryRg = "SELECT * FROM teste_cadastro.cadastro.cliente WHERE rg = '$rg' ";
        $queryEmail = "SELECT * FROM teste_cadastro.cadastro.cliente WHERE email = '$email' ";
        $queryTel = "SELECT * FROM teste_cadastro.cadastro.cliente WHERE telefone = '$tel' ";


        try {
            $resultCpf = pg_query($this->dbConnect, $queryCpf);

            if (pg_affected_rows($resultCpf) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'CPF ja existe no sistema!',
                    'dados' => []
                ];
            }

            $resultRg = pg_query($this->dbConnect, $queryRg);

            if (pg_affected_rows($resultRg) > 0) {

                return [
                    'codigo' => 0,
                    'msg' => 'RG ja existe no sistema!',
                    'dados' => []
                ];
            }

            $resultEmail = pg_query($this->dbConnect, $queryEmail);

            if (pg_affected_rows($resultEmail) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Email ja existe no sistema!',
                    'dados' => []
                ];
            }
            $resultTel = pg_query($this->dbConnect, $queryTel);

            if (pg_affected_rows($resultTel) > 0) {
                return [
                    'codigo' => 0,
                    'msg' => 'Telefone ja existe no sistema!',
                    'dados' => []
                ];
            }

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => 'Falha em verificação de dados!',
                'dados' => []
            ];
        }

        $lista = [$name, $cpf, $rg, $tel, $email, $rua, $numero, $bairro, $cidade];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos obrigatorios nao preenchidos!',
                    'dados' => []
                ];
            }
        }

        $queryInsertClient = "INSERT INTO
            teste_cadastro.cadastro.cliente(nome, cpf, rg, telefone, email, logradouro, numero,
                                            bairro , id_cidade)
            VALUES ('$name', '$cpf', '$rg', '$tel', '$email', '$rua', '$numero', '$bairro', '$cidade')";

        try {
            $resultInsertClient = pg_query($this->dbConnect, $queryInsertClient);

            if (pg_affected_rows($resultInsertClient) < 1) {
                return [
                    'codigo' => 0,
                    'msg' => 'Erro ao inserir dados do Cliente!',
                    'dados' => []
                ];
            }
            return [
                'codigo' => 1,
                'msg' => 'Registro inserido com sucesso!',
                'dados' => []
            ];
        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function listClientes()
    {

        $query = "SELECT cliente.*, cidade.nome AS nome_cidade, estado.uf, estado.id AS id_estado
                    FROM teste_cadastro.cadastro.cliente AS cliente
                    JOIN teste_cadastro.cadastro.municipio AS cidade ON cidade.id = cliente.id_cidade
                    JOIN teste_cadastro.cadastro.estados AS estado ON cidade.id_estado = estado.id 
                    ORDER BY id DESC ";

        try {
            $result = pg_query($this->dbConnect, $query);
            if (pg_affected_rows($result) <= 0) {
                return [
                    'codigo' => 0,
                    'msg' => "Nenhum registro encontrado no sistema!",
                    'dados' => []
                ];
            }

            $resultClient = [];
            while ($row = pg_fetch_assoc($result)) {
                $resultClient[] = $row;
            }

            return [
                'codigo' => 1,
                'msg' => 'Listado com sucesso!',
                'dados' => $resultClient
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage(),
                'dados' => []
            ];
        }
    }

    public function selectClient()
    {
        $idClient = (int)$this->parametros['id'];
        if ($idClient <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Informe o id do registro a qual deseja selecionar.',
                'dados' => []
            ];
        }

        $query = "SELECT cliente.*, cidade.nome AS nome_cidade, estado.nome AS estado, estado.id AS id_estado
                     FROM teste_cadastro.cadastro.cliente AS cliente
                     JOIN teste_cadastro.cadastro.municipio AS cidade ON cidade.id = cliente.id_cidade
                     JOIN teste_cadastro.cadastro.estados AS estado ON cidade.id_estado = estado.id                     
                  WHERE cliente.id = '$idClient' ";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Registro nao encontrado!',
                    'dados' => []
                ];
            }

            $row = pg_fetch_assoc($result);

            return [
                'codigo' => 1,
                'msg' => 'Registro selecionado com sucesso!',
                'dados' => $row
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function alterarClient()
    {
        $dataUser = (array)$this->parametros['data'];
        $id = (int)filter_var($dataUser['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $name = filter_var($dataUser['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cpf = filter_var($dataUser['cpf'], FILTER_SANITIZE_SPECIAL_CHARS);
        $rg = filter_var($dataUser['rg'], FILTER_SANITIZE_SPECIAL_CHARS);
        $tel = filter_var($dataUser['tel'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($dataUser['email'], FILTER_SANITIZE_SPECIAL_CHARS);
        $rua = filter_var($dataUser['rua'], FILTER_SANITIZE_SPECIAL_CHARS);
        $numero = filter_var($dataUser['numero'], FILTER_SANITIZE_SPECIAL_CHARS);
        $bairro = filter_var($dataUser['bairro'], FILTER_SANITIZE_SPECIAL_CHARS);
        $cidade = filter_var($dataUser['cidade'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (strlen($cpf) > 14 or strlen($cpf) < 14) {
            return [
                'codigo' => 0,
                'msg' => 'CPF incorreto!',
                'dados' => []
            ];
        }

        if ($cidade == 'Selecione') {
            return [
                'codigo' => 0,
                'msg' => 'Selecione a cidade!',
                'dados' => []
            ];
        }

        $querySlc = "SELECT cpf, rg, email, telefone FROM teste_cadastro.cadastro.cliente
                        WHERE id != '$id'";
        try {
            $resultSlc = pg_query($this->dbConnect, $querySlc);

            while ($rowSlc = pg_fetch_assoc($resultSlc)) {

                if ($cpf == $rowSlc['cpf']) {
                    return [
                        'codigo' => 0,
                        'msg' => 'CPF ja existe no sistema!',
                        'dados' => []
                    ];
                } else if ($rg == $rowSlc['rg']) {
                    return [
                        'codigo' => 0,
                        'msg' => 'RG ja existe no sistema!',
                        'dados' => []
                    ];
                } else if ($email == $rowSlc['email']) {
                    return [
                        'codigo' => 0,
                        'msg' => 'Email ja existe no sistema!',
                        'dados' => []
                    ];
                } else if ($tel == $rowSlc['telefone']) {
                    return [
                        'codigo' => 0,
                        'msg' => 'Telefone ja existe no sistema!',
                        'dados' => []
                    ];
                }
            }


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }

        if ($id <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'id do registro nao chegou ao sistema.',
                'dados' => []
            ];
        }

        $lista = [$name, $cpf, $rg, $tel, $email, $rua, $numero, $bairro, $cidade];

        foreach ($lista as $linha) {
            if (empty($linha)) {
                return [
                    'codigo' => 0,
                    'msg' => 'Campos de alteração nao Recebidos!',
                    'dados' => []
                ];
            }
        }

        $queryUpdate = "UPDATE teste_cadastro.cadastro.cliente 
                     SET nome = '$name',
                         cpf = '$cpf',
                         rg = '$rg',
                         telefone = '$tel',
                         email = '$email',
                         logradouro = '$rua',
                         numero = '$numero',
                         bairro = '$bairro',
                         id_cidade = '$cidade'                        
                   WHERE id = '$id' ";

        try {
            $resultUpdate = pg_query($this->dbConnect, $queryUpdate);
            if (!$resultUpdate) {
                return [
                    'codigo' => 0,
                    'msg' => pg_errormessage($this->dbConnect),
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Alterado com sucesso!',
                'dados' => []
            ];

        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function removeClient()
    {
        $idUser = (int)$this->parametros['id'];
        if ($idUser <= 0) {
            return [
                'codigo' => 0,
                'msg' => 'Id do registro a qual deseja remover não chegou ao sistema.',
            ];
        }

        $query = "DELETE FROM teste_cadastro.cadastro.cliente WHERE id = '$idUser' ";
        try {
            $result = pg_query($this->dbConnect, $query);
            if (!$result) {
                return [
                    'codigo' => 0,
                    'msg' => 'Falha ao tentar excluir!',
                    'dados' => []
                ];
            }

            return [
                'codigo' => 1,
                'msg' => 'Registro removido com sucesso!',
                'dados' => []
            ];


        } catch (\Exception $exception) {
            return [
                'codigo' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }

}