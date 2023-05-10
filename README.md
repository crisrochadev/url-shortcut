# URL SHORTCUT API

### Comandos
* php migrate
* php artisan serve

### Variavel de Ambiente .env

``` .env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shortener
DB_USERNAME=root
DB_PASSWORD=

# Hashids
HASHIDS_SALT='your_salt'
HASHIDS_MIN_LENGTH=8
```

### Rotas


<table>
    <thead>
        <tr>
            <th>URL</th>
            <th colspan="3">http://localhost:8000 - servidor local</th>
        </tr>
        <tr>
            <th>Método</th>
            <th >Endpoint</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>GET</td>
            <td>/links?page=2&perPage=1&orderBy=desc&filter=google<td>
            <td>Tráz apenas os links habilitados, pode-se passar a páginação, a orderm e filtrar por url</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/links</td>
            <td>Cria um novo link encurtado</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/links/slug/{slug}<td>
            <td>Traz o link pelo slug</td>
        </tr>
        <tr>
            <td>DELETE</td>
            <td>/links/disable/{id}<td>
            <td>Desabilita um link pelo id</td>
        </tr>
         <tr>
            <td>GET</td>
            <td>/links/disabled<td>
            <td>Tráz os links desabilitados</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/links/reactivate/{id}<td>
            <td>Reativa um link expirado</td>
        </tr>
    </tbody>
</table>

<hr/>

## Sobre o Projeto

  O projeto foi desenvolvido utilizando Laravel. A biblioteca Hashids foi utilizada para criar os slugs encurtados dos links. O projeto foi dividido em controllers, repositories e models, e consiste apenas de uma API, sem views.

### Funcionalidades
- Criar um link com data de expiração de 7 dias
- Listar os links não desativados
- Listar os links ativados
- Reativar um link expirado
- Buscar um link pelo slug (retorna apenas links ativos e não expirados)

### Banco de dados
O projeto utiliza um banco de dados MySQL com uma tabela que possui as seguintes colunas:

- `id` - ID do link (auto incremento , unsigned)
- `url` - URL do link (string)
- `slug` - Slug encurtado do link (string)
- `expiration_date` - Data de expiração do link (datetime)
- `deleted_at` - Data de exclusão (soft delete do Laravel)
- `updated_at` - Data de atualização (automática pelo Laravel)
- `created_at` - Data de criação (automática pelo Laravel)
