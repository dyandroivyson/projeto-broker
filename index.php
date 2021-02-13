<?php
    /**
     * Função de manipulação de diretórios e arquivos
     */
    function criaDiretorio($diretorio)
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
     */
    function escreveMensagem($mensagem)
    {
        // Verifica se o script está sendo executado via browser ou terminal
        $quebra_linha = "\n\n";
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $quebra_linha = '<br><br>';
        }

        echo $mensagem . $quebra_linha;
    }

    /**
     * Função que verifica se o DDD é válido
     */
    function checaDdd($ddd)
    {
        // Listando todos os DDDs brasileiros
        $todos_ddd = [
            '68' => 'acre',
            '82' => 'alagoas',
            '86' => 'amapa',
            '92' => 'amazonas',
            '97' => 'amazonas',
            '71' => 'bahia',
            '73' => 'bahia',
            '74' => 'bahia',
            '75' => 'bahia',
            '77' => 'bahia',
            '85' => 'ceara',
            '88' => 'ceara',
            '61' => 'distrito_federal',
            '27' => 'espirito_santo',
            '28' => 'espirito_santo',
            '62' => 'goias',
            '64' => 'goias',
            '98' => 'maranhao',
            '99' => 'maranhao',
            '65' => 'mato_grosso',
            '66' => 'mato_grosso',
            '67' => 'mato_grosso_do_sul',
            '31' => 'minas_gerais',
            '32' => 'minas_gerais',
            '33' => 'minas_gerais',
            '34' => 'minas_gerais',
            '35' => 'minas_gerais',
            '37' => 'minas_gerais',
            '38' => 'minas_gerais',
            '91' => 'para',
            '93' => 'para',
            '94' => 'para',
            '83' => 'paraiba',
            '41' => 'parana',
            '42' => 'parana',
            '43' => 'parana',
            '44' => 'parana',
            '45' => 'parana',
            '46' => 'parana',
            '81' => 'pernambuco',
            '87' => 'pernambuco',
            '86' => 'piaui',
            '89' => 'piaui',
            '21' => 'rio_de_janeiro',
            '22' => 'rio_de_janeiro',
            '24' => 'rio_de_janeiro',
            '84' => 'rio_grande_do_norte',
            '51' => 'rio_grande_do_sul',
            '53' => 'rio_grande_do_sul',
            '54' => 'rio_grande_do_sul',
            '55' => 'rio_grande_do_sul',
            '69' => 'rondonia',
            '95' => 'roraima',
            '47' => 'santa_catarina',
            '48' => 'santa_catarina',
            '49' => 'santa_catarina',
            '11' => 'sao_paulo',
            '12' => 'sao_paulo',
            '13' => 'sao_paulo',
            '14' => 'sao_paulo',
            '15' => 'sao_paulo',
            '16' => 'sao_paulo',
            '17' => 'sao_paulo',
            '18' => 'sao_paulo',
            '19' => 'sao_paulo',
            '79' => 'sergipe',
            '63' => 'tocantins',
        ];

        // Verificando se o DDD é numérico
        if (!is_numeric($ddd)) {
            return false;
        }

        // Verificando se o DDD tem exatamente 2 dígitos
        if (strlen($ddd) != 2) {
            return false;
        }

        // Verifica se o DDD existe
        if (!isset($todos_ddd[$ddd])) {
            return false;
        }

        // Verificando se o DDD é de São Paulo
        if ($todos_ddd[$ddd] == 'sao_paulo') {
            return false;
        }

        return true;
    }

    /**
     * Função para verificar se o  celular é válido
     */
    function checaCelular($celular)
    {
        // Verificando se o celular é numérico
        if (!is_numeric($celular)) {
            return false;
        }

        // Verificando se o celular tem exatamente 9 dígitos
        if (strlen($celular) != 9) {
            return false;
        }

        // Verificando se o primeiro dígito é 9
        if (substr($celular, 0, 1) != '9') {
            return false;
        }

        // Verificando se o segundo dígito é menor que 6
        if (substr($celular, 1, 1) <= '6') {
            return false;
        }

        return true;
    }

    /**
     * Função para verificar se o número está na blacklist
     */
    function checaBlacklist($ddd, $celular)
    {
        $telefone = $ddd . $celular;
        $url = 'https://front-test-pg.herokuapp.com/blacklist/' . $telefone;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $resposta = curl_exec($curl);
        $codigo_http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);

        // Verificando se o número está na blacklist
        if ($codigo_http == 200) {
            return false;
        }

        return true;
    }

    /**
     * Função para verificar horário de agendamento
     */
    function checaHorarioAgendamento($horario)
    {
        $horario_envio = new DateTime($horario);
        $horario_limite = new DateTime('19:59:59');

        $intervalo = $horario_envio->diff($horario_limite);
        
        // Se o horário de envio for maior que o horário limite, 
        // o atributo invert retorna 1, se não, retorna 0
        if ($intervalo->invert == 1) {
            return false;
        }

        return true;
    }

    /**
     * Função para verificar o conteúdo da mensagem
     */
    function checaMensagem($mensagem)
    {
        if (strlen($mensagem) > 140) {
            return false;
        }

        return true;
    }

    /**
     * Função para remover celulares repetidos conservando apenas a mensagem 
     * de menor horário de envio
     */
    function ordenaUnificaMensagens($linhas)
    {
        $resultado = [];
        $chaves = [];
        $i = 0;

        // Ordenando envios por menor horário
        array_multisort(array_map(function($elemento) {
            return $elemento['HORARIO'];
        }, $linhas), SORT_ASC, $linhas);
        
        // Removendo celulares duplicados
        foreach ($linhas as $linha) {
            $ddd_celular = $linha['DDD'] . $linha['CELULAR'];
            if (!in_array($ddd_celular, $chaves)) {
                $chaves[$i] = $ddd_celular;
                $resultado[$i] = $linha;
            }

            $i++;
        }

        return $resultado;
    }

    /**
     * Função para converter a mensagem no formato da operadora
     */
    function converteMensagens($linhas)
    {
        $resultado = [];

        $ids_broker = [
            'VIVO'  => 1,
            'TIM'   => 1,
            'CLARO' => 2,
            'OI' => 2,
            'NEXTEL' => 3
        ];

        foreach ($linhas as $linha) {
            $operadora = strtoupper($linha['OPERADORA']);

            // Verificando se a operadora existe na listagem de brokers
            if (array_key_exists($operadora, $ids_broker)) {
                $resultado[] = [
                    'IDMENSAGEM' => $linha['IDMENSAGEM'],
                    'IDBROKER' => $ids_broker[$operadora]
                ];
            }
        }

        return $resultado;
    }

    escreveMensagem('Iniciando processamento');

    // Inicializando variáveis
    $arquivos = [];
    $linhas = [];
    $mensagens_aptas = [];

    // Cria o diretório de entrada se não existir
    $dir_recebidos = __DIR__ . DIRECTORY_SEPARATOR . '1_recebidos';
    criaDiretorio($dir_recebidos);

    // Cria o diretório de arquivos processados se não existir
    $dir_processados = __DIR__ . DIRECTORY_SEPARATOR . '2_processados';
    criaDiretorio($dir_processados);

    // Cria o diretório de saída se não existir
    $dir_sanitarizados = __DIR__ . DIRECTORY_SEPARATOR . '3_sanitarizados';
    criaDiretorio($dir_sanitarizados);

    escreveMensagem('Lendo arquivos CSV do diretorio de entrada');

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

        escreveMensagem('Lendo arquivo ' . $arquivo);

        while ($linha = fgetcsv($arquivo_uso, 300, ';')) {
            $linhas[] = array_combine($campos, $linha);
        }

        fclose($arquivo_uso);

        // Renomeando e movendo arquivo processado
        $novo_arquivo = str_replace('.csv', '_processado_' . date('dmY_his') 
            . '.csv', $arquivo);
        $arquivo_processado = $dir_processados . DIRECTORY_SEPARATOR 
            . $novo_arquivo;

        escreveMensagem(
            'Movendo arquivo para o diretorio de processados e renomeando para ' 
            . $novo_arquivo
        );

        rename($arquivo_atual, $arquivo_processado);
    }

    escreveMensagem('Validando regras');

    // Listando o conteúdo por linha
    foreach ($linhas as $linha) {
        $ddd_celular = $linha['DDD'] . $linha['CELULAR'];
        
        // Verificando DDD
        if (!checaDdd($linha['DDD'])) {
            escreveMensagem(
                'Mensagem ' . $linha['IDMENSAGEM'] . ' possui DDD invalido'
            );
            continue;
        }

        // Verificando celular
        if (!checaCelular($linha['CELULAR'])) {
            escreveMensagem(
                'Mensagem ' . $linha['IDMENSAGEM'] . ' possui celular invalido'
            );
            continue;
        }

        // Verificando Blacklist
        if (!checaBlacklist($linha['DDD'], $linha['CELULAR'])) {
            escreveMensagem(
                'Mensagem ' . $linha['IDMENSAGEM'] . ' presente na blacklist'
            );
            continue;
        }

        // Verificando Horário de envio da mensagem
        if (!checaHorarioAgendamento($linha['HORARIO'])) {
            escreveMensagem(
                'Mensagem ' . $linha['IDMENSAGEM'] 
                . ' com horário de envio acima do limite permitido'
            );
            continue;
        }

        // Verificando conteúdo da mensagem
        if (!checaMensagem($linha['MENSAGEM'])) {
            escreveMensagem(
                'Mensagem ' . $linha['IDMENSAGEM'] 
                . ' com quantidade de caracteres acima do limite permitido'
            );
            continue;
        }

        $mensagens_aptas[] = $linha;
    }

    escreveMensagem('Ordenando mensagens');

    // Sanitarizando as mensagens
    $mensagens = ordenaUnificaMensagens($mensagens_aptas);

    escreveMensagem('Convertendo mensagens');

    // Convertendo para o formato final
    $mensagens_tratadas = converteMensagens($mensagens);

    // Criando arquivo com mensagens válidas
    $arquivo_saida = date('dmY_his') . '.csv';
    $arquivo_destino = $dir_sanitarizados . DIRECTORY_SEPARATOR 
        . $arquivo_saida;

    escreveMensagem('Gerando arquivo no diretorio de saida ' . $arquivo_saida);

    $arquivo_final = fopen($arquivo_destino, 'w');
    foreach ($mensagens_tratadas as $linha) {
        fputcsv($arquivo_final, $linha, ';');
    }

    fclose($arquivo_final);
    
    escreveMensagem('Processamento finalizado');
