# Sistema de Transações 💰

**Projeto Full Stack** com backend e frontend em repositórios separados.

Um sistema completo de gerenciamento de transações financeiras com controle rigoroso de saldo, histórico e validações de negócio.

## 🚀 Funcionalidades

- Login de administrador com Sanctum
- Cadastro, edição e listagem de clientes
- Transações de **crédito** e **débito** (validação: débito não permite saldo negativo)
- Perfil do cliente com **histórico completo** de transações e saldo atualizado em tempo real
- Arquitetura totalmente separada (Backend API + Frontend SPA)
- Testes unitários e funcionais no backend (PHPUnit)

## 🛠️ Tecnologias

**Backend**
- Laravel 11+
- PostgreSQL
- Laravel Sanctum (autenticação via token)
- PHPUnit + RefreshDatabase + Factories

**Frontend**
- Vue.js 3 (Composition API + Vite)
- SPA consumindo API REST

## 📁 Repositórios

- **Backend** → [Sistema-de-Transacoes](https://github.com/Paulagnavarro/Sistema-de-Transacoes)
- **Frontend** → [sistema-transacoes-frontend](https://github.com/Paulagnavarro/sistema-transacoes-frontend)

## 📋 Como Rodar o Projeto

### Pré-requisitos
- PHP 8.1+
- Composer
- Node.js 18+
- PostgreSQL 14+

### 1. Configurar o Banco de Dados
Crie dois bancos no PostgreSQL:
- `transacoes_db` → ambiente de produção
- `transacoes_test_db` → ambiente de testes

### 2. Backend (Laravel)

git clone https://github.com/Paulagnavarro/Sistema-de-Transacoes.git

cd Sistema-de-Transacoes

composer install

cp .env.example .env

Configure o arquivo .env com suas credenciais do PostgreSQL

php artisan key:generate
php artisan migrate --seed
php artisan serve

Admin inicial criado automaticamente:
E-mail: admin@exemplo.com
Senha: 12345678

Backend roda em: http://127.0.0.1:8000

### 3. Frontend (Vue.js)

git clone https://github.com/Paulagnavarro/sistema-transacoes-frontend.git
cd sistema-transacoes-frontend

npm install
npm run serve

Frontend geralmente roda em: http://localhost:5173

### 4. Como Testar

Acesse o frontend
Faça login com o administrador
Cadastre clientes, realize créditos e débitos
Verifique a validação de saldo negativo e o histórico no perfil do cliente

Rodar os testes do backend:
php artisan test

### Segurança
Cadastro de usuários protegido por autenticação
Apenas administradores logados podem criar novas contas
Autenticação via Sanctum com token
