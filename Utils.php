<?php

/**
 * Métodos utilitários
 */
class Utils
{
    /**
     * Função de manipulação de diretórios e arquivos
     *
     * @param  mixed $diretorio
     * @return void
     */
    public static function criaDiretorio($diretorio)
    {
        // Cria o diretório se não existir
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0755, true);
        }

        // Cria o arquivo index.html se não existir
        $arquivo_html = $diretorio . DIRECTORY_SEPARATOR . 'index.html';
        if (!file_exists($arquivo_html)) {
            $arquivo = fopen($arquivo_html, 'w');

            fclose($arquivo);
        }
    }

    /**
     * Função para escrever mensagem no terminal/navegador
     *
     * @param  mixed $mensagem
     * @return void
     */
    public static function escreveMensagem($mensagem)
    {
        // Verifica se o script está sendo executado via browser ou terminal
        $quebra_linha = "\n\n";
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $quebra_linha = '<br><br>';
        }

        echo $mensagem . $quebra_linha;
    }
}
