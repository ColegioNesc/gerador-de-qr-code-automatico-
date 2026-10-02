# Stack tecnológico
Backend: PHP estruturado (com sessões nativas)
Banco de dados: MySQL (PDO para segurança)
Frontend: HTML5, CSS (usando bootstrap 5), JavaScript

## Regras Agents de IA
,geradordeqrcoderules
- Use sempre PDO para conexões e queries no MySQL para evitar SQL Injection.
- Mantenha o código limpo e comente apenas lógicas complexas.
- Separe os arquivos de forma lógica: um arquivo para conexão ( db.php),scripts de backend isolados e viwes em HTML/PHP.
- A estrutura de arquivos deve ser feita sempre de forma modular.
- Estilize as telas com BootStrap 5 de forma responsiva MobileFirst.
- Retorne mensagens de erro claras na interface para o usuario no estilo Toast.
- Trate sempre as mensagens nativas "ex, caixas de mensagens com OK" sempre em um modal.

### regras de negócio CORE
senhas devem ser armazenadas com hash seguro

#### Obejetivos
Criar o sistema que gera Qr code personalizados de forma rapida, facil e gratuita
sem necessitar de login
