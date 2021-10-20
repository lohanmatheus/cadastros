function selectEstado() {
    let verificaSelect = document.getElementById('estado').getElementsByTagName('option');
    let select = document.getElementById("estado");

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectEstado'
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
    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(response => {
        if (response.codigo === 1) {
            response.dados.forEach(registro => {

                let option = document.createElement("option");
                option.value = registro.id;
                option.text = registro.nome;

                if (verificaSelect.length < 28) {
                    select.appendChild(option)
                }
            });
        }
    })
}

async function selectCidade(idEstado = '') {
    if (idEstado === '' || idEstado.length < 1)
        return false;

    let verificaSelect = document.getElementById('cidade').getElementsByTagName('option');
    let select = document.getElementById("cidade");
    select.innerHTML = '<option value="">Selecione um estado...</option>'
    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectCidade',
        idEstado
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
    let response = await fetch(myRequest).then(response =>response.json());


    if (response.codigo === 1) {
        response.dados.forEach(registro => {

            let option = document.createElement("option");
            option.value = registro.id;
            option.text = registro.nome;

            if (verificaSelect.length < response.quantidade) {
                select.appendChild(option)
            }
        });

        select.disabled = false;
    }

}

const verificarCadastro = function (e) {
    e.preventDefault()
    if (confirm("Todos os dados estao corretos ?")) {
        let name = document.getElementById('name').value
        let cpf = document.getElementById('cpf').value
        let rg = document.getElementById('rg').value
        let tel = document.getElementById('tel').value
        let email = document.getElementById('email').value
        let rua = document.getElementById('rua').value
        let numero = document.getElementById('numero').value
        let bairro = document.getElementById('bairro').value
        let estado = document.getElementById('estado').value
        let cidade = document.getElementById('cidade').value

        enviarCadastro(name, cpf, rg, tel, email, rua, numero, bairro, estado, cidade);
    }
}

function enviarCadastro(name, cpf, rg, tel, email, rua, numero, bairro, estado, cidade) {

    const params = {
        data: {
            name: name,
            cpf: cpf,
            rg: rg,
            tel: tel,
            email: email,
            rua: rua,
            numero: numero,
            bairro: bairro,
            estado: estado,
            cidade: cidade
        },
        classe: 'user',
        acao: 'insert',
    }

    const id = document.getElementById('id-user').value || false;

    if (id !== false) {
        params['data']['id'] = parseInt(id);
        params['acao'] = 'alterar';
    }

    let configRequest = {
        method: 'POST',
        cache: 'default',
        body: JSON.stringify(params),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    let myRequest = new Request('http://localhost:8000/conexao.php', configRequest);

    fetch(myRequest).then(response => {
        return response.json();
    }).then(function (response) {
        if (response.codigo === 1) {
            if (id !== false) {
                document.getElementById('insert-form').reset();
                alert("Registro alterado com sucesso!")
                voltarTela();
                return;
            }
            stopPageLoader();
            alert("Registro inserido com sucesso!")
            voltarTela();
            return;
        }
        alert(response.msg)

    })
}

const popularFormUpdate = (idClientUpdate) => {
    localStorage.removeItem("idClientUpdate");

    let bodyRequest = JSON.stringify({
        classe: 'user',
        acao: 'selectClient',
        id: idClientUpdate
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

    fetch(myRequest).then(function (response) {
        return response.json();
    }).then(async response => {
        if (response.codigo === 1) {

            document.getElementById('estado').options[response.dados.id_estado].selected = true
            await selectCidade(response.dados.id_estado);
            setSelect('cidade', response.dados.id_cidade)
            setVal('id-user', response.dados.id)
            setVal('name', response.dados.nome)
            setVal('cpf', response.dados.cpf)
            setVal('rg', response.dados.rg)
            setVal('tel', response.dados.telefone)
            setVal('email', response.dados.email)
            setVal('rua', response.dados.logradouro)
            setVal('numero', response.dados.numero)
            setVal('bairro', response.dados.bairro)
            return false;
        }
        alert(response.msg);
    })
}
