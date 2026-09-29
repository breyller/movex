# 🚚 MoveX

**MoveX** é uma plataforma experimental white-label de **delivery e mobilidade**, desenvolvida como laboratório prático de engenharia de software.

O projeto evolui de forma incremental, buscando simular decisões e problemas encontrados em aplicações reais: desenvolvimento de funcionalidades, manutenção de legado, testes, arquitetura, segurança, integração, entrega e operação.

> A proposta não é adicionar tecnologias apenas por estudo, mas introduzi-las quando houver uma necessidade técnica ou de negócio que justifique seu uso.

---

## Estado atual

A versão inicial do projeto estabelece a fundação necessária para a evolução da aplicação.

### v0.1.0 — Bootstrap

Inclui:

* PHP 8.4
* Yii 1.1.32
* Apache
* MySQL 8.4
* Composer
* Docker
* Docker Compose
* Dev Container
* configuração inicial da aplicação
* conexão com banco de dados
* endpoint de health check
* estrutura inicial de versionamento e releases

Arquitetura atual:

```text
┌────────────────────┐
│      MoveX API     │
│   PHP + Yii 1.x    │
└─────────┬──────────┘
          │
          ▼
     ┌─────────┐
     │  MySQL  │
     └─────────┘
```

A arquitetura será evoluída conforme novos requisitos surgirem.

---

## Stack atual

| Tecnologia     | Uso                                   |
| -------------- | ------------------------------------- |
| PHP 8.4        | Linguagem principal                   |
| Yii 1.1.32     | Framework da aplicação                |
| Apache         | Servidor HTTP                         |
| MySQL 8.4      | Banco de dados                        |
| Composer       | Dependências PHP                      |
| Docker         | Ambiente reproduzível                 |
| Docker Compose | Orquestração local                    |
| Git            | Controle de versão                    |
| GitHub         | Repositório, Pull Requests e releases |

---

## Executando localmente

Pré-requisitos:

* Docker
* Docker Compose

Clone o repositório e execute:

```bash
docker compose build
docker compose up -d
```

Para verificar os containers:

```bash
docker compose ps
```

A API ficará disponível em:

```text
http://localhost:8080
```

Para encerrar o ambiente:

```bash
docker compose down
```

---

## Health check

A aplicação disponibiliza:

```http
GET /health
```

Exemplo:

```bash
curl -i http://localhost:8080/health
```

Resposta esperada:

```json
{
    "status": "ok",
    "database": "ok"
}
```

O endpoint verifica se:

* a aplicação PHP/Yii está respondendo;
* a conexão com o MySQL está disponível.

---

## Migrations

O MoveX utiliza o mecanismo nativo de migrations do Yii para versionar e controlar a evolução do schema do banco de dados.

As migrations ficam armazenadas em:

```text
protected/migrations/
```

Os comandos devem ser executados através da aplicação de console do Yii, dentro do container `app`.

### Criar uma migration

```bash
docker compose exec app php protected/yiic.php migrate create nome_da_migration
```

### Aplicar migrations pendentes

```bash
docker compose exec app php protected/yiic.php migrate
```

### Reverter a última migration

```bash
docker compose exec app php protected/yiic.php migrate down
```

### Consultar migrations aplicadas

```bash
docker compose exec app php protected/yiic.php migrate history
```

### Consultar migrations pendentes

```bash
docker compose exec app php protected/yiic.php migrate new
```

O Yii mantém o histórico das migrations aplicadas na tabela `tbl_migration`.

Migrations já aplicadas e compartilhadas não devem ser modificadas. Alterações posteriores no schema devem ser realizadas através de novas migrations, preservando o histórico de evolução do banco.

---

## Estrutura principal

```text
movex/
├── .devcontainer/
│   └── devcontainer.json
├── .github/
│   └── pull_request_template.md
├── protected/
│   ├── config/
│   │   ├── console.php
│   │   ├── database.php
│   │   └── main.php
│   ├── controllers/
│   │   └── SiteController.php
│   ├── migrations/
│   │   └── .gitkeep
│   ├── runtime/
│   │   └── .gitkeep
│   └── yiic.php
├── public/
│   ├── .htaccess
│   └── index.php
├── tests/
│   └── Unit/
│       └── ExampleTest.php
├── .gitignore
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── composer.lock
├── phpunit.xml.dist
└── README.md
```

A pasta `public/` é utilizada como **DocumentRoot** do Apache.

O código da aplicação, configurações e dependências permanecem fora da área diretamente exposta pelo servidor HTTP.

A configuração de banco de dados compartilhada pelas aplicações web e console está centralizada em `protected/config/database.php`.

Os comandos de console do Yii são inicializados através de `protected/yiic.php`, utilizando a configuração definida em `protected/config/console.php`.

As migrations responsáveis pela evolução versionada do schema do banco de dados são armazenadas em `protected/migrations/`.

---

## Workflow de desenvolvimento

O projeto utiliza branches de curta duração para desenvolvimento e correções.

Fluxo normal:

```text
feature/*
    │
    ▼
develop
```

`develop` funciona como branch de integração das mudanças aprovadas.

Quando um conjunto de funcionalidades está pronto para uma nova versão:

```text
develop
   │
   ▼
release/vX.Y.Z
   │
   ▼
homologação
   │
   ▼
master
   │
   ▼
tag vX.Y.Z
```

### Branches

**`feature/*`**

Utilizadas para implementação de novas funcionalidades.

Exemplo:

```text
feature/MOVEX-002-phpunit
```

**`fix/*`**

Utilizadas para correções identificadas durante desenvolvimento ou homologação.

**`develop`**

Branch de integração das funcionalidades aprovadas para versões futuras.

**`release/*`**

Representa uma candidata a release.

Durante sua homologação, apenas correções relacionadas à versão devem ser incorporadas.

**`master`**

Representa código aprovado para publicação.

A branch não é o ambiente de produção; staging e produção são ambientes independentes do Git.

---

## Releases

Versões oficiais são identificadas por tags seguindo o formato:

```text
vMAJOR.MINOR.PATCH
```

Exemplos:

```text
v0.1.0
v0.2.0
v0.2.1
```

Uma tag representa um estado específico e publicado do código e não deve ser movida após a release.

O histórico de cada versão será documentado através das **GitHub Releases**.

---

## Qualidade

A estratégia de qualidade será introduzida incrementalmente conforme a aplicação evoluir.

Estão previstos:

* testes unitários;
* testes de integração;
* testes de API;
* PHPUnit;
* integração contínua;
* análise estática;
* validações automatizadas em Pull Requests.

O objetivo é priorizar testes sobre comportamentos e regras de negócio, evitando acoplamento excessivo à implementação interna.

### Executando os testes

Com os containers em execução:

```bash
docker compose exec app ./vendor/bin/phpunit
```

---

## Princípios de desenvolvimento

O MoveX será desenvolvido de forma incremental.

A abordagem adotada é:

```text
implementar
    ↓
observar
    ↓
identificar problemas
    ↓
testar
    ↓
refatorar
    ↓
evoluir
```

Princípios como **SOLID**, padrões de projeto e separação de responsabilidades serão aplicados quando resolverem problemas concretos de manutenção, extensibilidade, testabilidade ou acoplamento.

---

## Roadmap

A evolução planejada está organizada em grandes etapas:

```text
Bootstrap
   ↓
Core Foundation
   ↓
Multi-tenancy & Security
   ↓
Delivery Core
   ↓
Mobile
   ↓
Cloud & Serverless
   ↓
Event Driven
   ↓
Distributed Services
   ↓
Production Readiness
   ↓
MoveX MVP
```

Tecnologias e decisões arquiteturais serão introduzidas conforme necessidades concretas surgirem durante essa evolução.

---

## Propósito

O MoveX é um projeto de estudo, experimentação e evolução arquitetural.

Seu objetivo é criar um ambiente suficientemente próximo de um sistema real para praticar não apenas implementação de funcionalidades, mas também:

* modelagem;
* testes;
* manutenção;
* refatoração;
* segurança;
* arquitetura;
* integração;
* versionamento;
* releases;
* deployment;
* observabilidade;
* troubleshooting.

---
