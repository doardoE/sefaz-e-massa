# 💰 Sefaz é Massa

Sistema para **gestão de tributos municipais**, com cadastro de arrecadações, filtros dinâmicos e dashboard interativo com gráficos.

Este projeto é dividido em duas partes integradas:
- **Backend:** API RESTful em Laravel 12 (PHP 8.2+)
- **Frontend:** Interface Vue 3 com Vite e Tailwind CSS

---

## 🚀 Tecnologias

```bash
  #Backend                            #Frontend
- Laravel 12                        - Vue 3   
- PHP 8.2+                          - Tailwind CSS 
- Sanctum (autenticação leve)       - Axios  
- SQLite                            - Chart.js 
```

---

## ⚙️ Requisitos
Antes de começar, garanta que você tem:
```bash
- PHP ≥ 8.2
- Composer
- Node.js ≥ 18
- npm
- Banco de dados (SQLite)
```
---

## 🔧 Instalação

### 1️⃣ Clonar o projeto

```bash
# via HTTPS
git clone https://github.com/doardoE/sefaz-e-massa.git

# ou via SSH
git clone git@github.com:doardoE/sefaz-e-massa.git

cd sefaz-e-massa
```

### 2️⃣ Instalar dependências
🧩 Backend
```bash
composer install
```
💻 Frontend
```bash
npm install
```

### 3️⃣ Configurar o ambiente

Copie o arquivo .env.example e renomeie para .env:
```bash
cp .env.example .env
```

### 4️⃣ Executar migrações e seeders
```bash
php artisan migrate:fresh --seed
```

### 5️⃣ Executar back-end
O servidor será iniciado em: 👉 http://localhost:8000/api
```bash
php artisan serve
```

### 6️⃣ Executar front-end com vite
O servidor será iniciado em: 👉 http://localhost:4173
```bash
npm run build
npm run preview
```

### ⚡ Funcionalidades Principais
```bash
📊 Dashboard e KPIs: exibe totais de arrecadações e gráficos por tributo e período.
🧾 Cadastro de arrecadações: criação, edição e exclusão de registros.
🔍 Filtros dinâmicos: pesquisa por tributo, mês e ano.
🔐 Autenticação simples: com Laravel Sanctum para rotas protegidas.
```
## 🗂 Estrutura de Pastas

### Backend (Laravel)

```bash
📁 api-laravel
├── 📁 app
│   ├── 📁 Controllers
│   │   └── 🐘
│   ├── 📁 Models
│   │   ├── 🐘
├── 📁 database
│   └── 📄 database.sqlite
├── 📁 routes
│   └── 🐘
```

### Frontend (Vue.js)

```bash
📁 front-vue
├── 📁 src
│   ├── 📁 components
│   │   ├── 📄 
│   ├── 📁 plugins
│   │   └── 📄 axios.js
│   ├── 📁 router
│   │   └── 📄 index.js
│   ├── 📁 views
│   │   ├── 📄 
└── ⚙️ package.json
```

## 🔗 Endpoints da API

| Método | URL | Descrição |
|--------|-----|-----------|
| GET    | /api/arrecadacoes | Listar todas as arrecadações |
| POST   | /api/arrecadacoes | Criar nova arrecadação |
| GET    | /api/arrecadacoes/{id} | Detalhes de uma arrecadação |
| PUT    | /api/arrecadacoes/{id} | Atualizar arrecadação |
| DELETE | /api/arrecadacoes/{id} | Deletar arrecadação |
| GET    | /api/arrecadacoes/dashboard | Dados do dashboard (gráficos e resumo) |
| GET    | /api/arrecadacoes/kpis | KPIs gerais |
| POST   | /api/login | Autenticação |
| POST   | /api/logout | Logout |

## 🙏 Agradecimentos

Este projeto foi desenvolvido como parte de um **processo seletivo de estágio** na **Sefaz Maceió**.  
