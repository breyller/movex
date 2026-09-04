# 🚚 MoveX

**MoveX** é uma plataforma experimental white-label de **delivery e mobilidade**, criada como laboratório prático de engenharia de software backend.

O projeto tem como objetivo simular a evolução de uma aplicação real: começando por um monólito PHP legado funcional, passando por refatorações, testes automatizados, integrações externas e, posteriormente, evoluindo para uma arquitetura distribuída com serviços em Go.

> O foco do projeto não é apenas implementar funcionalidades, mas estudar decisões de arquitetura, manutenção de legado, boas práticas e evolução segura de software.

---

## 🎯 Objetivos

O MoveX será utilizado para praticar, de forma incremental:

* PHP moderno em uma aplicação baseada em **Yii 1**
* manutenção e evolução de sistemas legados
* APIs REST
* orientação a objetos
* princípios **SOLID**
* Design Patterns
* autenticação e autorização
* RBAC
* arquitetura multi-tenant / white-label
* modelagem de dados
* MySQL
* Redis
* integração com APIs externas
* Google Maps / Google Routes
* testes automatizados com PHPUnit
* Test-Driven Development (TDD)
* concorrência e idempotência
* filas e processamento assíncrono
* microsserviços em **Go**
* Docker
* Git e Pull Requests
* CI/CD
* deployment
* observabilidade e troubleshooting

---

## 🏗️ Visão arquitetural

A primeira versão do MoveX será propositalmente simples.

```text
                   MoveX

              ┌──────────────┐
              │   Yii API    │
              │ PHP Monolith │
              └──────┬───────┘
                     │
                     ▼
                ┌─────────┐
                │  MySQL  │
                └─────────┘
```

Conforme o sistema evoluir, novos componentes serão introduzidos apenas quando houver uma necessidade concreta.

Uma possível arquitetura futura:

```text
                       MoveX

                ┌─────────────────┐
                │     Yii API     │
                │   PHP Monolith  │
                └────────┬────────┘
                         │
           ┌─────────────┼─────────────┐
           │             │             │
           ▼             ▼             ▼
        MySQL          Redis        Message
                                      Queue
                                        │
                                        ▼
                               ┌────────────────┐
                               │  Go Dispatch   │
                               │    Service     │
                               └───────┬────────┘
                                       │
                                       ▼
                                Google Routes
```

Essa arquitetura representa um **roadmap**, não o estado atual da aplicação.

---

## 🧩 Domínio

O MoveX simula uma plataforma SaaS white-label utilizada por diferentes operadores de delivery e mobilidade.

Exemplo:

```text
                         MoveX
                           │
               ┌───────────┼───────────┐
               │           │           │
            RapidGo    CampinasJá   MotoSul
             Tenant A     Tenant B    Tenant C
```

Cada tenant poderá possuir seus próprios:

* usuários
* operadores
* motoristas e entregadores
* estabelecimentos
* entregas
* corridas
* tarifas
* configurações
* permissões

O isolamento dos dados entre tenants será uma das principais regras de segurança da plataforma.

---

## 🛠️ Stack atual

| Tecnologia        | Uso                                 |
| ----------------- | ----------------------------------- |
| PHP 8.4           | Linguagem principal                 |
| Yii 1.1.32        | Framework PHP                       |
| Apache            | Servidor HTTP                       |
| MySQL 8.4         | Banco de dados                      |
| Composer          | Gerenciamento de dependências PHP   |
| Docker            | Ambiente reproduzível               |
| Docker Compose    | Orquestração dos serviços locais    |
| GitHub Codespaces | Ambiente de desenvolvimento inicial |
| Git               | Controle de versão                  |

### Planejado

* PHPUnit
* Redis
* Go
* Google Routes API
* mensageria
* GitHub Actions
* CI/CD
* ambientes de staging e produção

---

## 📁 Estrutura inicial

```text
movex/
├── .devcontainer/
│   └── devcontainer.json
│
├── public/
│   ├── .htaccess
│   └── index.php
│
├── protected/
│   ├── config/
│   │   └── main.php
│   │
│   ├── controllers/
│   │   └── SiteController.php
│   │
│   └── runtime/
│
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── composer.lock
├── .gitignore
└── README.md
```

A pasta `public/` é o **DocumentRoot** do Apache.

Código da aplicação, configurações e dependências permanecem fora da área diretamente exposta pelo servidor HTTP.

---

## 🚀 Executando o projeto

### GitHub Codespaces

O ambiente de desenvolvimento está configurado através de:

```text
.devcontainer/devcontainer.json
```

Ao reconstruir o container, o Codespaces utiliza o `docker-compose.yml`, sobe os serviços necessários e executa a instalação das dependências do projeto.

---

### Docker Compose

O projeto foi estruturado para não depender exclusivamente do Codespaces.

Em uma máquina com Docker e Docker Compose instalados:

```bash
docker compose build
docker compose up -d
```

As dependências PHP podem ser instaladas dentro do container com:

```bash
docker compose exec app composer install
```

---

## ❤️ Health Check

A aplicação disponibiliza inicialmente:

```http
GET /health
```

Resposta esperada:

```json
{
    "status": "ok",
    "database": "ok"
}
```

Esse endpoint verifica se:

* a aplicação PHP/Yii está respondendo;
* a conexão com o MySQL está disponível.

Exemplo:

```bash
curl -i http://localhost/health
```

Resposta:

```text
HTTP/1.1 200 OK
Content-Type: application/json
```

---

## 🌿 Estratégia de branches

O projeto utiliza inicialmente:

```text
master
  │
  └── develop
        │
        └── feature/*
```

### `master`

Representará a versão estável da aplicação e, futuramente, o ambiente de produção.

### `develop`

Branch de integração das funcionalidades aprovadas.

### `feature/*`

Branches utilizadas durante a implementação dos cards.

Exemplo:

```text
feature/MOVEX-001-bootstrap
```

---

## 🧪 Estratégia de testes

Testes automatizados serão introduzidos gradualmente com **PHPUnit**.

O projeto deverá possuir diferentes níveis de testes:

### Unitários

Validam regras de negócio isoladamente.

```text
PricingService
Delivery
Dispatch
Authorization
```

### Integração

Validam a interação com componentes externos ou infraestrutura.

```text
Application
   +
MySQL
```

### Funcionais / API

Validam comportamentos completos através das interfaces públicas da aplicação.

Exemplo:

```text
POST /deliveries
        ↓
201 Created
```

O objetivo é que os testes representem principalmente **comportamentos e regras de negócio**, evitando acoplamento desnecessário à implementação interna.

---

## 🔄 Fluxo de desenvolvimento planejado

```text
Feature branch
      │
      ▼
Desenvolvimento
      │
      ▼
Testes automatizados
      │
      ▼
Push
      │
      ▼
Pull Request
      │
      ▼
CI
 ├── PHPUnit
 ├── lint
 └── análise estática
      │
      ▼
develop
      │
      ▼
MoveX Staging
      │
      ▼
Homologação
      │
      ▼
Pull Request
develop → master
      │
      ▼
CI
      │
      ▼
master
      │
      ▼
MoveX Production
```

Esse fluxo será implementado gradualmente conforme o projeto evoluir.

---

## 🧠 Filosofia de desenvolvimento

O MoveX será construído de maneira incremental.

Nem toda abstração será criada antecipadamente.

A estratégia será:

```text
implementar
    ↓
observar problemas reais
    ↓
identificar responsabilidades
    ↓
testar
    ↓
refatorar
    ↓
evoluir arquitetura
```

Princípios como **SOLID** serão utilizados para resolver problemas concretos de manutenção, extensibilidade, testabilidade e acoplamento — e não apenas como regras formais.

---

## 📋 Roadmap

### Fase 1 — Bootstrap

* [x] Estrutura inicial
* [x] Docker
* [x] PHP
* [x] Yii
* [x] MySQL
* [x] Health check
* [ ] README inicial

### Fase 2 — Fundamentos

* [ ] primeira entidade
* [ ] migrations
* [ ] persistência
* [ ] PHPUnit
* [ ] primeiros testes unitários
* [ ] introdução ao TDD

### Fase 3 — Plataforma white-label

* [ ] tenants
* [ ] usuários
* [ ] autenticação
* [ ] autorização
* [ ] RBAC
* [ ] isolamento entre tenants

### Fase 4 — Delivery

* [ ] clientes
* [ ] motoristas
* [ ] criação de entregas
* [ ] estados da entrega
* [ ] regras de transição
* [ ] cálculo de tarifas

### Fase 5 — Geolocalização

* [ ] integração com Google Maps
* [ ] geocoding
* [ ] cálculo de rotas
* [ ] estimativa de distância
* [ ] estimativa de duração

### Fase 6 — Dispatch

* [ ] motoristas disponíveis
* [ ] localização
* [ ] seleção de motorista
* [ ] concorrência
* [ ] prevenção de dupla aceitação

### Fase 7 — Go

* [ ] fundamentos da linguagem
* [ ] primeiro serviço
* [ ] extração do Dispatch Service
* [ ] comunicação PHP ↔ Go
* [ ] concorrência em Go

### Fase 8 — Sistemas distribuídos

* [ ] Redis
* [ ] filas
* [ ] processamento assíncrono
* [ ] retries
* [ ] idempotência
* [ ] dead-letter queue
* [ ] eventual consistency

### Fase 9 — Delivery pipeline

* [ ] GitHub Actions
* [ ] CI
* [ ] staging
* [ ] produção
* [ ] deployment automatizado
* [ ] migrations em deployment
* [ ] rollback
* [ ] health checks

### Fase 10 — Operação

* [ ] logs estruturados
* [ ] métricas
* [ ] observabilidade
* [ ] performance
* [ ] troubleshooting
* [ ] incidentes simulados

---

## 📚 Propósito

O MoveX é um projeto de estudo e experimentação.

Seu propósito é criar um ambiente suficientemente próximo de um sistema real para praticar não apenas desenvolvimento de funcionalidades, mas também:

> manutenção, investigação, refatoração, testes, arquitetura, integração, deployment e operação de software.

A aplicação será propositalmente evoluída ao longo do tempo, incluindo decisões imperfeitas, dívida técnica e problemas simulados que deverão ser identificados e corrigidos durante o desenvolvimento.

---

**MoveX — building software by evolving software.**
