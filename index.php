<?php

//ARRAY É UMA TABELA.. LOGO UMA TABELA É UMA ARRAY COM DUAS DIMENSOES
/* 
  No elemento abaixo é exibido uma arrya que possui 3 dimensoes onde estão dividadas em 
  1º Dimenssao { 
  alunos => [],
  professores => [],
  disciplina => [],
  }

  2º Dimessao {
  1 => [],
  5 => [],
  3 => [],
  4 => [],
  *5 => [],
  }

  3º Dimessao {
  'Cod' => 'valor',
  'Nome do aluno' => 'valor',
  'Fone' => 'valor',
  'Idade' => 'valor',
  'Serie' => 'valor'
  }
  se uma tabela é uma array com duas dimensoes onde se encontra a tabela na array abaixo ? 
  a tabela esta dentro do 'alunos' => [ ] logo:
  '1' => [] é representado como uma linha. e o que esta dentro dela 'cod' é a coluna. '1' é o valor que esta na coluna. 
  exemplificando mais ela segue essa analogia. 
            coluna      coluna     coluna        coluna  coluna
              cod | Nome do aluno | Fone      |  Idade |  Serie
  linha: 1 |   1  | Rafael        | 2222-2222 |   12   |  7 ano  <--- valores da tabela
  linha: 2 |   2  | Vanny         | 1212-1212 |   12   |  7 ano  <--- valores da tabela
  ______________________________________________________________

   
*/

  $Escola = [
    'alunos' => [
      '1' => [
        'Cod' => '1',
        'Nome do aluno' => 'Rafael',
        'Fone' => '2222-2222',
        'Idade' => '12',
        'Serie' => '7 ano'
      ],
      '5' => [
        'Cod' => '5',
        'Nome do aluno' => 'Zed ',
        'Fone' => '2323-4488',
        'Idade' => '12',
        'Serie' => '8 ano'
      ],
      '3' => [
        'Cod' => '3',
        'Nome do aluno' => 'Lux',
        'Fone' => '4444-4444',
        'Idade' => '13',
        'Serie' => '7 ano'
      ],
      '4' => [
        'Cod' => '4',
        'Nome do aluno' => 'Ekko',
        'Fone' => '5555-3323',
        'Idade' => '12',
        'Serie' => '8 ano'
      ],
      '2' => [
        'Cod' => '2',
        'Nome do aluno' => 'Vanny',
        'Fone' => '1212-1212',
        'Idade' => '12',
        'Serie' => '7 ano'
      ],

      '5' => [
        'Cod' => '2',
        'Nome do aluno' => 'Tagory',
        'Fone' => '9889-9988',
        'Idade' => '14',
        'Serie' => '8 ano'
      ],

    ],

    'professores' => [
        #codigo.
    ],

    'disciplina' => [
        #codigo.
    ],

  ];

/*
  Existem algumas funções que podem ser aplicadas em uma array como: 
  print_r($Escola); -> ele exibe o que tem em uma array:
  ksort ($Escola);  -> ela ordena array de forma [cresente]   pela {chave}.
  sort($Escola);    -> ela ordena array de forma [decresente] pela {chave}.
  arsort($Escola);  -> ela ordena array de forma [cresent]    pelo (valor).
  rsort($Escola);   -> ela ordena array de forma [decresente] pelo (valor).

================================================================================
*/

?>
  
    <table>
      <tr>
        <th>Cod </th>
        <th>Nome do aluno</th>
        <th>Fone</th>
        <th>Idade</th>
        <th>Serie</th>
      </tr>
        <?php      
          foreach ($Escola['alunos'] as $chaveLinha => $valor ) { ?>
          <tr>      
            <td><?=$valor['Cod']?></td>
            <td><?=$valor['Nome do aluno']?></td>
            <td><?=$valor['Fone']?></td>
            <td><?=$valor['Idade']?></td>
            <td><?=$valor['Serie']?></td>
          </tr>
            <?php 
          } 
        ?>         
    </table>
<!--
  A construção de uma array é muito importante por M formas.
  mas para acessar os valores de uma arra podemos pecorrer ela escrevendo o endereço ate chegar no valor desejado ex:

    $Escola['alunos']['1']['Idade']; essa instrunção imprimirá o valor da idade que será 12.

  podemos fazer operações matenmaticas ou ate mesmo criando condições... digamos que eu queria somar a idade de dois 
  alunos o aluno do cod 1 com o do cod 4 a instrunção seria:
  
  Ex:
    $somaDasIdades = $Escola['alunos']['1']['Idade'] + $Escola['alunos']['4']['Idade'].
  
  o valor da variavel  $somaDasIdades é 24.        
  agora eu quero constuir uma condição onde eu verifico se o somatorio das idades é maior que 20 
  Ex:  
    if ( $somaDasIdades >= 20 ){
        print "o somatorio das idades é maior que 20"
    }

#FOREACH  
  Foreach é a melhor estrutura de repetição para pecorrer uma array foreach significa para cada.
  o foreach é bem usando quando uma array muito eleventos ou muitas chaves, para nao ficar escrevendo 
  usanmos o foreach que pode pecorrer toda array e para exibir os valores é so colocar entre ['valor_do_campo_que_deseja']  
  a sintaxe foreach se escreve:
    
    foreach ($nome_da_Array as $chave_valor => $valor ){

      #codigo

    }
  usando a nossa array como exemplo ficaria: 
  
    foreach ($Escola['alunos']['1'] as $chave_valor => $valor ) {
      echo $valor;
    }

  baseando em nossa array ele vai imprimir na tela os valores da chave 1.
    1
    Rafael
    2222-2222127
    ano
  pra evitar ficar ecrever 

  Digamos que agora eu queria pecorer array Escola do elemento alunos exibindo todas as informações dos alunos 
  fazemos do seguinte geito.
    foreach ($Escola['alunos'] as $chave => $valor) {
      echo 'Codigo: '.$valor['Cod'].'<br>';
      echo 'Nome do aluno: '.$valor['Nome do aluno'].'<br>';
      echo 'Fone: '.$valor['Fone'].'<br>';
      echo 'Idade: '.$valor['Idade'].'<br>';
      echo 'Serie: '.$valor['Serie'].'<br>';
    }

-->

 <?php
/*
  #FUNÇÕES.
    arsort($array): Ordena um array em ordem descrescente mantendo a associação entre índices e valores
    sort($array):   Ordena um array
    ksort($array):  Ordena um array pelas chaves
    rsort($array):  Ordena um array em ordem descrescente

*/
    arsort($Escola['alunos']);
    foreach ( $Escola['alunos'] as $chave => $valor) {
      echo "=================== <br>";
      echo 'Codigo: '.$valor['Cod'].'<br>';
      echo 'Nome do aluno: '.$valor['Nome do aluno'].'<br>';
      echo 'Fone: '.$valor['Fone'].'<br>';
      echo 'Idade: '.$valor['Idade'].'<br>';
      echo 'Serie: '.$valor['Serie'].'<br>';
    }

    
    rsort($Escola['alunos']);
    foreach ($Escola['alunos'] as $chave => $valor) {
      echo "=================== <br>";
      echo 'Codigo: '.$valor['Cod'].'<br>';
      echo 'Nome do aluno: '.$valor['Nome do aluno'].'<br>';
      echo 'Fone: '.$valor['Fone'].'<br>';
      echo 'Idade: '.$valor['Idade'].'<br>';
      echo 'Serie: '.$valor['Serie'].'<br>';
    }
  
    ksort($Escola['alunos']);
    foreach ($Escola['alunos'] as $chave => $valor) {
      echo "=================== <br>";
      echo 'Codigo: '.$valor['Cod'].'<br>';
      echo 'Nome do aluno: '.$valor['Nome do aluno'].'<br>';
      echo 'Fone: '.$valor['Fone'].'<br>';
      echo 'Idade: '.$valor['Idade'].'<br>';
      echo 'Serie: '.$valor['Serie'].'<br>';
    }
    
    sort($Escola['alunos']);
    foreach ($Escola['alunos'] as $chave => $valor) {
      echo "=================== <br>";
      echo 'Codigo: '.$valor['Cod'].'<br>';
      echo 'Nome do aluno: '.$valor['Nome do aluno'].'<br>';
      echo 'Fone: '.$valor['Fone'].'<br>';
      echo 'Idade: '.$valor['Idade'].'<br>';
      echo 'Serie: '.$valor['Serie'].'<br>';
    }

?>