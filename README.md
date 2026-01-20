# 🚀 Star Wars Explorer - MCP Server

Um servidor **Model Context Protocol (MCP)** desenvolvido com Laravel que fornece acesso a dados da franquia Star Wars através de uma API externa e banco de dados local.

## 📋 Sobre o Projeto

Este projeto implementa um servidor MCP usando Laravel que permite:

- 🔍 **Buscar personagens** de Star Wars no banco de dados local
- 📥 **Importar personagens** da API externa (SWAPI) para o banco local
- 💾 **Armazenar dados** localmente para consultas rápidas
- 🤖 **Integração com IAs** através do protocolo MCP

## 🛠️ Tecnologias

- **PHP 8.5+**
- **Laravel 12**
- **Laravel MCP** - Protocolo Model Context Protocol
- **Laravel Sail** - Ambiente Docker
- **MySQL 8.4** - Banco de dados
- **Redis** - Cache

## 📦 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- [Docker](https://www.docker.com/get-started) e Docker Compose
- [Git](https://git-scm.com/)

> **Nota:** Como utilizamos Laravel Sail, não é necessário ter PHP instalado localmente. Tudo roda dentro do Docker.

## 🚀 Instalação

### Passo 1: Clonar o Repositório

```bash
git clone <url-do-repositorio>
cd mcp-app
```

### Passo 2: Configurar o Ambiente

```bash
# Copiar o arquivo de configuração de ambiente
cp .env.example .env
```

### Passo 3: Instalar Dependências

```bash
# Instalar dependências do Composer via Sail
./vendor/bin/sail composer install
```

> **Dica:** Se você não tiver o `vendor/bin/sail`, execute primeiro:
> ```bash
> composer install
> ```

### Passo 4: Configurar a Aplicação

```bash
# Gerar a chave da aplicação
./vendor/bin/sail artisan key:generate

# Executar as migrations do banco de dados
./vendor/bin/sail artisan migrate
```

### Passo 5: Iniciar os Containers

```bash
# Iniciar os containers em background
./vendor/bin/sail up -d

# Ou iniciar e ver os logs em tempo real
./vendor/bin/sail up
```

## ▶️ Executando o Servidor MCP

Após a instalação, você tem duas formas de executar o servidor:

### Opção 1: Modo STDIO (Produção)

Execute o servidor MCP diretamente:

```bash
./vendor/bin/sail artisan mcp:start star-wars-explorer
```

Este comando inicia o servidor no modo STDIO, pronto para comunicação via protocolo MCP.

### Opção 2: Inspector (Desenvolvimento/Testes) ⭐ Recomendado

Use o Inspector para testar o servidor com uma interface visual:

```bash
./vendor/bin/sail artisan mcp:inspector star-wars-explorer
```

O Inspector:
- 🌐 Abre uma interface web interativa
- 🧪 Permite testar todas as tools disponíveis
- 📊 Mostra as respostas do servidor em tempo real
- 🔍 Facilita o debug e desenvolvimento

## 🧪 Testando as Funcionalidades

O servidor MCP expõe uma tool chamada `star-wars-tool` com as seguintes funcionalidades:

### 1. Buscar Todos os Personagens

Chame a tool sem parâmetros para listar todos os personagens salvos localmente:

```json
{}
```

### 2. Buscar por Nome

Busque personagens pelo nome no banco de dados local:

```json
{
  "search": "Luke"
}
```

### 3. Importar da API Externa

Importe um personagem da API SWAPI usando o ID:

```json
{
  "id": 1
}
```

> **IDs disponíveis na API:** 1, 2, 3, 4, 5... (consulte a documentação da SWAPI)

## 📁 Estrutura do Projeto

```
mcp-app/
├── app/
│   ├── Mcp/
│   │   ├── Servers/
│   │   │   ├── StarWarsServer.php    # Servidor MCP principal
│   │   │   └── MyAssistantServer.php # Servidor exemplo
│   │   └── Tools/
│   │       ├── StarWarsTool.php      # Tool principal
│   │       └── GetProjectStats.php   # Tool exemplo
│   ├── Models/
│   │   └── Character.php             # Model do personagem
│   └── Services/
│       ├── External/
│       │   └── SwapiService.php      # Serviço de integração com API
│       └── Models/
│           └── CharacterService.php  # Lógica de negócio
├── routes/
│   └── ai.php                        # Registro dos servidores MCP
├── database/
│   └── migrations/                   # Migrations do banco
└── compose.yaml                       # Configuração Docker
```

## 🔧 Comandos Úteis

### Gerenciamento do Docker

```bash
# Iniciar containers
./vendor/bin/sail up -d

# Parar containers
./vendor/bin/sail down

# Ver logs
./vendor/bin/sail logs

# Acessar shell do container
./vendor/bin/sail shell
```

### Comandos Artisan

```bash
# Ver logs do Laravel
./vendor/bin/sail artisan pail

# Limpar cache
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear

# Executar migrations
./vendor/bin/sail artisan migrate

# Rollback migrations
./vendor/bin/sail artisan migrate:rollback
```

### Comandos MCP

```bash
# Listar servidores MCP disponíveis
./vendor/bin/sail artisan mcp:inspector

# Iniciar servidor específico
./vendor/bin/sail artisan mcp:start star-wars-explorer

# Abrir inspector para testes
./vendor/bin/sail artisan mcp:inspector star-wars-explorer
```

## 🎯 Funcionalidades da Tool

A `StarWarsTool` oferece três modos de operação:

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `id` | `integer` (opcional) | ID do personagem na API SWAPI para importar |
| `search` | `string` (opcional) | Nome do personagem para buscar localmente |

**Comportamento:**
- Se `id` for fornecido: importa da API e salva no banco local
- Se `search` for fornecido: busca no banco local por nome
- Se nenhum parâmetro: retorna todos os personagens do banco local

## 🔌 Integração com Clientes MCP

Para integrar este servidor MCP com um cliente (como Claude Desktop, Cursor, etc.), configure:

**Handle:** `star-wars-explorer`

**Comando:**
```bash
php artisan mcp:start star-wars-explorer
```

**Transport:** STDIO

## 📝 Padrões de Código

Este projeto segue as melhores práticas do Laravel:

- ✅ `declare(strict_types=1);` em todos os arquivos PHP
- ✅ Tipagem estrita em propriedades, parâmetros e retornos
- ✅ Services como `final readonly class`
- ✅ Controllers magros (Thin Controllers)
- ✅ Lógica de negócio em Services
- ✅ Dependency Injection via construtor

## 🐛 Troubleshooting

### Erro: "MCP Server with name [star-wars-explorer] not found"

Verifique se o servidor está registrado em `routes/ai.php`:

```php
Mcp::local('star-wars-explorer', StarWarsServer::class);
```

### Erro: "Connection refused" ao iniciar Sail

Certifique-se de que o Docker está rodando:

```bash
docker ps
```

### Erro ao executar migrations

Verifique se o banco de dados está configurado corretamente no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

## 📚 Recursos Adicionais

- [Documentação Laravel MCP](https://laravel.com/docs/mcp)
- [Model Context Protocol](https://modelcontextprotocol.io/)
- [Laravel Sail](https://laravel.com/docs/sail)
- [SWAPI - Star Wars API](https://swapi.bry.com.br/)

## 📄 Licença

Este projeto está sob a licença MIT.

---

**Desenvolvido com ❤️ usando Laravel e MCP**
