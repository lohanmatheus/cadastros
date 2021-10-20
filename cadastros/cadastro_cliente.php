<?php
require_once(__DIR__ . './header/header.php');

pageHeader('Cadastro de Clientes');
?>
<section class="container" >
    <h1 id="title">Cadastrar Cliente</h1>
    <div id="insert-container" style="display: block; width: 100%;">
        <button type="button" class="btn btn-sm btn-close" onclick="voltarTela()"></button>
        <form method="post" id='insert-form' onsubmit="return false;" >
            <div class="form-group col-md-12 col-lg-6 mb-1">
                <input id="id-user" value="" type="hidden">
                <label for="name">Nome Completo</label>
                <input id="name" class="form-control" placeholder="Insira o nome Completo" required>
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" name="cpf" id="cpf"
                       minlength="14" maxlength="14"
                       placeholder="Digite Apenas numeros do CPF"
                       onkeyup="setMaskCPF(this)"
                       required >
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="rg">RG:</label>
                <input type="text" class="form-control" name="rg" id="rg" maxlength="10" required="required"
                       placeholder="Digite o RG">
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="tel">Telefone:</label>
                <input type="tel" class="form-control" maxlength="15" name="tel" id="tel" required="required"
                        placeholder="Digite um numero de telefone" />
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="email">E-mail:</label>
                <input id="email" name="email" type="email" class="form-control" placeholder="Digite o E-mail" required>
            </div>
            <br/>

            <h5>Endereço </h5>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="rua">Logradouro: </label>
                <input type="text" name="rua" id="rua" class="form-control" required="required"
                       placeholder="Digite a rua" />
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="numero">Numero: </label>
                <input type="text" name="numero" id="numero" class="form-control" required="required"
                       placeholder="Digite o numero da residência" />
            </div>

            <div class="form-group col-md-12 col-lg-6 mb-1">
                <label for="bairro">Bairro: </label>
                <input type="text" name="bairro" id="bairro" class="form-control" required="required"
                       placeholder="Digite o bairro"/>
            </div>

            <br/>
            <div>
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" class="form-control-sm" onclick="removeSelectCidade()" onchange="selectCidade(this.value)" required>
                    <option value="" selected>Selecione um estado para exibir as cidades</option>
                </select>
            </div>
            <br/>
            <div>
                <label for="cidade">Cidade:</label>
                <select id="cidade" name="cidade"  class="form-control-sm" required disabled>
                    <option value="">Selecione um estado</option>
                </select>
            </div>
            <br/>

            <div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="voltarTela()">Cancelar</button>
                <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
            </div>

        </form>
    </div>
</section>
<?php
require_once(__DIR__ . './footer/footer.php');
?>
<script src="script/cadastro_cliente.js"></script>
<script>
    window.onload = function(){
        id('tel').onkeyup = function () {
            mascara(this, mtel);
        }
        document.getElementById(`insert-form`).addEventListener(`submit`, verificarCadastro)
        selectEstado()

        let idClientUpdate = localStorage.getItem("idClientUpdate") || '';
        console.log('idClientUpdate',idClientUpdate)
        if (idClientUpdate.length > 0) {
            popularFormUpdate(idClientUpdate)
        }

       stopPageLoader()
    }

</script>
</body>
</html>
