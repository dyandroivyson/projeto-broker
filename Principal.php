<?php

include 'autoloader.php';

/**
 * Método Principal
 */
class Principal
{    
    /**
     * Método de execução do script
     *
     * @return void
     */
    public function index()
    {
        Utils::escreveMensagem('Iniciando processamento');

        // Inicializando variáveis
        $arquivos = [];
        $linhas = [];
        $mensagens_aptas = [];

        // Cria o diretório de entrada se não existir
        $dir_recebidos = __DIR__ . DIRECTORY_SEPARATOR . '1_recebidos';
        Utils::criaDiretorio($dir_recebidos);

        // Cria o diretório de arquivos processados se não existir
        $dir_processados = __DIR__ . DIRECTORY_SEPARATOR . '2_processados';
        Utils::criaDiretorio($dir_processados);

        // Cria o diretório de saída se não existir
        $dir_sanitarizados = __DIR__ . DIRECTORY_SEPARATOR . '3_sanitarizados';
        Utils::criaDiretorio($dir_sanitarizados);

        Utils::escreveMensagem('Lendo arquivos CSV do diretorio de entrada');

        // Listam todos os arquivos do diretório
        $itens = new DirectoryIterator($dir_recebidos);
        foreach ($itens as $item) {
            // Ler apenas arquivos no formato csv
            if ($item->isFile() && $item->getExtension() == 'csv') {
                $arquivos[] = $item->getFilename();
            }
        }

        // Mapeando campos
        $campos = [
            'IDMENSAGEM',
            'DDD',
            'CELULAR',
            'OPERADORA',
            'HORARIO',
            'MENSAGEM'
        ];

        // Listando o conteúdo dos arquivos
        foreach ($arquivos as $arquivo) {
            $arquivo_atual = $dir_recebidos . DIRECTORY_SEPARATOR . $arquivo;
            $arquivo_uso = fopen($arquivo_atual, 'r');

            Utils::escreveMensagem('Lendo arquivo ' . $arquivo);

            while ($linha = fgetcsv($arquivo_uso, 300, ';')) {
                $linhas[] = array_combine($campos, $linha);
            }

            fclose($arquivo_uso);

            // Renomeando e movendo arquivo processado
            $novo_arquivo = str_replace('.csv', '_processado_' . date('dmY_his') 
                . '.csv', $arquivo);
            $arquivo_processado = $dir_processados . DIRECTORY_SEPARATOR 
                . $novo_arquivo;

            Utils::escreveMensagem(
                'Movendo arquivo para o diretorio de processados e renomeando para ' 
                . $novo_arquivo
            );

            rename($arquivo_atual, $arquivo_processado);
        }

        Utils::escreveMensagem('Validando regras');

        // Listando o conteúdo por linha
        foreach ($linhas as $linha) {
            $ddd_celular = $linha['DDD'] . $linha['CELULAR'];
            
            // Verificando DDD
            if (!MensagemUtils::checaDdd($linha['DDD'])) {
                Utils::escreveMensagem(
                    'Mensagem ' . $linha['IDMENSAGEM'] . ' possui DDD invalido'
                );
                continue;
            }

            // Verificando celular
            if (!MensagemUtils::checaCelular($linha['CELULAR'])) {
                Utils::escreveMensagem(
                    'Mensagem ' . $linha['IDMENSAGEM'] . ' possui celular invalido'
                );
                continue;
            }

            // Verificando Blacklist
            if (!MensagemUtils::checaBlacklist($linha['DDD'], $linha['CELULAR'])) {
                Utils::escreveMensagem(
                    'Mensagem ' . $linha['IDMENSAGEM'] . ' presente na blacklist'
                );
                continue;
            }

            // Verificando Horário de envio da mensagem
            if (!MensagemUtils::checaHorarioAgendamento($linha['HORARIO'])) {
                Utils::escreveMensagem(
                    'Mensagem ' . $linha['IDMENSAGEM'] 
                    . ' com horário de envio acima do limite permitido'
                );
                continue;
            }

            // Verificando conteúdo da mensagem
            if (!MensagemUtils::checaMensagem($linha['MENSAGEM'])) {
                Utils::escreveMensagem(
                    'Mensagem ' . $linha['IDMENSAGEM'] 
                    . ' com quantidade de caracteres acima do limite permitido'
                );
                continue;
            }

            $mensagens_aptas[] = $linha;
        }

        Utils::escreveMensagem('Ordenando mensagens');

        // Sanitarizando as mensagens
        $mensagens = MensagemUtils::ordenaUnificaMensagens($mensagens_aptas);

        Utils::escreveMensagem('Convertendo mensagens');

        // Convertendo para o formato final
        $mensagens_tratadas = MensagemUtils::converteMensagens($mensagens);

        // Criando arquivo com mensagens válidas
        $arquivo_saida = date('dmY_his') . '.csv';
        $arquivo_destino = $dir_sanitarizados . DIRECTORY_SEPARATOR 
            . $arquivo_saida;

        Utils::escreveMensagem('Gerando arquivo no diretorio de saida ' . $arquivo_saida);

        $arquivo_final = fopen($arquivo_destino, 'w');
        foreach ($mensagens_tratadas as $linha) {
            fputcsv($arquivo_final, $linha, ';');
        }

        fclose($arquivo_final);

        Utils::escreveMensagem('Processamento finalizado');
    }
}
