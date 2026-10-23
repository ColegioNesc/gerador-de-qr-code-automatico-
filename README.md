# 📱 Gerador de QR Code Automático & Personalizado

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL / MariaDB](https://img.shields.io/badge/MySQL%20%2F%20MariaDB-PDO-00758F?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![WCAG 2.1 Contrast](https://img.shields.io/badge/WCAG%202.1-Contrast%20Validation-10b981?style=for-the-badge)](https://www.w3.org/WAI/standards-guidelines/wcag/)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE)

Aplicação web completa, moderna e responsiva (Mobile-First) para **geração instantânea de QR Codes personalizados**. Totalmente gratuita, rápida e sem necessidade de cadastro ou login.

O sistema permite criar códigos para **Links (URLs)**, **WhatsApp**, **Textos** e **Redes Wi-Fi**, com seleção customizada de cores para os pontos escuros e o fundo, validação automática da taxa de contraste (norma WCAG 2.1) e exportação em **PNG de alta resolução** e **SVG vetorial**.

---

## ✨ Principais Funcionalidades

- ⚡ **100% Gratuito & Sem Cadastro:** Acesso imediato a todas as ferramentas sem barreiras de login.
- 🎨 **Personalização de Cores & Presets:** Escolha as cores dos módulos e do fundo com seletores visuais ou valores hexadecimais, além de presets com alto contraste prontos para uso e botão de inverter cores.
- 👁️ **Validador de Contraste WCAG em Tempo Real:** Medição precisa da luminância relativa e taxa de contraste. Garante que os códigos gerados sejam perfeitamente legíveis pelas câmeras de smartphones (alerta e bloqueio caso a taxa seja inferior a 3.0:1).
- 🗂️ **Navegação Modular por Abas:**
  - **Links / URL:** Validação de URL e contagem de caracteres (suporte até 2.953 caracteres alfanuméricos).
  - **WhatsApp:** Link direto `wa.me` com número e mensagem pré-definida opcional.
  - **Texto Livre:** Codificação de notas, mensagens ou cupons.
  - **Redes Wi-Fi:** Formato MeCard padrão para conexão rápida por escaneamento (WPA/WPA2/WPA3, WEP ou Aberta).
- 🔍 **Pré-Visualização Instantânea:** Visualização ao vivo no navegador conforme você digita e altera as cores.
- 📥 **Exportação Multiformato:**
  - **PNG:** Ideal para redes sociais, sites e apresentações digitais.
  - **SVG:** Formato vetorial escalável para impressões gráficas em qualquer tamanho (banners, panfletos, cartões de visita) sem perda de qualidade.
- 🕒 **Histórico de Sessão com MySQL:** Visualize, rebaixe ou limpe seus QR Codes gerados durante a sessão atual.
- 🛡️ **Interface Amigável e Moderna:** Mensagens de erro e avisos apresentados via **Toasts**, e confirmações importantes tratadas em **Modais do Bootstrap**, sem travamentos por caixas de diálogo nativas do navegador.
- 🖤 **Design Dark Puro:** Fundo 100% preto (`#000000`), visual elegante e foco no contraste.

---

## 🛠️ Stack Tecnológico

- **Backend:** PHP 8.1+ estruturado com sessões nativas.
- **Biblioteca QR Code:** [`chillerlan/php-qrcode`](https://github.com/chillerlan/php-qrcode) para renderização robusta de PNG e SVG no servidor.
- **Banco de Dados:** MySQL / MariaDB utilizando **PDO** com *Prepared Statements* para proteção total contra SQL Injection.
- **Frontend:** HTML5, CSS3, JavaScript puro (ES6+) e [Bootstrap 5](https://getbootstrap.com/) com [Bootstrap Icons](https://icons.getbootstrap.com/).
- **Preview Client-side:** QRCode.js para feedback visual em tempo real.

---

## 📁 Estrutura de Diretórios

```text
gerador-de-qr-code-automatico-/
│
├── api/                      # Endpoints de backend isolados
│   ├── generate.php          # Geração, validação de contraste e persistência
│   ├── download.php          # Download seguro de arquivos (proteção contra path traversal)
│   ├── history.php           # Consulta dos códigos criados na sessão
│   └── clear_history.php     # Limpeza dos arquivos e registros da sessão
│
├── assets/                   # Arquivos estáticos de frontend
│   ├── css/
│   │   └── style.css         # Estilos customizados, tema preto (#000) e componentes
│   ├── js/
│   │   └── main.js           # Lógica de interface, cálculo WCAG, previews e AJAX
│   └── vendor/               # Bootstrap 5, Bootstrap Icons e QRCode.js
│
├── config/
│   └── db.php                # Conexão PDO segura e gerenciamento de sessões
│
├── database/
│   └── schema.sql            # Script de criação da base de dados e tabela
│
├── generated/                # Armazenamento temporário dos QR Codes gerados (.png, .svg)
│
├── includes/                 # Componentes modulares reutilizáveis
│   ├── functions.php         # Cálculos matemáticos de luminância/contraste e validações
│   ├── header.php            # Cabeçalho da aplicação
│   ├── footer.php            # Rodapé, modais de diálogo/ajuda e scripts
│   └── toast.php             # Container de notificações Toast
│
├── composer.json             # Dependências PHP do projeto
├── index.php                 # View principal do sistema
├── LICENSE                   # Licença MIT
├── README.md                 # Documentação do projeto
└── requisitos.md             # Especificações e regras de negócio CORE
```

---

## ⚙️ Pré-requisitos

Antes de iniciar, certifique-se de possuir em seu ambiente:

1. **PHP 8.1** ou superior com as extensões ativadas:
   - `pdo_mysql`
   - `mbstring`
   - `gd` ou `imagick`
2. **Composer** instalado.
3. Servidor de banco de dados **MySQL** ou **MariaDB**.

---

## 🚀 Guia de Instalação e Execução

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU-USUARIO/gerador-de-qr-code-automatico-.git
cd gerador-de-qr-code-automatico-
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Configurar o Banco de Dados

Importe o arquivo SQL no seu MySQL/MariaDB:

```bash
mysql -u root -p < database/schema.sql
```

Caso queira utilizar o usuário padrão já previsto no arquivo de conexão, execute no MySQL:

```sql
CREATE USER IF NOT EXISTS 'qrcode_user'@'localhost' IDENTIFIED BY 'qrcode_pass123';
GRANT ALL PRIVILEGES ON gerador_qrcode.* TO 'qrcode_user'@'localhost';
FLUSH PRIVILEGES;
```

> **Nota:** Para personalizar as credenciais de acesso ao banco de dados, edite o arquivo [`config/db.php`](config/db.php):
> ```php
> define('DB_HOST', 'localhost');
> define('DB_NAME', 'gerador_qrcode');
> define('DB_USER', 'seu_usuario');
> define('DB_PASS', 'sua_senha');
> ```

### 4. Permissões de escrita

Certifique-se de que o diretório `generated/` possui permissão de escrita para o servidor web:

```bash
chmod 777 generated
```

### 5. Iniciar o servidor local

Você pode rodar a aplicação imediatamente usando o servidor embutido do PHP:

```bash
php -S 127.0.0.1:8000
```

Acesse em seu navegador:
👉 **`http://127.0.0.1:8000`**

---

## 📐 Regras de Negócio & Padrões Implementados

- **Contraste Mínimo:** Conforme as diretrizes WCAG 2.1, combinações de cores com contraste inferior a `3.0:1` são bloqueadas na geração final para evitar QR Codes ilegíveis.
- **Tamanho Máximo da URL:** A URL informada não pode exceder `2.953 caracteres alfanuméricos`, respeitando a capacidade máxima de armazenamento de dados dos módulos do padrão QR Code.
- **Tratamento de Interface:**
  - Feedbacks de erro e sucesso são exibidos via **Toast flutuante**.
  - Ações críticas (como exclusão do histórico) e mensagens de confirmação utilizam **Modais interativos**, eliminando o uso de caixas nativas `alert()` e `confirm()`.

---

## 📄 Licença

Este projeto está sob a licença [MIT](LICENSE). Consulte o arquivo `LICENSE` para obter mais detalhes.
