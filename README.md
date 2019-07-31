# Manual de Uso: Módulo Pickup Intelipost

[![logo](https://image.prntscr.com/image/E8AfiBL7RQKKVychm7Aubw.png)](http://www.intelipost.com.br)

## Introdução

O módulo Pickup é uma extensão do módulo Intelipost Quote que acrescenta a funcionalidade de **Retirada na Loja** no momento do cálculo do frete.
A consulta do frete é feita na [API Intelipost](https://docs.intelipost.com.br/v1/cotacao/criar-cotacao-por-produto) e a consulta do mapa com a localização da loja é feita na [API do Google](https://developers.google.com/maps/?hl=pt-br). Portanto, se faz necessário uma chave de autenticação e permissão para os dois casos.

Este manual foi divido em três partes:

  - [Instalação](#instalação): Onde você econtrará instruções para instalar nosso módulo.
  - [Configurações](#configurações): Onde você encontrará o caminho para realizar as configurações e explicações de cada uma delas.
  - [Uso](#uso): Onde você encontrará a maneira de utilização de cada uma das funcionalidades.
  
## Instalação
> É recomendado que você tenha um ambiente de testes para validar alterações e atualizações antes de atualizar sua loja em produção.

> A instalação do módulo é feita utilizando o Composer. Para baixar e instalar o Composer no seu ambiente acesse https://getcomposer.org/download/ e caso tenha dúvidas de como utilizá-lo consulte a [documentação oficial do Composer](https://getcomposer.org/doc/).

Navegue até o diretório raíz da sua instalação do Magento 2 e execute os seguintes comandos:


```
bin/composer require intelipost/magento2-pickup  // Faz a requisição do módulo da Intelipost
bin/magento module:enable Intelipost_Pickup      // Ativa o módulo
bin/magento setup:upgrade                        // Registra a extensão
bin/magento setup:di:compile                     // Recompila o projeto Magento
```

## Configurações
Conforme comentado na introdução, o módulo Pickup é uma extensão do Quote. Portanto, é necessário que este último esteja configurado corretamente no seu ambiente.
Caso tenha alguma dúvida sobre a configuração do módulo Quote Intelipost, consulte [nosso manual](https://github.com/intelipost/magento2-quote).

Para acessar o menu de configurações, basta seguir os seguintes passos:

No menu à esquerda, acessar **Stores** -> **Configuration** -> **Intelipost** -> **Shipping Methods** -> **Intelipost - Retira em Loja**:

![pick0](https://s3.amazonaws.com/email-assets.intelipost.net/integracoes/pickup1.gif)


### Intelipost - Retira em Loja

- **Ativado**: Se o módulo está ativo e deve ser apresentado no front da loja.
- **Nome**: Nome que ficará registrado no pedido no Magento.
- **Título**: Nome que será exibido no checkout ao lado de cada método da Intelipost.
![pick1](https://s3.amazonaws.com/email-assets.intelipost.net/integracoes/quote1.png)
------------

- **Modo de Exibição**: Há duas configurações possíveis: 
    - "Data de Chegada" exibirá o momento em que o pedido estará disponível para retirada. 
    - "Tempo de Operação" exibirá a quantidade de dias necessários para que o pedido esteja disponível para retirada.
- **SLA Adicional**: Você pode inserir uma quantidade de dias a mais para que o produto esteja disponível para retirada.
- **Google Maps API**: Inserir a sua chave de autenticação do Google Maps para que as consultas de mapa sejam realizadas.
- **Exibir Todas as Lojas**: 
    - Se configurado como "Sim", todos os endereços de lojas disponíveis serão exibidos. 
    - Se configurado como "Não", apenas o endereço da loja mais próximo do cliente será exibido. 
- **Ordernar por Proximidade**: Caso sim, as lojas serão ordenadas por proximidade pelo CEP do cliente.
- **Formato da Data**: Formato em que a data deve ser exibida.

![pick2](https://s3.amazonaws.com/email-assets.intelipost.net/integracoes/pickup222.png)

------------

- **Entrega aplicável para países**: Países que a cotação deve abrangir.
- **Ordenação**: Caso exista algum outro método de envio ativo, essa configuração possibilita escolher em qual ordem o módulo de frete da Intelipost deve se posicionar após a cotação.

![pick3](https://s3.amazonaws.com/email-assets.intelipost.net/integracoes/presales3.png)


## Uso

Uma vez instalado e configurado, é necessário cadastrar as Lojas disponíveis para retirada bem como as Janelas de Coleta.  
Para gerenciar as Lojas e as Janelas, foi construída uma API dentro do módulo contendo os seguintes serviços:

### Lojas

**POST** - http://{{url_da_loja}}/rest/V1/istores/save 
Adicionará uma nova loja ao sistema.

Request_body:
```json
{
 "stores": [
 {
 "id_loja": "L1100",
 "name": "Loja Teste 1100",
 "address": "Rua dr amancio de carvalho",
 "number": "182",
 "complement": "Vila Mariana",
 "zipcode": "04012-080",
 "city": "São Paulo",
 "state": "SP",
 "store_neighborhood": "Vila Mariana",
 "opening": "09:00 as 21:00",
 "begin_zipcode": "01000-000",
 "end_zipcode": "19999-999",
 "observations": null,
 "delivered_cdg": "1",
 "is_active": "1"
 }]
}
```

**GET** - http://{{url_da_loja}}/rest/V1/istores/list  
Retornará uma lista com todas as lojas registradas até o momento.

**GET** - http://{{url_da_loja}}/rest/V1/istores/info/{{entityId}}  
Consultar uma loja específica pelo seu Id de cadastro.

**DELETE** - http://{{url_da_loja}}/rest/V1/istores/delete/{{id}}  
Deletar uma loja específica pelo seu Id de cadastro.

---------

### Janelas de Entrega

**POST** - http://{{url_da_loja}}/rest/V1/pickup/save  
Cadastrar janela de entrega.

Request_body:
```json
{
 "items": [
 {
 "id_loja": "L1100",
 "departure_date": "05/04/2017",
 "arrival_date": "06/04/2017",
 "operation_time": "1"
 }]
}
```

**GET** - http://{{url_da_loja}}/rest/V1/pickup/list  
Retornará uma lista com todas as janelas registradas até o momento.

**GET** - http://{{url_da_loja}}/rest/V1/pickup/info/{{entityId}}    
Consultar uma janela específica pelo seu Id de cadastro.

**DELETE** - http://{{url_da_loja}}/rest/V1/pickup/delete/{{id}}  
Deletar uma janela específica pelo seu Id de cadastro.

> Obs: Para realização das chamadas, deverá ser passado o token de autenticação do usuário. Para mais detalhes, consulte a [documentação do Magento](http://devdocs.magento.com/guides/v2.1/get-started/rest_front.html).
