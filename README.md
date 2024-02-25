# Projeto de Transferência

Esse projeto realiza a transferência de uma conta para outra.

# Documentação

A documentação do projeto foi feita utilizando a Open Api (Swagger), basta acessa-la.

# Arquitetura

O projeto foi realizado dividido em várias camadas isolando a camada de négocio ao máximo do Framework.

Evitei utilizar recursos excessivos do Framework, evitando por exemplo os relacionamentos do model.

Me ative a manter no Form Request apenas validações de tipagem e mantive na camada de négocio suas devidas validações.

Utilizei algumas estruturas do HyperF para mostrar domínio e conhecimento do Framework, como async-queue.

# Testes

Foram feitos testes de integração e testes na camada de serviço, para executa-los basta acessar o container docker e
rodar: vendor/bin/co-phpunit --prepend test/bootstrap.php -c phpunit.xml --colors=always

# Dúvida / Sugestão

O verbo http sugerido é o Verbo Post, que significa a criação de um recurso, porém ao meu ver, mesmo que essa transferência
origine um novo recurso (Uma transação), o ato solicitado é uma transferência, que seria uma atualização de um recurso,
204 ou 200 dependendo do caso.

Porém me ative e mantive a requisição como post.