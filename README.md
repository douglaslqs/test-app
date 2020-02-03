
# Projeto Catálogo Usuários
* Versão PHP: `7.2`
* Versão Mysql (MariaDB): `10.4.11`

## Descrição

Levando em consideração a solicitação  em [https://github.com/mobly/teste-programador-php/tree/master/teste-api](https://github.com/mobly/teste-programador-php/tree/master/teste-api), este projeto foi dividido em 3 aplicações:

 1. api-resource
 2. api
 3. app
 
 A `api-resource` representa o sistema que fornece os dados em massa para a `api`, atendendo parte do item 1 da solicitação.
 
 A `api` é responsável por armazenar os dados recebidos de `api-resource` e disponibilizar as informações para o `app`, atendendo assim ao item 4 e complementando o item 1.
 
Já o `app` é responsável por fazer a interação com o usuário, atendendo assim aos itens 2 e 3.

Essa divisão do projeto em 3, tem como vantagens principais:
* Responsabilidades isoladas uma da outra
*  Grande escalabilidade
*  Possível problema em alguma parte da arquitetura não afete todo o funcionamento do sistema.

Principais desvantagem:
* Maior número de hosts para o funcionamento
* Complexidade do entendimento da arquitetura

## Conclusão

O projeto teve como foco principal a sua arquitetura, permitindo que ele tenha sua distribuição de responsabilidades, grande escalabilidade e minimização de problemas que possam pará-lo totalmente.

## Collections postman:
> https://www.getpostman.com/collections/fe84465b0422a4d92281

## Github:
> api-resource: https://github.com/douglaslqs/teste-api-resource
api: https://github.com/douglaslqs/teste-api
app: https://github.com/douglaslqs/test-app


## Execução


- Executar o arquivo `data/database/test.sql` localizado na aplicação `test-api` para criar a base de dados;
- Criar os vhosts `test-api`, `test-api-source` e `test-app`
- Baixar os projetos respectivamente dentro dos diretórios representados pelos nomes dos vhosts;
- Caso necessário, configurar as conexões com o banco de dados no arquivo `config/autoload/local.php` de cada projeto.

- Importe os dados dos usuários, utilizando a collection `test-api > users import data` do postman
- Importe os dados dos posts, utilizando a collection `test-api > posts import data` do postman

- Acesse a aplicação com a url `http://test-app.local/`
