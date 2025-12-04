Agenda Eletrônica - PHP PDO
https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white
https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white
https://img.shields.io/badge/AdminLTE-3.2.0-367fa9?style=for-the-badge&logo=adminlte&logoColor=white

📋 Sumário
Instalação Rápida

Estrutura do Projeto

Configuração do Banco de Dados

Configuração do PHP

Configuração do Servidor Web

Configuração de Permissões

Configuração de E-mail

Configuração de Upload

Variáveis de Ambiente

Solução de Problemas

🚀 Instalação Rápida
1. Clonar o Repositório
bash
git clone https://github.com/leandro-oe/new_agenda_2024.git
cd new_agenda_2024
2. Configurar Ambiente Local
bash
# Copiar para diretório do servidor web
# XAMPP (Windows):
copy new_agenda_2024 C:\xampp\htdocs\

# Linux/Mac:
sudo cp -r new_agenda_2024 /var/www/html/
📁 Estrutura do Projeto
text
new_agenda_2024/
├── 📂 config/
│   ├── 📄 database.php              # Configurações do banco de dados
│   ├── 📄 config.php                # Configurações gerais
│   ├── 📄 mail.php                  # Configurações de e-mail
│   └── 📄 constants.php             # Constantes do sistema
├── 📂 includes/
│   ├── 📄 db_connection.php         # Conexão PDO com banco
│   ├── 📄 functions.php             # Funções auxiliares
│   ├── 📄 auth_functions.php        # Funções de autenticação
│   ├── 📄 session_manager.php       # Gerenciamento de sessões
│   └── 📄 security.php              # Funções de segurança
├── 📂 assets/
│   ├── 📂 css/
│   │   ├── 📄 style.css            # Estilos principais
│   │   └── 📄 custom.css           # Estilos personalizados
│   ├── 📂 js/
│   │   ├── 📄 main.js              # Scripts principais
│   │   └── 📄 contacts.js          # Scripts de contatos
│   └── 📂 img/
│       └── 📂 icons/               # Ícones do sistema
├── 📂 uploads/
│   ├── 📂 profiles/                # Fotos de perfil dos usuários
│   │   └── 📄 default.png          # Imagem padrão
│   └── 📂 exports/                 # Arquivos exportados
├── 📂 vendor/
│   ├── 📂 adminlte/                # Template AdminLTE
│   ├── 📂 fpdf/                    # Biblioteca PDF
│   └── 📂 phpmailer/               # Biblioteca de e-mail
├── 📂 pages/
│   ├── 📄 dashboard.php            # Painel principal
│   ├── 📄 contacts.php             # Gerenciamento de contatos
│   ├── 📄 add_contact.php          # Adicionar contato
│   ├── 📄 edit_contact.php         # Editar contato
│   ├── 📄 profile.php              # Perfil do usuário
│   ├── 📄 settings.php             # Configurações
│   └── 📄 reports.php              # Relatórios
├── 📄 .htaccess                    # Regras do Apache
├── 📄 index.php                    # Página inicial/login
├── 📄 register.php                 # Registro de usuários
├── 📄 logout.php                   # Logout do sistema
├── 📄 new_agenda.sql               # Script SQL do banco
└── 📄 README.md                    # Documentação
🗄️ Configuração do Banco de Dados
Arquivo: config/database.php
php
