<?php 

    $varejo = [
        'vendas' => [
            'computador' => [
                '1' => [
                'codProduto' => '1',
                'nomeProduto' => 'computador',
                'marca' => 'lenovo',
                'preco' => '2.500',
                ],

                '2' => [
                'codProduto' => '2',
                'nomeProduto' => 'computador',
                'marca' => 'dell',
                'preco' => '3.000',
                ],

                '3' => [
                'codProduto' => '3',
                'nomeProduto' => 'computador',
                'marca' => 'lenovo',
                'preco' => '4.000',
                ],
                '4' => [
                'codProduto' => '4',
                'nomeProduto' => 'computador',
                'marca' => 'positivo',
                'preco' => '3.980',
                ],

                '5' => [
                'codProduto' => '5',
                'nomeProduto' => 'computador',
                'marca' => 'samsung',
                'preco' => '1.000',
                ],

                '6' => [
                'codProduto' => '6',
                'nomeProduto' => 'notebooke',
                'marca' => 'dell',
                'preco' => '3.899',
                ],

                '7' => [
                'codProduto' => '7',
                'nomeProduto' => 'notebooke',
                'marca' => 'lenovo',
                'preco' => '3.990',
                ],

                '8' => [
                'codProduto' => '8',
                'nomeProduto' => 'computador',
                'marca' => 'dell',
                'preco' => '4.500',
                ],

                '9' => [
                'codProduto' => '9',
                'nomeProduto' => 'computador',
                'marca' => 'samsung',
                'preco' => '1.000',
                ],

                '10' => [
                'codProduto' => '10',
                'nomeProduto' => 'notebooke',
                'marca' => 'lenovo',
                'preco' => '4.500',         
                ],
            ],       
        ],      
    ];
    
?>
<?php 
  $total= 0;
?>
<table>
  <tr>
      <th>codProduto</th>
      <th>Nome do produto</th>
      <th>Marca</th>
      <th>R$ </th>
  </tr>  
      <?php 
          foreach ($varejo['vendas']['computador'] as $chaveLinha => $valor) { 
            $total = $total + $valor['preco'];
            ?>
              <tr>
                <td><?=$valor['codProduto']?></td>
                <td><?=$valor['nomeProduto']?></td>
                <td><?=$valor['marca']?></td>
                <td>R$:<?=$valor['preco']?></td>
              </tr>
            <?php
          } 
      ?>
      <th>Total: </th>
      <td><?=$total?></td>
</table>

<hr>
<h3>Somatorio de todos da marca [del]</h3>

<table>
    <tr>
      <th>codProduto</th>
      <th>Nome do produto</th>
      <th>Marca</th>
      <th>R$ </th>
     
    </tr>
    <?php 
        foreach ($varejo['vendas']['computador'] as $chavelinha => $valor) { ?>
              <?php 
                if ($valor['marca'] == 'dell') { 
                  $total = $total + $valor['preco'];
              ?> 

                      <tr>
                        <td><?=$valor['codProduto']?></td>
                        <td><?=$valor['nomeProduto']?></td>
                        <td><?=$valor['marca']?></td>
                        <td>R$:<?=$valor['preco']?></td>                     
                      </tr>
                  <?php
                }
              ?>
          <?php
        }
    ?>
    <th>TOTAL: </th>
    <td>R$:<?=$total?></td>
</table>

<hr>
<h3>Somatorio de todos da marca [lenovo]</h3>

<table>
    <tr>
      <th>codProduto</th>
      <th>Nome do produto</th>
      <th>Marca</th>
      <th>R$ </th>
     
    </tr>
    <?php 
        foreach ($varejo['vendas']['computador'] as $chavelinha => $valor) { ?>
            <?php 
              if ($valor['marca'] == 'lenovo') { 
                $total = $total + $valor['preco'];
                ?> 

                    <tr>
                      <td><?=$valor['codProduto']?></td>
                      <td><?=$valor['nomeProduto']?></td>
                      <td><?=$valor['marca']?></td>
                      <td>R$:<?=$valor['preco']?></td>                     
                    </tr>
                 <?php
              }
            ?>
          <?php
        }
    ?>
    <th>TOTAL: </th>
    <td>R$:<?=$total?></td>
</table>

<hr>
<h3>Somatorio de todos da marca [samsung]</h3>

<table>
    <tr>
      <th>codProduto</th>
      <th>Nome do produto</th>
      <th>Marca</th>
      <th>R$ </th>
     
    </tr>
    <?php 
        foreach ($varejo['vendas']['computador'] as $chavelinha => $valor) { ?>
            <?php 
              if ($valor['marca'] == 'samsung') { 
                $total = $total + $valor['preco'];
                ?> 

                    <tr>
                      <td><?=$valor['codProduto']?></td>
                      <td><?=$valor['nomeProduto']?></td>
                      <td><?=$valor['marca']?></td>
                      <td>R$:<?=$valor['preco']?></td>                     
                    </tr>
                 <?php
              }
            ?>
          <?php
        }
    ?>
    <th>TOTAL: </th>
    <td>R$:<?=$total?></td>
</table>