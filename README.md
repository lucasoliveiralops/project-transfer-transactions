# Projeto de Transferência

Esse projeto realiza a transferência de uma conta para outra.

# Documentação

A documentação do projeto foi feita utilizando a Open Api (Swagger), basta acessa-la.

# Arquitetura

O projeto foi realizado dividido em várias camadas isolando a camada de négocio ao máximo do Framework.

Evitei utilizar recursos excessivos do Framework, evitando por exemplo os relacionamentos do model.

Me ative a manter no Form Request apenas validações de tipagem e mantive na camada de négocio suas devidas validações.

Utilizei algumas estruturas do HyperF para mostrar domínio e conhecimento do Framework, como async-queue.

# Teste

Foram feitos testes de integração e testes na camada de serviço, para executa-los basta acessar o container docker e
rodar: vendor/bin/co-phpunit --prepend test/bootstrap.php -c phpunit.xml --colors=always
