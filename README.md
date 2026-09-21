# StatPoker (Protótipo) ♠♥♣♦
**Desenvolvido por:** [José Pedro](https://github.com/silverioJose) e [Naomi Marra](https://github.com/NaomiMoon6)

O conceito inicial do site consistia em uma interface moderna e simplificada, sem funcionalidade de back-end. O objetivo era implementar operações CRUD (criação, leitura, atualização e exclusão) para torneios de pôquer e seus respectivos itens de caixa. Além disso, realizamos ajustes pontuais na interface original para conferir maior profissionalismo ao protótipo. 

A versão beta atual foi elaborada em menos de uma semana e permanece em desenvolvimento; todo o conteúdo está sujeito a aprovação e poderá sofrer revisões futuras.

---

## Estrutura de Arquivos

```text
StatPoker/
├── index.php                 <—- Landing page
│
├── src/
│   ├── Config/
│   │   └── Database.php      <—- Conexão com banco de dados
│   ├── Controller/
│   │   ├── excluir.php       <—- Intermediário entre o banco e o DAO
│   │   └── salvar.php        <—- Intermediário entre o banco e o DAO
│   ├── DAO/
│   │   ├── ItemDAO.php       <—- Objeto de acesso de dados para itens Caixa
│   │   └── TorneioDAO.php    <—- Objeto de acesso de dados para torneios
│   ├── Model/
│   │   ├── ItemCaixa.php     <—- Classe Itens Caixa com getters e setters
│   │   └── Torneio.php       <—- Classe Torneios com getters e setters
│   └── Views/
│       ├── home/
│       │   └── home.php      <—- Placeholder de dashboard com dados gerais
│       ├── layouts/
│       │   ├── footer.php    <—- Placeholder de footer
│       │   └── header.php    <—- Header e barra lateral reutilizáveis
│       └── torneios/
│           ├── criar.php     <—- Formulário para criar novos torneios
│           └── lista.php     <—- Lista de todos os torneios
└── public/
    ├── assets/
    │   ├── fonts/            <—- Fontes customizadas
    │   ├── icons/            <—- Favicon
    │   └── imgs/             <—- Logo e outras imagens
    ├── css/
    │   ├── base/
    │   │   ├── chip-animation.css <—- Animação de fundo
    │   │   ├── fonts.css     <—- Importa fontes
    │   │   ├── normalize.css <—- Remove estilos padrão de navegador
    │   │   ├── page.css      <—- Edita o layout básico da página
    │   │   └── variables.css <—- Guarda variáveis de cor
    │   ├── blocks/
    │   │   ├── form.css      <—- Edita o formulário
    │   │   ├── header.css    <—- Edita o header
    │   │   ├── landing.css   <—- Edita a landing page
    │   │   ├── list.css      <—- Edita a lista de torneios
    │   │   └── sidebar.css   <—- Edita a barra lateral
    │   └── main.css          <—- Importa os outros arquivos .css
    └── js/
        ├── torneio-filtro.js <—- Script para filtrar torneios na lista
        └── torneio-novo.js   <—- Script para atualizar a lista de blinds
