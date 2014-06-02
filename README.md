SQLAnywhereClient
=================

Classe para conexão com banco de dados Sybase com PHP baseada na biblioteca sqlanywhere.

Classe foi baseada na Classe Nativa do PDO.

TODO:
- Testar classe com eficácia
 
## Instalação
=================

1- Primeiro instale o modulo do sqlanywhere em seu PHP [Clincando aqui!](http://scn.sap.com/docs/DOC-40537).

2- Use o composer para instalar o package ao seu projeto adicionado a linha abaixo ao `require`:

    // ...
    "require": {
        "cagartner/SQLAnywhereClient": "dev-master"
    },
    // ...

# Como usar

Abaixo você ve alguns exemplos de como usar a classe:

### Conexão `SQLAnywhereClient::__construct`:

```php
<?php
    require '../vendor/autoload.php';

    use Cagartner\SQLAnywhereClient;

    try {
        $dns = "uid={usuario};pwd={senha};ENG={nome-do-banco};commlinks=tcpip{host={seu-host};port={sua-porta}}";
        $con = new SQLAnywhereClient( $dns );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
```
Você pode definir duas opções iniciais junto com a conexão, que são as seguintes: `auto_commit` e `is_persistent`.

* `auto_commit` Ativa os commits automaticamente por padrão é `true`;
* `is_persistent` Define conexão no modo persistente por padrão é `false`;

```php
<?php
    require '../vendor/autoload.php';

    use Cagartner\SQLAnywhereClient;

    try {
        $dns = "uid={uid};pwd={senha};ENG={};commlinks=tcpip{host={seuuhost};port={suasenha}}";
        $autocommit = false;
        $persistent = true;

        $con = new SQLAnywhereClient( $dns, $autocommit, $persistent );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
```


### Executar comando SQL `SQLAnywhereClient::exec()`:

```php
<?php

    $sql = "SELECT * FROM Usuarios";
    $result = $con->exec( $sql );

    echo "<pre>";
    print_r($result->fetch());
    echo "</pre>";
    exit;
?>
```

### Executar comando SQL com retorno de dados `SQLAnywhereClient::query()` :

Método retornar um array com várias posições

```php
<?php

    $sql = "SELECT nome, email FROM Usuarios";

    foreach ($con->query( $sql ) as $resultado) {
        print_r($resultado);
    }
    exit;
?>
```

### Retornar uma linha `SQLAnywhereQuery::fetch`

Retornar a primeira linha

```php
<?php
    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );
    $usuario = $resultado->fetch();

    print_r($usuario);
    exit;
?>
```
 
### Formato de retorno dos dados

Podemos escolher o formato dos dados no retorno com as seguintes constantes da classe `SQLAnywhereClient`
```php
<?php
    // Retornar em um array com idexação por numero e coluna
    SQLAnywhereClient::FETCH_ARRAY;

    // Retornar em um array com idexação por coluna
    SQLAnywhereClient::FETCH_ASSOC; // Formato Padrão!

    // Retornar em um array com idexação por coluna
    SQLAnywhereClient::FETCH_OBJECT;

    // Retornar em um array com idexação por linha de dados
    SQLAnywhereClient::FETCH_ROW;

    // Retornar em um array com idexação por colunas
    SQLAnywhereClient::FETCH_FIELD;
?>
```

Exemplo:

```php
<?php

    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );
    $usuario = $resultado->fetch( SQLAnywhereClient::FETCH_OBJECT );

    print_r($usuario);
    exit;
?>
```

### Retornar todas as linhas `SQLAnywhereQuery::fetchAll`

Retornar Todas as linhas encontradas

```php
<?php
    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );
    $usuario = $resultado->fetchAll();

    print_r($usuario);
    exit;
?>
```

Como no caso assima do fetch, você pode retornar os valores em diferentes formatos utilizando as mesmas constantes, exemplo:

```php
<?php

    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );
    $usuario = $resultado->fetchAll( SQLAnywhereClient::FETCH_OBJECT );

    print_r($usuario);
    exit;
?>
```

### Números de linhas `SQLAnywhereQuery::rowCount`

Retornar o total de linhas encontradas

```php
<?php
    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );

    echo "Foram encontrados " . $resultado->rowCount() . " registros.";
    exit;
?>
```

Ou também da seguinte maneira: 

```php
<?php
    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );

    echo "Foram encontrados " . $resultado->count() . " registros.";
    exit;
?>
```

### Números de colunas `SQLAnywhereQuery::fieldCount`

Retornar o total de colunas encontradas

```php
<?php
    $sql = "SELECT nome, email FROM Usuarios";
    $resultado = $con->exec( $sql );

    echo "Foram encontrados " . $resultado->fieldCount() . " colunas na tabela.";
    exit;
?>
```

### Último Id inserido `SQLAnywhereClient::lastInsertId()` 

Retorna o último o valor do último id inserido na conexão

```php
<?php
    $sql = "INSERT INTO Usuarios  nome, email VALUES ('Carlos', 'contato@carlosgartner.com.br')";
    if ($con->exec( $sql )) {
        echo $con->lastInsertId();
    }
    exit;
?>
```

### Último Id inserido `SQLAnywhereClient::lastInsertId()` 

Retorna o último o valor do último id inserido na conexão

```php
<?php
    $sql = "INSERT INTO Usuarios  nome, email VALUES ('Carlos', 'contato@carlosgartner.com.br')";
    if ($con->exec( $sql )) {
        echo $con->lastInsertId();
    }
    exit;
?>
```

### Prepared Statement `SQLAnywhereClient::prepare()`:

Preparar sql usando `?`:

```php
<?php
    $sql = "INSERT INTO Usuarios  nome, email VALUES (?, ?)";
    $stmnt = $con->prepare( $sql );
    if ($stmnt->execute(array('Carlos', 'contato@carlosgartner.com.br'))) {
         echo $con->lastInsertId();
    }
    exit;
?>
```

E usando nomes para os parametros:

```php
<?php
    $sql = "INSERT INTO Usuarios  nome, email VALUES (:nome, :email)";
    $stmnt = $con->prepare( $sql );
    if ($stmnt->execute(array(
        ':nome' => 'Carlos', 
        ':email' => 'contato@carlosgartner.com.br'
    ))) {
         echo $con->lastInsertId();
    }
    exit;
?>
```

### Bind Param `SQLAnywherePrepared::bindParam()`:

```php
<?php
    $sql = "INSERT INTO Usuarios  nome, email VALUES (:nome, :email)";
    $stmnt = $con->prepare( $sql );

    $nome = "Carlos A.";
    $email = "contato@carlosgartner.com.br";

    $stmnt->bindParam(':nome', $nome);
    $stmnt->bindParam(':email', $email);

    if ($stmnt->execute()) {
         echo $con->lastInsertId();
    }
    exit;
?>
```