# Catálogo de Produtos

Sistema de catálogo de produtos — permite organizar, cadastrar e visualizar produtos com interface web, ideal para lojas online ou controle de estoque.

## 📌 Sumário / Índice
- Sobre o projeto
- Tecnologias
- Pré-requisitos
- Instalação do Servidor Local
- Como usar / Instalar o projeto
- Funcionalidades
- Estrutura de Pastas
- Demonstração
- Contribuição
- Licença

## 🧰 Sobre o projeto
Este projeto é um sistema de catálogo de produtos desenvolvido para facilitar a criação, organização e visualização de um inventário de produtos. Permite adicionar, listar e visualizar produtos de forma simples, sendo útil para lojas, portfólios de itens ou controle de estoque.

## 🔧 Tecnologias
Linguagem: PHP, Frontend: HTML5, CSS3, JavaScript, Servidor Local: WAMP, XAMPP, LAMP ou MAMP, Banco de dados: MySQL (opcional)

## ✅ Pré-requisitos
Navegador web moderno, Servidor local PHP (WAMP, XAMPP, LAMP ou MAMP), MySQL (se usar banco de dados)

## 🖥️ Instalação do Servidor Local
Para rodar qualquer projeto PHP, siga o passo a passo abaixo:

1. WAMP (Windows): Acesse https://www.wampserver.com/en/, baixe e instale a versão compatível, abra o WampServer; o ícone deve ficar verde indicando que Apache e MySQL estão funcionando.

2. XAMPP (Windows / Linux / macOS): Acesse https://www.apachefriends.org, baixe e instale a versão correspondente ao seu sistema, abra o XAMPP Control Panel e clique em Start em Apache e MySQL.

3. LAMP (Linux): No terminal, execute: sudo apt update && sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql && sudo systemctl restart apache2.

4. MAMP (macOS / Windows): Acesse https://www.mamp.info, baixe e instale, abra o aplicativo e clique em Start Servers.

Pastas padrão para colocar os projetos: XAMPP: htdocs/, WAMP: www/, LAMP: /var/www/html/, MAMP: htdocs/. Teste o servidor acessando no navegador: http://localhost/

## 🚀 Como usar / Instalar o projeto
1. Clone o repositório: git clone https://github.com/frannciscosilva063-stack/Catalogo-de-produtos.git
2. Coloque a pasta Catalogo-de-produtos dentro da pasta do servidor local.
3. Inicie o servidor (Apache + MySQL se necessário).
4. Abra o navegador e acesse: http://localhost/Catalogo-de-produtos/

## ✨ Funcionalidades
Visualização de produtos, Cadastro de novos produtos via formulário, Organização por categorias, Layout responsivo, Backend em PHP para processamentos.

## 🗂️ Estrutura de Pastas
config/ → configurações do sistema, dist/ → arquivos gerados, img/ → imagens do site e produtos, includes/ → componentes PHP reutilizáveis, loja/ → lógica do catálogo, paginas/ → páginas do site, plugins/ → bibliotecas externas

## 🎯 Demonstração
Adicione aqui prints ou GIFs mostrando a interface de produtos e o cadastro funcionando.

## 🤝 Contribuição
Faça um fork do projeto, crie uma branch: git checkout -b feature/nova-feature, commit: git commit -m "Descrição da alteração", push: git push origin feature/nova-feature, abra um Pull Request.

## 📄 Licença
Este projeto está sob a licença MIT.
