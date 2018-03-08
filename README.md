SQLAnywhereClient
=================

Classe para conexão com banco de dados Sybase com PHP baseada na biblioteca sqlanywhere.
Class for connection with database Sybase with PHP, created for PHP library SqlAnywhere.

The development was based on PDO Native Class.

TODO:
- More tests.
 
## Installation
=================

1- First install sqlanywhere module for PHP [Click Here!](http://scn.sap.com/docs/DOC-40537).

2- Use composer to install this package adding the lines bellow in the require section `require`:
    // ...
    "require": {
        "cagartner/SQLAnywhereClient": "dev-master"
    },
    // ...

# How to use
Bellow have some examples of how to use this class.

### Connection `SQLAnywhereClient::__construct`:

```php
<?php
    require '../vendor/autoload.php';

    use Cagartner\SQLAnywhereClient;

    try {
        $dns = "uid={user};pwd={password};ENG={database-name};commlinks=tcpip{host={host};port={port}}";
        $con = new SQLAnywhereClient( $dns );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
```
Você pode definir duas opções iniciais junto com a conexão, que são as seguintes: `auto_commit` e `is_persistent`.
You can define two initials configuration params with the connection: `auto_commit` and `is_persistent`.

* `auto_commit` Enable auto commit, default is `true`;
* `is_persistent` Define persistent mode, default is `false`;

```php
<?php
    require '../vendor/autoload.php';

    use Cagartner\SQLAnywhereClient;

    try {
        $dns = "uid={uid};pwd={password};ENG={};commlinks=tcpip{host={host};port={password}}";
        $autocommit = false;
        $persistent = true;

        $con = new SQLAnywhereClient( $dns, $autocommit, $persistent );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
```


### Executing SQL commands `SQLAnywhereClient::exec()`:

```php
<?php

    $sql = "SELECT * FROM users";
    $result = $con->exec( $sql );

    echo "<pre>";
    print_r($result->fetch());
    echo "</pre>";
    exit;
?>
```

### Executing SQL commands with retrieve of data `SQLAnywhereClient::query()` :

This method return an array with the data

```php
<?php

    $sql = "SELECT name, email FROM users";

    foreach ($con->query( $sql ) as $result) {
        print_r($result);
    }
    exit;
?>
```

### Retrieve just one line `SQLAnywhereQuery::fetch`

Return first row

```php
<?php
    $sql = "SELECT name, email FROM users";
    $result = $con->exec( $sql );
    $user = $result->fetch();

    print_r($user);
    exit;
?>
```
 
### Data format returns

You can choose how is the format that your data is retrieve `SQLAnywhereClient`

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

Example:

```php
<?php

    $sql = "SELECT name, email FROM users";
    $result = $con->exec( $sql );
    $user = $result->fetch( SQLAnywhereClient::FETCH_OBJECT );

    print_r($user);
    exit;
?>
```

### Return all rows `SQLAnywhereQuery::fetchAll`

Return all selected rows

```php
<?php
    $sql = "SELECT name, email FROM users";
    $result = $con->exec( $sql );
    $user = $result->fetchAll();

    print_r($user);
    exit;
?>
```


In this method you also can choose the format of return too:

```php
<?php

    $sql = "SELECT name, email FROM users";
    $result = $con->exec( $sql );
    $user = $result->fetchAll( SQLAnywhereClient::FETCH_OBJECT );

    print_r($user);
    exit;
?>
```

### Row count `SQLAnywhereQuery::rowCount`

Return the count of rows

```php
<?php
    $sql = "SELECT name, email FROM users";
    $result = $con->exec( $sql );

    echo "We find " . $result->rowCount() . " itens.";
    exit;
?>
```

Or with `count` alias: 

```php
<?php
    $sql = "SELECT name, email FROM user";
    $result = $con->exec( $sql );

    echo  "We find " . $result->count() . " itens.";
    exit;
?>
```

### Field count `SQLAnywhereQuery::fieldCount`

Return the total of fields

```php
<?php
    $sql = "SELECT name, email FROM user";
    $result = $con->exec( $sql );
    
    echo  "We find " . $result->fieldCount() . " fields.";
    exit;
?>
```

### Last ID `SQLAnywhereClient::lastInsertId()` 

Return the last inserted ID

```php
<?php
    $sql = "INSERT INTO user  name, email VALUES ('Carlos', 'contato@carlosgartner.com.br')";
    if ($con->exec( $sql )) {
        echo $con->lastInsertId();
    }
    exit;
?>
```

### Prepared Statement `SQLAnywhereClient::prepare()`:

Prepared Statement with  `?`:

```php
<?php
    $sql = "INSERT INTO users  name, email VALUES (?, ?)";
    $stmnt = $con->prepare( $sql );
    if ($stmnt->execute(array('Carlos', 'contato@carlosgartner.com.br'))) {
         echo $con->lastInsertId();
    }
    exit;
?>
```

And this params names:

```php
<?php
    $sql = "INSERT INTO users  name, email VALUES (:name, :email)";
    $stmnt = $con->prepare( $sql );
    if ($stmnt->execute(array(
        ':name' => 'Carlos', 
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
    $sql = "INSERT INTO users  name, email VALUES (:name, :email)";
    $stmnt = $con->prepare( $sql );

    $name = "Carlos A.";
    $email = "contato@carlosgartner.com.br";

    $stmnt->bindParam(':name', $name);
    $stmnt->bindParam(':email', $email);

    if ($stmnt->execute()) {
         echo $con->lastInsertId();
    }
    exit;
?>
```
