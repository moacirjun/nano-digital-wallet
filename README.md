# Nano Digital Wallet
Um sistema simples para efeturar transferências entre usuários.

## Documentação
O projeto foi dividido numa arquitetura hexagonal (também conhecido como ports and adapters).
- Application: Serviços de uso de casos. Geralmente são chamodos por entry points do sistema como controller e commands.
- Domain: Fica com as entidades do sistema. Ex.: Models, Values Object.
- Infra: Controllers, Framework, Implementação de portas abstratas do Domain.

### Models
- Transference
- Wallet
- User

### Fluxo
1. AuthorizeTransference.php - Faz as validações internas e verifica o autorizador externo.
1. ExecuteTransferenceProcess.php - Persiste a transferência e modifica as carteiras dos usuários.
1. NotifyTransferencePayee.php - Envia a notificação para o beneficiado da transferência em caso de sucesso.

## Configuração
Clone o repositório
```bash
$ git clone git@github.com:moacirjun/nano-digital-wallet.git
```
Monte a imagem do `php-fpm` com o seu userId:
```bash
$ cd nano-digital-wallet && docker-compose build --build-arg USER_ID=${UID} --build-arg GROUP_ID=${GID} php-fpm
$ docker-compose up -d
```

Crie seu `.env.local`:
```bash
$ cp .env .env.local
```

Instalar as dependências, carregar banco de dados e Fixtures.
```bash
$ docker exec -it digital_wallet_php composer sync
```

Testes unitários.
```bash
$ docker exec -it digital_wallet_php vendor/bin/codecept run Unit
```

Gere as chaves SSL:
```bash
$ docker exec -it digital_wallet_php bin/console lexik:jwt:generate-keypair
```

Gere um Token JWT para o usuário Mike (Para mais usuários veja o arquivo AppFixtures.php):
```bash
$ curl --location --request POST 'localhost/api/login_check' \
  --header 'Content-Type: application/json' \
  --data-raw '{
      "username":"mike@stranger.com",
      "password":"123"
  }'
```
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTY0OTE4MjksImV4cCI6MTYxNjQ5..."
}
```
Faça sua primeira tranferência:
```bash
$ curl --location --request POST 'localhost/api/transferences' \
  --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTY0OTE...' \
  --header 'Content-Type: application/json' \
  --data-raw '{
      "payee": 2,
      "amount": 5
  }'
```
```json
{"transference":"3908ef07-fe21-4fed-a016-091b72de275a"}
```

## Proposta de melhoria
Configurar o `Symfony/Messenger` para manipular as mensagens de forma assíncrona utilizando o RabbitMQ como driver. 
Dessa forma, seria possível gerenciar tópicos e filas a nível de configuração, e sem falar na possibilidade de 
definir filas específicas para reprocessamento de falhas. 
