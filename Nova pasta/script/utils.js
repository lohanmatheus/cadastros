function setVal(id, value) {
    document.getElementById(id).value = value;
}

function setSelect(id, idRegistro) {
    let select = document.getElementById(id);

    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === idRegistro) {
            select.selectedIndex = i;
            break;
        }
    }
}

function setMaskCPF(element) {
    element.value = element.value.replace(/\D/g, "").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2")
}

function voltarTela(){
    window.location.href = "index.php";
}

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function mtel(v){
    v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

function id( el ){
    return document.getElementById( el );
}

function startPageLoader(){
    window.document.body.innerHTML = `    
        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner"></div>
        </div>
        ${window.document.body.innerHTML}`;
}

function stopPageLoader(){
    window.setTimeout(() => { window.document.getElementById('preloader').remove() },360)
}

function removeSelectCidade(){
    let selectCidade = document.getElementById("cidade");
    let selectEstado = document.getElementById("estado");

    if(selectCidade === "") {
        if (selectCidade.value !== "") {
            selectCidade.innerHTML = '<option value="">Selecione um estado...</option>'
        } else if (selectEstado.value === "") {
            selectCidade.disabled = true;
        }
    }
    if (selectEstado.value === "") {
        selectCidade.innerHTML = '<option value="">Selecione um estado...</option>'
        selectCidade.disabled = true;
    }
}