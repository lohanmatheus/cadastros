<?php
require_once(__DIR__ .'./header/header.php');

pageHeader('Clientes Cadastrados');
?>
<section class="container-fluid">
    <header>
        <div style="text-align: center">
            <h1 class="display-5" id="titulo"> Clientes Cadastrados</h1>
        </div>
    </header>
    <div class="row" id="tabela-clientes">
        <div class="col-12">
            <div id="product-request-adm" class="card" style="">
                <div class="card-header pt-3">
                    <div style="float: left;">
                        <h5 class="card-title">Lista</h5>
                    </div>
                    <div style="float: right;">
                        <button type="button" class="btn btn-primary"
                                onclick="locationCadastroCliente()">Novo Cliente
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="area-menu-request-adm" style="display: block" class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-secondary text-light text-uppercase">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Email</th>
                                <th scope="col">CIDADE</th>
                                <th scope="col" colspan="2">Opcoes</th>
                            </tr>
                            </thead>
                            <tbody id="list-client">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-foot p-1" style="text-align: right;">

                </div>

            </div>
        </div>
    </div>

</section>
<?php
require_once(__DIR__ . './footer/footer.php');
?>
<script src="script/listar_clientes.js"></script>
<script>
    function locationCadastroCliente(){
        window.location.href = "cadastro_cliente.php";
    }

    window.onload = function(){
        listarClientes()
        stopPageLoader()
    }
</script>

</body>
</html>