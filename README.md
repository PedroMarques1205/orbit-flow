```
# ✈️ Sistema de Gerenciamento de Viagens Corporativas – Desafio Onfly

Este projeto é uma aplicação Full Stack desenvolvida como parte do processo seletivo da Onfly. Ele permite que usuários cadastrem e gerenciem pedidos de viagem corporativa. A aplicação é composta por uma API em Laravel (backend) e uma interface em Vue.js (frontend), com ambiente de execução configurado via Docker utilizando Laradock.

## 🛠 Tecnologias Utilizadas

- **Back-end**: Laravel
- **Front-end**: Vue.js
- **Banco de dados**: MySQL
- **Ambiente de desenvolvimento**: Docker + Laradock
- **Testes**: PHPUnit

## 🚀 Como Executar o Projeto

### 🔧 Back-end (Laravel)

1. Acesse a pasta laradock que está dentro do diretório badger:

```bash
cd badger/laradock
```

2. Suba os containers necessários:

```bash
docker-compose up -d nginx mysql phpmyadmin
```

3. Verifique se os containers estão rodando:

```bash
docker-compose ps
```

4. Acesse o container workspace:

```bash
docker-compose exec --user=laradock workspace bash
```

5. Rode as migrations (dentro do container):

```bash
php artisan migrate
```

- A API estará disponível em: http://localhost
- O phpMyAdmin estará disponível em: http://localhost:8081  
  (Usuário: `root`, Senha: `root`)

### 🌐 Front-end (Vue.js)

1. Acesse a pasta do frontend:

```bash
cd Front-Info-Integrador-main
```

2. Instale as dependências:

```bash
npm install
```

3. Rode o servidor de desenvolvimento:

```bash
npm run dev
```

- A interface estará disponível em: http://localhost:8081

## ✅ Executando os Testes

Para rodar os testes do back-end com PHPUnit:

1. Acesse a pasta badger:

```bash
cd badger
```

2. Execute o comando:

```bash
php artisan test
```

## 📌 Funcionalidades Implementadas

- Cadastro de pedidos de viagem
- Atualização de status (aprovado, cancelado)
- Restrições por usuário (somente terceiros podem alterar status)
- Filtros por status, destino e período
- Notificações em mudanças de status
- Testes automatizados no back-end

## 📝 Observações

- O projeto está containerizado para facilitar o setup.
- Interface amigável com feedback visual e filtros dinâmicos.

## 📬 Contato

**Pedro Marques**  
Email: [pedrophmo1205@gmail.com]  
```
