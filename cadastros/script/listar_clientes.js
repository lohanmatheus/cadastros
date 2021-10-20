function listarClientes() {
    const getTR = registro => {
        return `<tr id="tr-${registro.id}">
                    <td>${registro.id}</td>
                    <td>${registro.nome}</td>
                    <td>${registro.cpf}</td>
                    <td>${registro.email}</td>
                    <td>${registro.nome_cidade}/${registro.uf}</td>                    
                    <td><button type="button" class="btn btn-sm btn-warning"
                     onclick="alterar('${registro.id}');">Alterar
                     </button></td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="remover('${registro.id}','${registro.nome}')">Excluir</button></td>
                </tr>`;
    }

    const getLoaginTr = function () {
        return `<tr>
                    <td colspan="8" style="text-align: center;" ">Carregando dados. Aguarde!</td>
                </tr>`;
    }

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'listarClientes'
    })
    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: bodyRequest,
        headers: {
            'Content-Type': 'application/json'
        }
    }
    let myRequest = new Request('http://localhost:8000/conexao.php', configRequest);
    document.getElementById('list-client').innerHTML = getLoaginTr();
    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            document.getElementById('list-client').innerHTML = '';
            response.dados.forEach(registro => {
                document.getElementById('list-client').innerHTML += getTR(registro);
            })
            return false;
        }

        document.getElementById('list-client').innerHTML = `<tr>
                        <td colspan="8">${response.msg}</td>
                    </tr>`;
    })
}

const alterar = (idClientUpdate) => {
    startPageLoader();
    if(!confirm(`Confirmar alteracao do id ${idClientUpdate} ?`)){stopPageLoader(); return false;}
    localStorage.setItem("idClientUpdate", idClientUpdate)
    location.href = `http://${location.host}/cadastro_cliente.php`
}

function remover(id, nome) {
    startPageLoader();
    if (id === '') {
        alert("Informe o ID")
        return;
    }

    if (!confirm(`Confirmar exclusao do cadastro do ${nome}, id ${id} ?`)){
        stopPageLoader();
        return;
    }


    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: JSON.stringify({
            id,
            classe: 'user',
            acao: 'remove'
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    let myRequest = new Request('http://localhost:8000/conexao.php', configRequest);

    fetch(myRequest).then(response => {
        return response.json();
    }).then(function (response) {
        alert(response.msg)
        document.getElementById(`tr-${id}`).remove()

        stopPageLoader();
    })
}
