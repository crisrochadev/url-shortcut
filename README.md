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
            <th colspan="2">http://localhost:8000<th>
        </tr>
        <tr>
            <th>Método</th>
            <th>Endpoint<th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>GET</td>
            <td>/links?page=2<td>
            <td>Tráz apenas os links habilitados</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/links<td>
            <td>Cria um novo link encurtado</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/{slug}<td>
            <td>Traz o link pelo slug</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/disable/{id}<td>
            <td>Desabilita um link pelo id</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/reactivate/{id}<td>
            <td>Reativa um link expirado</td>
        </tr>
    </tbody>
</table>
