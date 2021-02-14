<?php

/**
 * Utilitários para as mensagens
 */
class MensagemUtils 
{
    /**
     * Função que verifica se o DDD é válido
     *
     * @param  mixed $ddd
     * @return void
     */
    public function checaDdd($ddd)
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
     *
     * @param  mixed $celular
     * @return void
     */
    public function checaCelular($celular)
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
     *
     * @param  mixed $ddd
     * @param  mixed $celular
     * @return void
     */
    public function checaBlacklist($ddd, $celular)
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
     *
     * @param  mixed $horario
     * @return void
     */
    public function checaHorarioAgendamento($horario)
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
     *
     * @param  mixed $mensagem
     * @return void
     */
    public function checaMensagem($mensagem)
    {
        if (strlen($mensagem) > 140) {
            return false;
        }

        return true;
    }

    /**
     * Função para remover celulares repetidos conservando apenas a mensagem 
     * de menor horário de envio
     *
     * @param  mixed $linhas
     * @return void
     */
    public function ordenaUnificaMensagens($linhas)
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
     *
     * @param  mixed $linhas
     * @return void
     */
    public function converteMensagens($linhas)
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
}