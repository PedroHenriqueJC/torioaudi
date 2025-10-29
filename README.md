Para rodar basta executar o docker compose no seu docker.
A porta está por padrão na 8000, então as rotas vão passar por ali.
As rotas tem que ter o /api, ou seja, sempre na porta 8000, /api e o restante da rota.
Para verificar se está tudo funcionando lembre-se de utilizar o healthcheck em http://localhost:8000/health


Cole o conteúdo do openapi.yaml em https://editor.swagger.io/ para melhor visualizar a documentação da API.
