# Gerenciador de Países e Cidades

## Descrição do Projeto
Esta é uma aplicação web completa para gerenciamento de dados geográficos, focada em **países** e **cidades do mundo**. 
O sistema permite criar, editar, visualizar e excluir registros de países e cidades, além de consultar informações adicionais, como clima atual das cidades e estatísticas.

O projeto contempla:

- **Front End:** HTML, CSS, JavaScript
- **Back End:** PHP
- **Banco de Dados:** MySQL

---

## Funcionalidades

- CRUD completo de **países**:
  - Criar, editar, visualizar e excluir países
  - Visualização detalhada com integração à [REST Countries API](https://restcountries.com/)
  
- CRUD completo de **cidades**:
  - Criar, editar, visualizar e excluir cidades
  - Clima das cidades obtido via API do [OpenWeatherMap](https://openweathermap.org/api)
  
- Pesquisa dinâmica de países e cidades
- Estatísticas:
  - Cidade mais populosa por país
  - Total de cidades por continente

---

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/LucasGanan/Crud-Mundo.git.

2. Configurar o Banco de Dados
   - Crie um banco de dados chamado 'bd_mundo'.
   - Importe as tabelas 'paises' e 'cidades' (via phpMyAdmin ou MySQL Workbench).
   - Configure o arquivo 'db.php' com suas credenciais:
   $DB_HOST = 'localhost';
   $DB_USER = 'root';
   $DB_PASS = '';
   $DB_NAME = 'bd_mundo';

3. Configurar Servidor Local
   - Instale um servidor local com PHP e MySQL (XAMPP, WAMP, Laragon, etc.).
   - Coloque os arquivos do projeto na pasta de acesso do servidor (ex.: 'htdocs' no XAMPP).

4. Acessar o Sistema
   - Abra o navegador e acesse:
    http://localhost/NOME_DO_PROJETO/index.php
   - Use o menu superior para navegar entre Países, Cidades e Estatísticas.
