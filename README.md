# Manual de Uso: Módulo Pudo Intelipost

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
