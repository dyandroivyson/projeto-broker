# Projeto Broker
Script PHP para ler, manipular e retornar arquivos com mensagens de envio para operadoras de celular.

O arquivo de entrada tem o seguinte layout:

IDMENSAGEM;DDD;CELULAR;OPERADORA;HORARIO_ENVIO;MENSAGEM

Exemplo:

    bff58d7b-8b4a-456a-b852-5a3e000c0e63;12;996958849;NEXTEL;21:24:03;sapien sapien non mi integer ac neque duis bibendum
    b7e2af69-ce52-4812-adf1-395c8875ad30;46;950816645;CLARO;19:05:21;justo lacinia eget tincidunt eget
    e7b87f43-9aa8-414b-9cec-f28e653ac25e;34;990171682;VIVO;18:35:20;dui luctus rutrum nulla tellus in sagittis dui
    c04096fe-2878-4485-886b-4a68a259bac5;43;940513739;NEXTEL;14:54:16;nibh fusce lacus purus aliquet at feugiat
    d81b2696-8b62-4b8b-af82-586ce0875ebc;21;983522711;TIM;16:42:48;sit amet eros suspendisse accumsan tortor quis turpis sed ante
    
O arquivo de saída gerado tem o seguinte layout:

IDMENSAGEM;IDBROKER

Exemplo:

    e7b87f43-9aa8-414b-9cec-f28e653ac25e;1
    d81b2696-8b62-4b8b-af82-586ce0875ebc;1
    
## Como executar
Adicione um ou mais arquivos no formato CSV dentro do diretório "1_recebidos". Após isso, basta executar o arquivo "index.php" pelo navegador ou diretamente pelo terminal. Para arquivos grandes ou vários arquivos, é recomendado o uso pelo terminal devido à possíveis limitações do "php.ini".

## Como testar
Pelo terminal ou navegador execute o arquivo "run_tests.php".

## Sobre o desenvolvimento do projeto
Este script foi desenvolvido utilizando a linguagem PHP em sua versão 7.2. Foi utilizado o editor Visual Studio Code em ambiente Windows 10. Para alcançar o objetivo do projeto foram utilizadas algumas bibliotecas nativas do PHP, sendo elas:

- cURL para acesso à Blacklist;
- DateTime para manipulação do horário limite de envio de mensagens;
- DirectoryIterator para manipulação do sistema de arquivos.

O projeto foi arquitetado para utilizar o máximo de recursos nativos da linguagem, pregando pela simplicidade no desenvolvimento, configuração e uso, dispensando demais frameworks, bibliotecas e etc.
