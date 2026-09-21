        <!--Fechamento da area rea do conteudo princpal-->
        </main>

        <!--Carrega JS somente se a variavel $script foi definida-->
        <?php if (isset($script) && !empty($script)) : ?>
            <script src="<?= $script ?>"></script>
        <?php endif ; ?>
    
    </body>
</html>