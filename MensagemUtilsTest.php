<?php

/**
 * Testando métodos utilitários de mensagem
 */
class MensagemUtilsTest
{    
    /**
     * Testa se o DDD é numérico
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeDddNaoNumericoTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaDdd('x1');

        $this->assertEquals(false, $retorno);
    }
    
    /**
     * Testa se o DDD possuí exatamente dois dígitos
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeDddDiferenteDoisDigitosTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaDdd('123');

        $this->assertEquals(false, $retorno);
    }
    
    /**
     * Testa se o DDD existe
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeDddNaoExisteTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaDdd('01');

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se o DDD é de São Paulo
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeDddSaoPauloTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaDdd('11');

        $this->assertEquals(false, $retorno);
    }
    
    /**
     * Testa se o DDD passa em todas as regras
     *
     * @return void
     */
    public function deveriaRetornarVerdadeiroSeDddValidoTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaDdd('62');

        $this->assertEquals(true, $retorno);
    }

    /**
     * Testa se o celular é numérico
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeCelularNaoNumericoTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaCelular('x1');

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se o celular possuí exatamente nove dígitos
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeCelularDiferenteNoveDigitosTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaCelular('123');

        $this->assertEquals(false, $retorno);
    }
    
    /**
     * Testa se o celular começa com o dígito nove
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeCelularNaoComecaComNoveTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaCelular('887654821');

        $this->assertEquals(false, $retorno);
    }
    
    /**
     * Testa se o segundo dígito é maior que seis
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeCelularPossuiSegundoDigitoMenorIgualSeisTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaCelular('917654821');

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se o celular passa em todas as regras
     *
     * @return void
     */
    public function deveriaRetornarVerdadeiroSeCelularValidoTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaCelular('997654821');

        $this->assertEquals(true, $retorno);
    }
    
    /**
     * Testa se o número está na blacklist
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeDddCelularEstaNaBlacklistTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaBlacklist('68', '960636588');

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se o número não está na blacklist
     *
     * @return void
     */
    public function deveriaRetornarVerdadeiroSeDddCelularNaoEstaNaBlacklistTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaBlacklist('62', '960636581');

        $this->assertEquals(true, $retorno);
    }
    
    /**
     * Testa se o horário de envio está acima do limite permitido
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeHorarioAcimaLimiteTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaHorarioAgendamento('20:00:00');

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se o horário de envio está até o limite permitido
     *
     * @return void
     */
    public function deveriaRetornarVerdadeiroSeHorarioAbaixoLimiteTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaHorarioAgendamento('19:59:59');

        $this->assertEquals(true, $retorno);
    }
    
    /**
     * Testa se a mensagem possui mais que cento e quarenta caracteres
     *
     * @return void
     */
    public function deveriaRetornarFalsoSeMensagemAcimaCentoQuarentaCaracteresTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaMensagem("Lorem ipsum dolor sit amet, consectetur adipiscing elit. In facilisis nunc ut nibh tristique, eu semper purus vestibulum. Interdum et tellus.");

        $this->assertEquals(false, $retorno);
    }

    /**
     * Testa se a mensagem possui até cento e quarenta caracteres
     *
     * @return void
     */
    public function deveriaRetornarVerdadeiroSeMensagemAteCentoQuarentaCaracteresTest()
    {
        $mensagemUtils = new MensagemUtils();
        $retorno = $mensagemUtils->checaMensagem("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sollicitudin enim nec ligula semper, sed vehicula ante convallis porttitor.");

        $this->assertEquals(true, $retorno);
    }

    /**
     * Método para asserções de resultados
     *
     * @param  mixed $valorEsperado
     * @param  mixed $valorAtual
     * @return void
     */
    public function assertEquals($valorEsperado, $valorAtual)
    {
        if ($valorEsperado !== $valorAtual) {
            $valorAtual = $valorAtual ? $valorAtual : 0;

            $mensagem = 'Esperado: ' . $valorEsperado . ' mas recebeu: ' . $valorAtual;
            throw new \Exception($mensagem);
        }

        // Verifica se o script está sendo executado via browser ou terminal
        $quebra_linha = "\n";
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $quebra_linha = '<br>';
        }
    
        echo 'Teste passou!' . $quebra_linha;
    }
}
