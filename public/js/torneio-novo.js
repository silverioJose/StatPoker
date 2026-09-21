document.addEventListener('DOMContentLoaded', () => {
    initItensCaixa();
    initBlindsPreview();
});

/* ============================================================
   1. ITENS DE CAIXA
   ============================================================ */
function initItensCaixa() {
    const inputJson = document.getElementById('input-itens-caixa');
    const tbody = document.getElementById('tbody-itens-caixa');
    const btnAdd = document.getElementById('t-add-item'); // <-- pega o botão

    if (!inputJson || !tbody) return;

    let itens = [];
    try {
        itens = JSON.parse(inputJson.value || '[]');
    } catch (e) {
        itens = [
            { item: 'Buy-in', valor: 250, fichas: 30000, taxa: 30, limite: 1 },
            { item: 'Re-entry', valor: 250, fichas: 30000, taxa: 30, limite: 2 },
            { item: 'Add-on', valor: 100, fichas: 20000, taxa: 0, limite: 1 }
        ];
    }

    function renderTabela() {
    tbody.innerHTML = '';
    itens.forEach((item, index) => {
        const tr = document.createElement('tr');

        // Normaliza o nome para ignorar maiúsculas/minúsculas
        const isBuyIn = item.item.trim().toLowerCase() === 'buy-in';

        // Cria o botão de exclusão apenas se NÃO for Buy-in
        const acaoHtml = isBuyIn 
            ? `<span style="color: #999; font-size: 12px;">Obrigatório</span>` 
            : `<button type="button" class="btn-excluir-item" data-index="${index}">Excluir</button>`;

        // Buy-in não pode ser renomeado, os demais itens sim
        const nomeHtml = isBuyIn
            ? `<strong>${item.item}</strong>`
            : `<input type="text" data-index="${index}" data-field="item" value="${item.item}" style="width: 100px; padding: 4px;">`;

        tr.innerHTML = `
            <td>${nomeHtml}</td>
            <td>R$ <input type="number" data-index="${index}" data-field="valor" value="${item.valor}" style="width: 70px; padding: 4px;"></td>
            <td><input type="number" data-index="${index}" data-field="fichas" value="${item.fichas}" style="width: 80px; padding: 4px;"></td>
            <td>R$ <input type="number" data-index="${index}" data-field="taxa" value="${item.taxa}" style="width: 60px; padding: 4px;"></td>
            <td><input type="number" data-index="${index}" data-field="limite" value="${item.limite}" style="width: 50px; padding: 4px;"></td>
            <td style="text-align: center;">${acaoHtml}</td>
        `;
        tbody.appendChild(tr);
    });

    inputJson.value = JSON.stringify(itens);
}

    // Ouve alterações nos inputs e cliques no botão de exclusão
    tbody.addEventListener('input', (e) => {
        const input = e.target;
        const index = input.getAttribute('data-index');
        const field = input.getAttribute('data-field');

        if (index !== null && field) {
            if (field === 'item') {
                itens[index][field] = input.value;
            } else {
                itens[index][field] = parseFloat(input.value) || 0;
            }
            inputJson.value = JSON.stringify(itens);
        }
    });

    tbody.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-excluir-item');
        if (btn) {
            const index = parseInt(btn.getAttribute('data-index'), 10);
            
            // Remove o elemento do array
            itens.splice(index, 1);
            
            // Re-renderiza a tabela com os novos índices
            renderTabela();
        }
    });

    if (btnAdd) {
        btnAdd.addEventListener('click', () => {
            itens.push({ item: '', valor: 0, fichas: 0, taxa: 0, limite: 0 });
            renderTabela();
        });
    }

    renderTabela();
}

/* ============================================================
   2. PREVIEW DE ESTRUTURA DE BLINDS
   ============================================================ */
function initBlindsPreview() {
    const selectBlinds = document.getElementById('t-blinds');
    const tbodyBlinds = document.getElementById('t-blinds-tbody');

    if (!selectBlinds || !tbodyBlinds) return;

    // Estruturas de blinds predefinidas
    const estruturas = {
        padrao: {
            tempo: '20 min',
            niveis: [
                { nivel: '1', sb_bb: '100 / 100', ante: '-' },
                { nivel: '2', sb_bb: '100 / 200', ante: '200' },
                { nivel: '3', sb_bb: '200 / 400', ante: '400' },
                { nivel: '4', sb_bb: '300 / 600', ante: '600' },
                { nivel: 'INTERVALO', sb_bb: 'Break (15 min)', ante: '-' },
                { nivel: '5', sb_bb: '400 / 800', ante: '800' },
                { nivel: '6', sb_bb: '500 / 1.000', ante: '1.000' },
                { nivel: '7', sb_bb: '600 / 1.200', ante: '1.200' },
                { nivel: '8', sb_bb: '800 / 1.600', ante: '1.600' }
            ]
        },
        turbo: {
            tempo: '12 min',
            niveis: [
                { nivel: '1', sb_bb: '100 / 100', ante: '-' },
                { nivel: '2', sb_bb: '100 / 200', ante: '200' },
                { nivel: '3', sb_bb: '200 / 400', ante: '400' },
                { nivel: '4', sb_bb: '300 / 600', ante: '600' },
                { nivel: '5', sb_bb: '400 / 800', ante: '800' },
                { nivel: '6', sb_bb: '500 / 1.000', ante: '1.000' }
            ]
        },
        deep: {
            tempo: '30 min',
            niveis: [
                { nivel: '1', sb_bb: '50 / 100', ante: '-' },
                { nivel: '2', sb_bb: '100 / 200', ante: '-' },
                { nivel: '3', sb_bb: '150 / 300', ante: '300' },
                { nivel: '4', sb_bb: '200 / 400', ante: '400' },
                { nivel: 'INTERVALO', sb_bb: 'Break (20 min)', ante: '-' },
                { nivel: '5', sb_bb: '300 / 600', ante: '600' }
            ]
        }
    };

    function renderBlinds() {
        const tipo = selectBlinds.value || 'padrao';
        const estrutura = estruturas[tipo] || estruturas.padrao;

        tbodyBlinds.innerHTML = '';
        estrutura.niveis.forEach(n => {
            const tr = document.createElement('tr');
            if (n.nivel === 'INTERVALO') {
                tr.classList.add('blinds-preview__break');
                tr.innerHTML = `
                    <td colspan="3" style="text-align:center; font-weight:bold; color: #e6a23c;">${n.sb_bb}</td>
                    <td>${estrutura.tempo}</td>
                `;
            } else {
                tr.innerHTML = `
                    <td>${n.nivel}</td>
                    <td>${n.sb_bb}</td>
                    <td>${n.ante}</td>
                    <td>${estrutura.tempo}</td>
                `;
            }
            tbodyBlinds.appendChild(tr);
        });
    }

    selectBlinds.addEventListener('change', renderBlinds);
    renderBlinds();
}