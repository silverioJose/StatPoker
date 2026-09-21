//Captura dos elementos
const inputFiltro = document.getElementById('input-filtro');
const selectStatus = document.getElementById('select-status');

//Filtra linhas da tabela com base no texto de busca e no status selecionado
function aplicarFiltros() {
    //Normalização dos valores de entrada (caixa baixa e remoção de espaços em extremidades)
    const termo = inputFiltro.value.toLowerCase().trim();
    const statusSelecionado = selectStatus.value.toLowerCase().trim();

    //Captura de todas as linhas de dados da tabela
    const linhas = document.querySelectorAll('.view__tournaments__table_data tr');

    linhas.forEach(linha => {
        //Seleção do nome e da badge de status na linha atual
        const elementoNome = linha.querySelector('td:first-child strong');
        const elementoBadge = linha.querySelector('.badge');
        
        if (elementoNome && elementoBadge) {
            //Limpeza do conteudo de texto extraido das tags HTML
            const nomeTorneio = elementoNome.textContent.toLowerCase().trim();
            const statusTorneio = elementoBadge.textContent.toLowerCase().trim();
            
            //Verificação de correspondencia do filtro textual e do filtro de status
            const bateuNome = nomeTorneio.includes(termo);
            const bateuStatus = statusSelecionado === '' || statusTorneio === statusSelecionado;

            //Exibe a linha se bater ambos os criterios, caso contrario esconde
            if (bateuNome && bateuStatus) {
                linha.style.display = '';
            } else {
                linha.style.display = 'none';
            }
        }
    });
}
//Registra eventos apenas se os inputs existirem
if (inputFiltro && selectStatus) {
    inputFiltro.addEventListener('input', aplicarFiltros);
    selectStatus.addEventListener('change', aplicarFiltros);
}