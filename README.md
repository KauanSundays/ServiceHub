# 🛠️ ServiceHub — Sistema de Tickets

O **ServiceHub** é um sistema de gerenciamento de chamados de serviço desenvolvido como **desafio técnico**, com foco em **performance**, **escalabilidade** e **boas práticas de arquitetura**.

O projeto utiliza **Laravel 11**, **Vue 3 com Inertia.js** e **Docker**, explorando processamento assíncrono com filas para garantir uma experiência fluida ao usuário.

---

## 🚀 Tecnologias Utilizadas

### Backend
- **Laravel 11**
- **PHP 8.5**
- **MySQL 8**
- **Redis** (Cache e Filas)

### Frontend
- **Vue 3**
- **Inertia.js**
- **Tailwind CSS**
- **Shadcn/UI**

### Infraestrutura & Ferramentas
- **Docker (Laravel Sail)**
- **Mailpit** (captura de e-mails em ambiente de desenvolvimento)
- **Vite** (build e hot reload)

---

## 📦 Como Rodar o Projeto

Siga os passos abaixo para subir o ambiente completo utilizando **Docker**.

### 1️⃣ Preparar o Ambiente

Clone o repositório e crie o arquivo de variáveis de ambiente:

```sh
cp .env.example .env
```

💡 Windows (PowerShell):
```sh
copy .env.example .env
```

2️⃣ Subir os Containers

Certifique-se de que o Docker Desktop esteja rodando e execute:
```sh
docker compose up -d
```

Este comando iniciará:

Servidor PHP (Laravel)

Banco de dados MySQL

Redis

Mailpit

3️⃣ Instalar Dependências e Migrar o Banco

Execute os comandos abaixo dentro do container do Laravel:

# Instala as dependências do PHP
```sh
docker compose exec laravel.test composer install
```

# Gera a chave da aplicação
```sh
docker compose exec laravel.test php artisan key:generate
```


# Cria as tabelas e popula com dados iniciais
```sh
docker compose exec laravel.test php artisan migrate --seed
```


4️⃣ Rodar o Frontend (Vite)

No seu terminal local, instale as dependências e inicie o servidor de desenvolvimento:
```sh
npm install
```

```sh
npm run dev
```

5️⃣ Iniciar o Processamento de Filas (Crucial)

O sistema utiliza Redis para processar tarefas pesadas como:

Upload e processamento de anexos

Envio de e-mails

Em um novo terminal, mantenha o worker ativo:

```sh
docker compose exec laravel.test php artisan queue:work
```

🔍 Onde Acessar?

Aplicação:
👉 
```sh
http://localhost
```

💡 Detalhes Técnicos Implementados
⚙️ Processamento em Background

Quando um ticket com anexo é criado, o Laravel despacha um Job para a fila do Redis, evitando bloqueios na requisição e melhorando a experiência do usuário.

🐳 Arquitetura Docker

Todo o ambiente é isolado via Docker, garantindo:

Consistência entre ambientes

Zero conflitos de versão (PHP, MySQL, Redis)

Setup rápido em qualquer máquina

🔐 Segurança e Validação

Validações robustas em formulários

Policies para controle de acesso aos tickets

Separação clara de responsabilidades (Controllers, Services, Jobs)


🔧 Solução de Problemas (Common Issues)
❌ Porta 3306 Ocupada

Se o MySQL não subir, verifique se há outro serviço usando a porta:

netstat -ano | findstr :3306


Pare serviços como XAMPP, WAMP ou MySQL local, se necessário.

❌ Erro de Permissão em Pastas

Certifique-se de que as pastas abaixo têm permissão de escrita:

storage
bootstrap/cache


No Linux/macOS:
```sh
chmod -R 775 storage bootstrap/cache
```

📄 Licença

Este projeto foi desenvolvido exclusivamente para fins de avaliação técnica e estudo.
