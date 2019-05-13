# WebPKJ

Após muitos anos de trabalho coloco no ar a primeira documentação do framework

[TOC]

## Primeiros passos

### Instalação

A instalação é bem simples na maioria dos casos, basta extrair o zip, renomear a pasta e começara usar.

### Configuração 

Para configurar o webpkj você deve alterar o arquivo na raiz chamado config.php

```php+HTML
<?php
if ($_SERVER['SERVER_NAME'] === 'newbgp.com.br') {
    conf::$dateFormat = 'd/m/Y';
    conf::$local = 'pt_BR';
    conf::$servidor = 'mysql';
    conf::$endereco = 'localhost';
    conf::$usuario = 'newbgp_webpkj';
    conf::$senha = '123';
    conf::$base = 'newbgp_webpkj';
    conf::$session = 'database';
} else {
    conf::$local = 'pt-BR';
    conf::$dateFormat = 'd/m/Y';
    conf::$servidor = 'mysql';
    conf::$endereco = '127.0.0.1';
    conf::$usuario = 'root';
    conf::$senha = '123456';
    conf::$base = 'bgpsistema';
    conf::$session = 'database';
}
//https://toolheap.com/test-mail-server-tool/users-manual.html
conf::$mail_host = 'localhost';
conf::$mail_username = 'felipe@newbgp.com.br';
conf::$mail_from = 'felipe@newbgp.com.br';
conf::$mail_name = 'Felipe';
conf::$mail_password = '';
conf::$mail_secure = '';
conf::$mail_port = 25;
conf::$mail_auth = false;
```

dateFormat = formato usado quando a classe Calendar é convertida para String

local = Usado para formatar os numeros ( o único formato testado é o pt-br)

servidor = Driver será usado pelo PDO 

endereco = Endereço do banco de dados

usuario = Usuário do banco de dados

senha = Senha do banco de dados

base = Nome da base de dados 

session = Tipo de sessão a ser usado ( default ( arquivo ), database, memory ou javascript )

mail_host = endereço do servidor smtp

mail_username = Usuário para ser feito a autenticação

mail_from = Endereço de email informado como origem

mail_name = Nome amigavel do usuário

mail_password = Senha do servidor smtp

mail_secure = Tipo de segurança do servidor smtp

mail_port = porta do servidor smtp

mail_auth = true para caso precise de autenticação



### Problemas comuns

1. No iis você precisa estar com um dominio configurado 
2. No nginx ainda não foi testado 
3. A pasta pkj precisa de permissão de escrita para  o envio de emails asincrono funcionar
4. Falta testes com sql server.


## Primeiro programa

### Usando bind e jquery confirm

```php+HTML
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8">
		<?php
        // importando os plugins de javascript e css
		import('jquery');
		import('pkj');
		import('bind2');
		import('bootstrap');
		import('alerts');
		?>
	</head>
	<body>
		<form class="row">
			<?php
            //geração de formulário
			label_text('Seu nome','nome',10);
			label_button('Mostrar','click="mostrar()" lock',2);
            
            //função do controller
			function mostrar($form){
				alert('Seu nome é '.$form['nome']);
			}
            
			?>
		</form>
	</body>
</html>
```

### Usando banco de dados

### Model básico

### Model avançado

####  funções magicas

####  eventos

## Linha de comando
Embora não seja obrigatório é de grande ajuda a linha de comando do webpkj
Para isto funcionar você irá precisar colocar o PHP no path do sistema.

### ajuda

Este comando exibe um resumo e a sintaxe de todos os comandos possiveis.

```bash
$ php cmd.php ajuda
```

### model

Este comando analisa a tabela e cria um model com base na estrutura.
`! É sempre bom revisar o código que foi gerado.!` 

```bash
$ php cmd.php model tabela
```

### table_info

Este comando mostra a estrutura da tabela

```bash
$ php cmd.php table_info tabela
```

### tables

Este comando mostra todas as tabelas do banco de dados atual.

```bash
$ php cmd.php tables
```

### sql

Este comando executa diretamente um texto no banco de dados.

```bash
$ php cmd.php sql "select datetime('now','localtime') "
```

### top

Este comando executa diretamente um texto no banco de dados.

`! O ultimo parametro ( quantidade de registros ) é opcional, pois tem seu valor padrão como 10 !` 

```bash
$ php cmd.php top usuarios 1
```

### insert

Este comando insere registros no banco via linha de comando perguntando o valor de todos os campos

```bash
$ php cmd.php insert usuarios
```

### install 

Roda os models para fazer a instalação das tabelas e seus registros no banco de dados

```bash
$ php cmd.php install
```

### update 

Atualiza a estrutura das tabelas conforme o model, não altera os dados

```bash
$ php cmd.php update
```



## Banco de dados

### Usando o construtor de consultas

### Usando o model

### Executando sql puro ( raw )






