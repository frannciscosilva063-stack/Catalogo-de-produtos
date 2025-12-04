Agenda Eletrônica - PHP PDO
https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white
https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white
https://img.shields.io/badge/AdminLTE-3.2.0-367fa9?style=for-the-badge&logo=adminlte&logoColor=white

Uma solução completa de agenda de contatos desenvolvida em PHP procedural com PDO, oferecendo uma experiência moderna e segura para gerenciamento de contatos pessoais.

✨ Funcionalidades
🔐 Autenticação e Segurança
Sistema de Login/Cadastro com senhas criptografadas

Proteção de rotas - acesso restrito para usuários autenticados

Sessões seguras para manter os usuários logados

👥 Gerenciamento de Contatos
CRUD completo de contatos (Criar, Ler, Atualizar, Excluir)

Contatos por usuário - cada usuário gerencia seus próprios contatos

Campos informativos: nome, telefone, e-mail

👤 Perfil do Usuário
Edição completa de informações pessoais

Upload de foto de perfil

Atualização de credenciais (e-mail, senha)

📊 Relatórios e Exportação
Tabelas dinâmicas com DataTable.js

Exportação para PDF de contatos

Exportação para Excel/CSV

Busca e filtros avançados

🛠️ Tecnologias Utilizadas
Backend
PHP 8.0+ (Procedural)

PDO (PHP Data Objects) para acesso seguro ao banco

MySQL como sistema gerenciador de banco de dados

Frontend
HTML5 e CSS3

JavaScript (Vanilla)

AdminLTE 3 - Template administrativo responsivo

DataTables - Para tabelas interativas

Font Awesome - Ícones

Bootstrap 4 - Framework CSS

Bibliotecas e Ferramentas
FPDF - Geração de PDFs

PHP Mailer - Envio de e-mails

Session Manager - Gerenciamento de sessões

📋 Pré-requisitos
Servidor web (Apache, Nginx)

PHP 8.0 ou superior

MySQL 5.7 ou superior

Extensões PHP habilitadas:

PDO MySQL

GD Library (para manipulação de imagens)

Mbstring

OpenSSL

🚀 Instalação
1. Clone o Repositório
bash
git clone https://github.com/leandro-oe/new_agenda_2024.git
cd new_agenda_2024
2. Configure o Ambiente
Coloque os arquivos na pasta htdocs (XAMPP) ou www (WAMP/MAMP)

Certifique-se de que o servidor Apache e MySQL estão rodando

3. Configure o Banco de Dados
sql
-- Crie o banco de dados
CREATE DATABASE agenda_eletronica;

-- Ou importe o arquivo SQL fornecido
-- Via phpMyAdmin ou linha de comando:
mysql -u root -p agenda_eletronica < new_agenda.sql
4. Configure as Credenciais
Edite o arquivo config/database.php ou config.php (conforme a estrutura do projeto):

php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'agenda_eletronica');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
?>
5. Permissões de Diretório
bash
# No Linux/Mac
chmod 755 uploads/
chmod 755 tmp/

# No Windows, garanta permissões de escrita nas pastas:
# - uploads/
# - tmp/
# - logs/
6. Acesse a Aplicação
Abra seu navegador e acesse:

text
http://localhost/new_agenda_2024
📁 Estrutura do Projeto
text
new_agenda_2024/
├── assets/
│   ├── css/           # Estilos personalizados
│   ├── js/            # Scripts JavaScript
│   └── img/           # Imagens e ícones
├── config/
│   └── database.php   # Configuração do banco
├── includes/
│   ├── functions.php  # Funções auxiliares
│   ├── auth.php       # Autenticação
│   └── header.php     # Cabeçalho comum
├── uploads/
│   └── profiles/      # Fotos de perfil
├── pages/
│   ├── dashboard.php  # Painel principal
│   ├── contacts.php   # Gerenciar contatos
│   ├── profile.php    # Perfil do usuário
│   └── reports.php    # Relatórios
├── vendor/            # Dependências (AdminLTE, etc.)
├── index.php          # Página inicial/login
├── register.php       # Registro de usuários
└── new_agenda.sql     # Estrutura do banco
🔧 Configuração do Banco de Dados
Tabelas Principais
sql
-- Tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de contatos
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
🎨 Tema AdminLTE
O projeto utiliza o AdminLTE 3, oferecendo:

Design responsivo que se adapta a qualquer dispositivo

Temas claros e escuros (suporte a modo dark)

Componentes UI ricos (cards, modais, tabelas, forms)

Sidebar colapsável para melhor experiência em mobile

Ícones Font Awesome integrados

🔒 Segurança
Hash de senhas usando password_hash()

Prevenção contra SQL Injection com PDO prepared statements

Proteção XSS com htmlspecialchars()

Validação de entrada em todos os formulários

CSRF Protection em forms sensíveis

Sanitização de uploads de arquivos

📱 Funcionalidades por Página
Página Inicial (/index.php)
Login de usuários

Link para registro

Recuperação de senha

Dashboard (/pages/dashboard.php)
Visão geral dos contatos

Estatísticas rápidas

Acesso rápido às funcionalidades

Contatos (/pages/contacts.php)
Listagem de contatos com paginação

Adicionar novo contato

Editar/Excluir contatos existentes

Busca em tempo real

Perfil (/pages/profile.php)
Editar informações pessoais

Alterar foto de perfil

Mudar senha

Relatórios (/pages/reports.php)
Visualização de contatos em DataTable

Exportar para PDF

Exportar para Excel/CSV

Filtros avançados

🐛 Solução de Problemas
Erro de Conexão com Banco
php
// Verifique no config/database.php
$conn = new PDO("mysql:host=localhost;dbname=agenda_eletronica", "root", "");
Página em Branco
Habilite erros no PHP:

php
ini_set('display_errors', 1);
error_reporting(E_ALL);
Problemas com Upload
Verifique permissões da pasta uploads/

Confirme upload_max_filesize no php.ini

Verifique post_max_size no php.ini

Problemas com Sessões
Verifique se o diretório de sessões tem permissão de escrita

Confirme se as sessões estão sendo iniciadas

📈 Melhorias Futuras
Sistema de grupos de contatos

Lembretes e aniversários

Importação de contatos de CSV

API REST para integração

Aplicativo móvel (React Native/Flutter)

Backup automático do banco

Logs de atividades

Multi-idioma

🤝 Como Contribuir
Faça um Fork do projeto

Crie uma Branch para sua Feature (git checkout -b feature/AmazingFeature)

Commit suas mudanças (git commit -m 'Add some AmazingFeature')

Push para a Branch (git push origin feature/AmazingFeature)

Abra um Pull Request

📄 Licença
Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

👥 Autores
Leandro Oliveira - leandro-oe

🙏 Agradecimentos
AdminLTE pela incrível template

PHP comunidade

Todos os contribuidores e testadores

📞 Suporte
Encontrou um problema ou tem uma sugestão?

Abrir uma Issue

Entre em contato: [seu-email@exemplo.com]
