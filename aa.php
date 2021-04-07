[01 inicio]
<?      
	require 'modules/parametros.php';            
	ob_start();      
	$_GET['forceDateUnformmating'] = true;            
	$hasPrint = true;            
	require_once 'modules/veiculos/controllers/veiculos.php';      
	require_once 'modules/estoques/controllers/estoques.php';      
	require_once 'modules/pessoas/controllers/pessoas.php';
	
	if (!$parametros['vendas_qtdDiasMontageCarga']) {      
		$generate->message("danger", "Configure parâmetro: 'Quantidade de dias para montagem de carga após faturamento'");      
		die();      
	}
	
	$funParams = $_GET;      
	if ($_GET['idCargaArmazem'] != '') {            
		/*      
			if (preg_match('/[0-9][Aa][0-9]/', $_GET['idCargaArmazem'])) {                  
				$funParams['idArmazem'] = $idExploded[1];      
			}      
			else {      
				echo "Formato Inválido";      
				die();      
			}      
		*/      
		$idExploded = explode("A", strtoupper($_GET['idCargaArmazem']));      
		$_GET['id'] = $funParams['idCarga'] = $idExploded[0];            
	} elseif ($_GET['idReferencia'] > 0 && $_GET['tpReferencia']== 'Vendas'){      
		$funParams['idVenda'] = $_GET['idReferencia'];      
	} 
	else {      
		$funParams['idCarga'] = $_GET['id'];      
	} 
	
	if ($pages['print']) {	      
		$_GET['forceDateUnformmating'] = true;      
		$Vendas->cargas_carregar($funParams);      
		$cargasFields = $Vendas->fields['Vendas_Cargas'];            
		$_POST['fields']['Vendas_Cargas'] = $cargasFields;      
		$Vendas->cargas_atualizar();      
	}
	
	$_GET['forceDateUnformatting'] = true;                  
	$funParams['statusImpressao'] = '';      
	$Vendas->cargas_carregar($funParams);      
	$cargasFields = $Vendas->fields['Vendas_Cargas'];
	
	if($cargasFields['idPessoaMotorista'] > 0){      
		$pesParams['idPessoa'] = $cargasFields['idPessoaMotorista'];      
		$Pessoas->carregar($pesParams);      
		$pessoaFields = $Pessoas->fields['Pessoas'];      
		$strPessoaMotorista = $pessoaFields['nmPessoa'];      
	} 
	
	if (!is_array($cargasFields)) {      
		echo "Carga inválida";      
		die();      
	}      
	extract($cargasFields);            
	$Veiculos->categorias_carregar($cargasFields);      
	$categoriasFields = $Veiculos->fields['Veiculos_Categorias'];           
	//$funParams['idFilial'] = $cargasFields['idFilial'];      
	$Estoques->armazens_listar($funParams);      
	$armazensArr = $Estoques->fieldsarr['Estoques_Armazens'];            
	if (!is_array($armazensArr)) {      
		$generate->message('warning', 'Não possui nenhum armazem de estoque cadastrado.');      
	}      
?>
[01 fim]

[02 inicio]	
<h1>Mapa de Separação <img src="<?=$config['defaultDOM']; ?>/../mgerencia/imagens/barcode.php?id=<?=$cargasFields['idCarga']; ?>" align="right" width="187" height="20"></h1>      
<h2>      
	<?      
		if (($cargasFields['idReferencia'] > 0) && ($cargasFields['tpReferencia'] == 'Vendas')) {
            echo "Pedido: ".$cargasFields['idReferencia']." - "; 
        }      
	?>      
	Carga Nº <?=number_format($cargasFields['idCarga'], 0, ',', '.'); ?> - Emissao: <?=date("d/m/Y H:i"); ?> - Tipo de Veículo: <?=$categoriasFields['nmCategoria']; ?>
</h2>
[02 fim]

[03 inicio]                        
<?   
    [03.1 inicio]
	if ($tpEntrega == 'DDP') {      
		?>      
			<table  class="table table-bordered" width="100%" style="margin:0px !important; padding:0px !important">      
				<tr>      
					<?      
						if($cargasFields['idPessoaMotorista'] > 0){      
							?>	      
								<td>
									<strong>Motorista: </strong><?=$strPessoaMotorista;?>
								</td>            
							<?	      
						}      
					?>      
				</tr>      
			</table>      
		<?            
		if ($cargasFields['idVeiculo'] > 0) {            
			$fields['idVeiculo'] = $cargasFields['idVeiculo'];      
			require 'modules/veiculos/includes/header.php';            
		} 
		
		if (($cargasFields['idReferencia'] > 0) && ($cargasFields['tpReferencia'] == 'Vendas') && ($cargasFields['tpEntrega'] == 'FOB')) {      
			$idVenda = $funParams['idVenda'] = $cargasFields['idReferencia'];      
			require 'modules/vendas/views/index/ver_cliente.php';      
		}            
		
		if($cargasFields['idPessoaBaixa'] > 0){      
			$pesParams['idPessoa'] = $cargasFields['idPessoaBaixa'];      
			$Pessoas->carregar($pesParams);      
			$pessoaFields = $Pessoas->fields['Pessoas'];      
			$strPessoaBaixa = "Carga baixada por: ".$pessoaFields['nmPessoa'];      
		}     
		else{      
			$strPessoaBaixa = "Carga não Baixada";      
		}                  
	} 
    [03.1 fim]

	if($cargasFields['idPessoaBaixa'] > 0){      
		?>	      
			<table  class="table table-bordered" width="100%">      
				<tr>            
					<td>
						<strong>CARGA BAIXADA POR: </strong><?=$pessoaFields['nmPessoa'];?>
					</td>      
					<td>
						<?=$cargasFields['dtBaixa'];?>
					</td>							            
				</tr>     
			</table>      
		<?	      
	}            
	unset($funParams);      
	$funParams['idCarga'] = $cargasFields['idCarga'];      
	$funParams['agruparProduto'] = true;      
	$funParams['agruparArmazem'] = true;      
	$funParams['isNaoAgruparCarregamento'] = 'Não';      
	$funParams['hasAgruparEmbalagem'] = true;            
	$Vendas->cargas_produtos_listar($funParams);      
	$produtosAgregados = $Vendas->fieldsarr['Vendas_Cargas_Produtos'];      
	/*
		echo "<pre>";      
		print_r($produtosAgregados);      
		echo "</pre>";      
	*/      
	foreach ($produtosAgregados as $n1 => $nv1) {      
		foreach ($nv1 as $n2 => $nv2) {      
			foreach ($nv2 as $n3 => $nv3) {      
				//$produtosAgregados[$n1][$n2][$n3]['qtdTotalProduto'] = $nv3['qtdProduto'];      
			}      
		}      
	}

	$funParams['isNaoAgruparCarregamento'] = 'Sim';      
	$funParams['hasAgruparEmbalagem'] = true;	      
	$Vendas->cargas_produtos_listar($funParams);      
	$produtosNaoAgregados = $Vendas->fieldsarr['Vendas_Cargas_Produtos'];            
	if (is_array($produtosNaoAgregados)) {      
		foreach ($produtosNaoAgregados as $n1 => $nv1) {      
			foreach ($nv1 as $n2 => $nv2) {      
				foreach ($nv2 as $n3 => $nv3) {      
					//$produtosNaoAgregados[$n1][$n2][$n3]['qtdTotalProduto'] = $nv3['qtdProduto'];      
				}      
			}      
		}      
	}
    [03.2 inicio]                                   
	if (is_array($armazensArr)) {      
		foreach ($armazensArr as $idArmazem => $armazem) {            
			if ((is_array($produtosAgregados[$idArmazem])) || (is_array($produtosNaoAgregados[$idArmazem]))) {            
				$idCodeBar = $cargasFields['idCarga'].'A'.$idArmazem;            
				?>      
					<h2>      
						<?      
							require_once "../mtos_novo/objects/phpqrcode/qrlib.php";              
							$PNG_TEMP_DIR = '../temp'.DIRECTORY_SEPARATOR;                              
							$PNG_WEB_DIR = $config['platform'].'/../temp/';                              
							if (!file_exists($PNG_TEMP_DIR))mkdir($PNG_TEMP_DIR);                  
								$filename = $PNG_TEMP_DIR.'_cargas_'.$cargasFields['idCarga'].'A'.$idArmazem.'.png';                  
								$errorCorrectionLevel = 'L';      
								if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))      
									$errorCorrectionLevel = $_REQUEST['level'];                
									$matrixPointSize = 4;      
									if (isset($_REQUEST['size']))      
										$matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);                        
										QRcode::png("baixar:".$cargasFields['idCarga']."A".$idArmazem, $filename, $errorCorrectionLevel, $matrixPointSize, 2);                
						?>      
						<img src="<?=$PNG_WEB_DIR.basename($filename);?>" width="60">                  
					</h2>
                    [03.3 inicio]      
					<?                             
						if ($_SESSION['idPessoa'] == '615123') {      
					        ?> 
                                 
                            <h2>Resumo</h2>      
                            <?      
                                if (is_array($produtosAgregados[$idArmazem])) {            
                                    $produtosArr = $produtosAgregados[$idArmazem];                        
                                    $produto['qtdTotalProdutoConvertidos']  = '';      
                                    foreach ($produtosArr as $embalagens) {      
                                        foreach ($embalagens as $produto) {                                    
                                            $cod = 	$produto['idProduto'] ."-".$produto['Produtos_Embalagens_id'];		                  
                                            //$vrPesoTotalProduto += $produto['qtdTotalProduto'] * $produto['qtdEmbalagemProduto'] *  $produto['vrPesoBruto'];            
                                            if ($produto['hasEtiquetagem'] == 'Sim') {      
                                                $hasEtiqueta = true;            
                                            }            
                                            $produto['qtdTotalProdutoConvertidos'] .= number_format($produto['qtdProduto'] * $produto['qtdEmbalagemProduto'], 2, ',', '.');                  
                                            $produtos[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
                                            $produtos[$cod]['idProduto'] = $produto['idProduto'];      
                                            $produtos[$cod]['nmProduto'] = $produto['nmProduto'];      
                                            $produtos[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
                                            $produtos[$cod]['dsEmbalagemFiscal'] = $produto['dsEmbalagemFiscal'];      
                                            $produtos[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
                                            $produtos[$cod]['qtdProduto'] += $produto['qtdProduto'];      
                                            $produtos[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];      
                                            $produtos[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
                                            $produtos[$cod]['vrPesoBruto'] = $produto['vrPesoBruto'];                        
                                        }      
                                    }      
                                }      
                                if (is_array($produtosNaoAgregados[$idArmazem])) {                  
                                    $produtosArr = $produtosNaoAgregados[$idArmazem];                  
                                    foreach ($produtosArr as $idVenda => $vendas) {                        
                                        foreach ($vendas as $embalagens) {            
                                            foreach ($embalagens as $produto) {                                    
                                                $cod = 	$produto['idProduto'] ."-".$produto['Produtos_Embalagens_id'];		
                                                //------------------TAVA FALTANDO                  
                                                //$vrPesoTotalProduto += $produto['qtdProduto'] * $produto['qtdEmbalagemProduto'] *  $produto['vrPesoBruto'];            
                                                //echo $vrPesoTotalProduto;            
                                                if ($produto['hasEtiquetagem'] == 'Sim') {      
                                                        $hasEtiqueta = true;					      
                                                }            
                                                $produto['qtdTotalProdutoConvertidos'] .= number_format($produto['qtdProduto'] * $produto['qtdEmbalagemProduto'], 2, ',', '.');                  
                                                $produtos[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
                                                $produtos[$cod]['idProduto'] = $produto['idProduto'];      
                                                $produtos[$cod]['nmProduto'] = $produto['nmProduto'];      
                                                $produtos[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
                                                $produtos[$cod]['dsEmbalagemFiscal'] = $produto['dsEmbalagemFiscal'];      
                                                $produtos[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
                                                $produtos[$cod]['qtdProduto'] += $produto['qtdProduto'];      
                                                $produtos[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];      
                                                $produtos[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
                                                //if ($produto['qtdProdutoCarga'] == 0) {            
                                                //	}      
                                            }      
                                        }      
                                    }      
                                }                  
                                if (is_array($produtos)) {      
                                    foreach ($produtos as $index => $prod) {            
                                        //VERIFICAR SE TEM OU NAO CASAS DECIMAIS      
                                        $q = number_format($prod['qtdTotalProduto'] * $prod['qtdEmbalagemProduto'], 2, '.', '');      
                                        $t = explode(".", $q);      
                                        $qtdCasasDecimaisTotalProdutoEmbalagem = 0;
                                        
                                        if ($t[1] > 0) {      
                                            $qtdCasasDecimaisTotalProdutoEmbalagem = 2;      
                                            $produtos[$index]['qtdCasasDecimaisTotalProdutoEmbalagem'] = $qtdCasasDecimaisTotalProdutoEmbalagem;      
                                        }
                                        
                                        $q = number_format($prod['qtdTotalProduto'], 2, '.', '');      
                                        $t = explode(".", $q);      
                                        $qtdCasasDecimaisTotalProduto = 0;
                                        
                                        if ($t[1] > 0) {      
                                            $qtdCasasDecimaisTotalProduto = 2;      
                                            $produtos[$index]['qtdCasasDecimaisTotalProduto'] = $qtdCasasDecimaisTotalProduto;      
                                        }
                                        
                                        if ($prod['dsObservacoes'] != '') {      
                                            $produtos[$index]['nmProduto'] .= '<br><small>'.$prod['dsObservacoes'].'</small>';      
                                        }      
                                    }      
                                }                  
                                if (is_array($produtos)) {     
                                    unset($colunas, $acoes);            
                                    require 'vermapa.formmaker.php';      
                                    $params['checkboxLateral'] = 'Sim';      
                                    $generate->formmaker($params);            
                                    echo "<hr>";      
                                    unset($produtos);      
                                }      
                        }      
				    ?>
					[03.3 fim]
				<?  
                    [03.4 inicio]                
					if (is_array($produtosAgregados[$idArmazem])) {                        
						$produtosArr = $produtosAgregados[$idArmazem];                  
						$produto['qtdTotalProdutoConvertidos']  = '';      
						foreach ($produtosArr as $embalagens) {      
							foreach ($embalagens as $produto) {                                         
								$cod = 	$produto['idProduto'] ."-".$produto['Produtos_Embalagens_id'];		                  
								//$vrPesoTotalProduto += $produto['qtdTotalProduto'] * $produto['qtdEmbalagemProduto'] *  $produto['vrPesoBruto'];            
											
								if ($produto['hasEtiquetagem'] == 'Sim') {      
									$hasEtiqueta = true;            
								}            
											
								$produto['qtdTotalProdutoConvertidos'] .= number_format($produto['qtdProduto'] * $produto['qtdEmbalagemProduto'], 2, ',', '.');                  
								/*
									$produto['qtdTotalProdutoConvertidos'] = number_format($produto['vrProdutoEmbalagem'], 2, ',', '.') . " ".$produto['cdUnidadeVenda'];      
									if ($produto['vrMultiplo'] != 1) {      
										$produto['qtdTotalProdutoConvertidos'] .= " (".number_format(($produto['qtdTotalProduto'] / $produto['vrMultiplo']), 2, ',', '.'). " ".$produto['cdUnidadeDivisor'].")";      
									}
								*/                                                
								$produtos[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
								$produtos[$cod]['idProduto'] = $produto['idProduto'];      
								$produtos[$cod]['nmProduto'] = $produto['nmProduto'];      
								$produtos[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
								$produtos[$cod]['dsEmbalagemFiscal'] = $produto['nmProdutoEmbalagem'];      
								$produtos[$cod]['dsUnidadeEmbalagem'] = $produto['dsUnidadeEmbalagem'];      
								$produtos[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
								$produtos[$cod]['qtdProduto'] += $produto['qtdProduto'];      
								$produtos[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];      
								$produtos[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
								$produtos[$cod]['vrPesoBruto'] = $produto['vrPesoBruto'];                  
								$produtosSeparacao[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
								$produtosSeparacao[$cod]['idProduto'] = $produto['idProduto'];      
								$produtosSeparacao[$cod]['nmProduto'] = $produto['nmProduto'];      
								$produtosSeparacao[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
								$produtosSeparacao[$cod]['dsEmbalagemFiscal'] = $produto['nmProdutoEmbalagem'];      
								$produtosSeparacao[$cod]['dsUnidadeEmbalagem'] = $produto['dsUnidadeEmbalagem'];      
								$produtosSeparacao[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
								$produtosSeparacao[$cod]['qtdProduto'] += $produto['qtdProduto'];      
								$produtosSeparacao[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];      
								$produtosSeparacao[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
								$produtosSeparacao[$cod]['vrPesoBruto'] = $produto['vrPesoBruto'];                                               
							}                  
						}                        
						if (is_array($produtos)) {      
							foreach ($produtos as $index => $prod) {            
								//VERIFICAR SE TEM OU NAO CASAS DECIMAIS      
								$q = number_format($prod['qtdTotalProduto'] * $prod['qtdEmbalagemProduto'], 2, '.', '');      
								$t = explode(".", $q);      $qtdCasasDecimaisTotalProdutoEmbalagem = 0;      
								
								if ($t[1] > 0) {      
									$qtdCasasDecimaisTotalProdutoEmbalagem = 2;      
									$produtos[$index]['qtdCasasDecimaisTotalProdutoEmbalagem'] = $qtdCasasDecimaisTotalProdutoEmbalagem;      
								}                  
								$q = number_format($prod['qtdTotalProduto'], 2, '.', '');      $t = explode(".", $q);     
								$qtdCasasDecimaisTotalProduto = 0;
										
								if ($t[1] > 0) {      
									$qtdCasasDecimaisTotalProduto = 2;      
									$produtos[$index]['qtdCasasDecimaisTotalProduto'] = $qtdCasasDecimaisTotalProduto;      
								}            
										
								$produtos[$index]['qtdProdutoCarga'] += $prod['qtdTotalProduto'];
								
								if ($prod['dsObservacoes'] != '') {      
								$produtos[$index]['nmProduto'] .= '<br><small>'.$prod['dsObservacoes'].'</small>';      
								}      
							}      
						}            
						unset($colunas, $acoes);      
						require 'vermapa.formmaker.php';      
						$params['checkboxLateral'] = 'Sim';      
						$generate->formmaker($params);      
					}// fim do if
                    [03.4 fim]  
                    
                    [03.5 inicio] 
					if (is_array($produtosNaoAgregados[$idArmazem])) {                  
						unset($produtos);            
						$produtosArr = $produtosNaoAgregados[$idArmazem];            
						foreach ($produtosArr as $idVenda => $vendas) {                        
							foreach ($vendas as $embalagens) {            
								foreach ($embalagens as $produto) {                              
									$cod = 	$produto['idProduto'] ."-".$produto['Produtos_Embalagens_id'];		
									//------------------TAVA FALTANDO                  
									//$vrPesoTotalProduto += $produto['qtdProduto'] * $produto['qtdEmbalagemProduto'] *  $produto['vrPesoBruto'];                        
										
									if ($produto['hasEtiquetagem'] == 'Sim') {      
										$hasEtiqueta = true;					      
									}
												
									$produto['qtdTotalProdutoConvertidos'] .= number_format($produto['qtdProduto'] * $produto['qtdEmbalagemProduto'], 2, ',', '.');                  
									$produtos[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
									$produtos[$cod]['idProduto'] = $produto['idProduto'];      
									$produtos[$cod]['nmProduto'] = $produto['nmProduto'];      
									$produtos[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
									$produtos[$cod]['dsEmbalagemFiscal'] = $produto['nmProdutoEmbalagem'];      
									$produtos[$cod]['dsUnidadeEmbalagem'] = $produto['dsUnidadeEmbalagem'];      
									$produtos[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
									$produtos[$cod]['qtdProduto'] += $produto['qtdProduto'];      
									$produtos[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];     
									$produtos[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
									$produtos[$cod]['vrPesoBruto'] = $produto['vrPesoBruto'];                  
									$produtosSeparacao[$cod]['idProdutoCarga'] = $produto['idProdutoCarga'];      
									$produtosSeparacao[$cod]['idProduto'] = $produto['idProduto'];      
									$produtosSeparacao[$cod]['nmProduto'] = $produto['nmProduto'];      
									$produtosSeparacao[$cod]['nmEmbalagem'] = $produto['nmEmbalagem'];      
									$produtosSeparacao[$cod]['dsEmbalagemFiscal'] = $produto['nmProdutoEmbalagem'];      
									$produtosSeparacao[$cod]['dsUnidadeEmbalagem'] = $produto['dsUnidadeEmbalagem'];      
									$produtosSeparacao[$cod]['qtdTotalProduto'] += $produto['qtdTotalProduto'];      
									$produtosSeparacao[$cod]['qtdProduto'] += $produto['qtdProduto'];      
									$produtosSeparacao[$cod]['qtdEmbalagemProduto'] = $produto['qtdEmbalagemProduto'];      
									$produtosSeparacao[$cod]['cdUnidadeVenda'] = $produto['cdUnidadeVenda'];      
									$produtosSeparacao[$cod]['vrPesoBruto'] = $produto['vrPesoBruto'];      
								}      
							}                        
						?>      
						<br>      
                        <div style="page-break-inside:avoid">				      
                            <?            
                                $funParams['idVenda'] = $idVenda;            
                                require 'modules/vendas/views/index/ver_cliente.php';                        
                                if (is_array($produtos)) {      
                                    foreach ($produtos as $index => $prod) {            
                                        //VERIFICAR SE TEM OU NAO CASAS DECIMAIS      
                                        $q = number_format($prod['qtdTotalProduto'] * $prod['qtdEmbalagemProduto'], 2, '.', '');      
                                        $t = explode(".", $q);      
                                        $qtdCasasDecimaisTotalProdutoEmbalagem = 0;      
                                        if ($t[1] > 0) {     
                                            $qtdCasasDecimaisTotalProdutoEmbalagem = 2;      
                                            $produtos[$index]['qtdCasasDecimaisTotalProdutoEmbalagem'] = $qtdCasasDecimaisTotalProdutoEmbalagem;      
                                        }
                                        
                                        $q = number_format($prod['qtdTotalProduto'], 2, '.', '');      
                                        $t = explode(".", $q);      $qtdCasasDecimaisTotalProduto = 0;      
                                                
                                        if ($t[1] > 0) {      
                                            $qtdCasasDecimaisTotalProduto = 2;      
                                            $produtos[$index]['qtdCasasDecimaisTotalProduto'] = $qtdCasasDecimaisTotalProduto;      
                                        }
                                            
                                        if ($prod['dsObservacoes'] != '') {      
                                            $produtos[$index]['nmProduto'] .= '<br><small>'.$prod['dsObservacoes'].'</small>';     
                                        }      
                                    }      
                                }      
                                unset($colunas, $acoes);     
                                require 'vermapa.formmaker.php';      
                                $generate->formmaker($params);            
                                unset($produtos);      
                            ?>            
                        </div>      
						    <?            
						}      
						//print_r($produtos);                  
					}
                    [03.5 fim]                   
					//die();            
				?> 
                           
				<?            
            } //fim do if      
        }// fim do  foreach      
    }// fim do if
    [03.2 fim]                              
    unset($funParams);      
    $funParams['idCarga'] = $cargasFields['idCarga'];      
    $funParams['agruparVendaProduto'] = true;      
    $Vendas->cargas_produtos_listar($funParams);      
    $produtosArr = $Vendas->fieldsarr['Vendas_Cargas_Produtos'];                  
    //--------------------------------------------------ENVIO de SMS----------------------------------------------------------      
    require_once 'modules/pessoas/controllers/pessoas.php';      
    /*      
        if ($cargasFields['isSmsCarregadoEnviado'] != 'Sim') {            
            $funParams['idCarga'] = $cargasFields['idCarga'];      
            $funParams['agruparVendaProduto'] = true;      
            $Vendas->cargas_produtos_listar($funParams);      
            $produtosArr = $Vendas->fieldsarr['Vendas_Cargas_Produtos'];      
            if (count($produtosArr) > 0) {      
                foreach($produtosArr as $idVenda => $produtos) {      
                    $params['idVenda'].= "$idVenda,";      
                }            
                $Vendas->listar($params);      
                $vendasArr = $Vendas->fieldsarr['Vendas']; 
                
                if (is_array($vendasArr)) {      
                    foreach($vendasArr as $idVenda => $vendasFields) {      
                        if($vendasFields['tpEntrega'] == 'DDP'){                  
                            $pesParam['idPessoa'] = $vendasFields['idPessoa'];      
                            $Pessoas->carregar($pesParam);      
                            $pessoasFields = $Pessoas->fields['Pessoas'];      
                            $nrCelular1 = $pessoasFields['nrCelular1'];     
                            $caracteres = array(      " ",      "-",      "(",      ")"      );      
                            $nrCelular1 = str_replace($caracteres, "", $nrCelular1);      
                            //TODO verificar se é regiao metropolitana      
                            $newDate = date("d/m/Y", strtotime($cargasFields['dtCarga']));      
                            $dsMensagem = "ROQUE ACO E CIMENTO informa: Pedido: " . $vendasFields['idVenda'] . " Status: CARREGANDO Previsao Entrega: " . $newDate . " PELA " . strtoupper($cargasFields['tpTurnoCarga']) . " (sujeito a alteracoes)";      
                            $funParams['nrDestinatario'] = $nrCelular1;     
                            $funParams['dsMensagem'] = $dsMensagem;      
                            if($nrCelular1!= ''){      
                                require_once 'modules/sms/controllers/sms.php';      
                                $Sms->enviar($funParams);      
                            }      
                        }      
                    }      
                }            
                $cargasFields['isSmsCarregadoEnviado'] = 'Sim';      
                $_POST['fields']['Vendas_Cargas'] = $cargasFields;      
                $Vendas->cargas_atualizar();      
            }      
        }            
    */      
//-----------------------------------------------------------------------------------------------------------------------------            
[03 fim]
?>
[04 inicio]            
<h3>
    <? 
        echo "Pedidos: ";    
        if(is_array($produtosArr)){      
            foreach ($produtosArr as $idVenda => $produtos) {      
                unset($vendParams);      
                $vendParams['idVenda'] = $idVenda;      
                $Vendas->carregar($vendParams);      
                $vendasFields = $Vendas->fields['Vendas'];                       
                $dias = $fieldsProcessing->calcularDias($fieldsProcessing->converterData($vendasFields['dtFaturamento']), date("d/m/Y"));      
            
                if ($dias > $parametros['vendas_qtdDiasMontageCarga']) {      
                    ob_clean();      
                    $generate->message("danger", "Problemas com o pedido: ".$idVenda. ". Faturado ha mais de ".$parametros['vendas_qtdDiasMontageCarga']." dias. Solicite liberação");      
                    die();      
                }            
                echo $idVenda .", " ;                  
            }      
        }            
        if (is_array($produtos)) {      
            foreach ($produtos as $produto) {      
                //$vrPesoTotalProduto += $produto['qtdProduto'] * $produto['qtdEmbalagemProduto']  * $produto['vrPesoBruto'];      
            }      
        }      
        if (is_array($produtos)) {      
            foreach ($produtosSeparacao as $produto) {      
                $vrPesoTotalProduto += $produto['qtdTotalProduto'] * $produto['qtdEmbalagemProduto']  * $produto['vrPesoBruto'];      
            }      
        }            
        if ($somaPesoVenda < $vrPesoTotalProduto) {							
        }            
    ?> - Peso Total: <?=number_format($vrPesoTotalProduto, 2, ',', '.'); ?> KG
</h3>            
<style>      
    @media print {     
        button {      
            display:none;      
        }      
    }      
</style>                  
<script>      
    function confirmarImpressao() {      
        var url = defaultDOM + "/pure/vendas/cargas/actions/confirmarImpressao";      
        $.post(url, { idCarga: <?=$cargasFields['idCarga']; ?> }, 
        function (data) {     
            window.close();      
        });      
    }      
    <?      
        if ($hasEtiqueta) {      
            ?>      
                navegarAssistente('vendas/cargas/etiquetas/<?=$_GET['id']; ?>&noCss=true&autoPrint=true');      
            <?      
        }      
    ?>            
</script> 
[04.1 inicio]               
<?           
    if ($pages['print']) {      
        ?>      
            <div style="page-break-after:always"></div>      
            <h1>Mapa de Separação - VIA DO SEPARADOR - Carga Nº <?=number_format($cargasFields['idCarga'], 0, ',', '.'); ?> - Emissao: <?=date("d/m/Y"); ?> -  
                <table  class="table table-bordered" width="100%" style="margin:0px !important; padding:0px !important">      
                    <tr>      
                        <?      
                            if($cargasFields['idPessoaMotorista'] > 0){      
                                ?>	      
                                    <td><strong>Motorista: </strong><?=$strPessoaMotorista;?></td>            
                                <?	      
                            }      
                        ?>      
                    </tr>      
                    </table>      
                <?      
                    if (($cargasFields['idReferencia'] > 0) && ($cargasFields['tpReferencia'] == 'Vendas')) {      
                        echo "Pedido: ".$cargasFields['idReferencia']." - ";      
                    }      
	   	?>      
            </h1>            
            <?      
                $produtos = $produtosSeparacao;                  
                if (is_array($produtos)) {      
                    foreach ($produtos as $index => $prod) {                  
                        $q = number_format($prod['qtdTotalProduto'] * $prod['qtdEmbalagemProduto'], 2, '.', '');      
                        $t = explode(".", $q);      
                        $qtdCasasDecimaisTotalProdutoEmbalagem = 0;      
                        if ($t[1] > 0) {      
                            $qtdCasasDecimaisTotalProdutoEmbalagem = 2;      
                            $produtos[$index]['qtdCasasDecimaisTotalProdutoEmbalagem'] = $qtdCasasDecimaisTotalProdutoEmbalagem;      
                        }                  
                        $q = number_format($prod['qtdTotalProduto'], 2, '.', '');      
                        $t = explode(".", $q);      
                        $qtdCasasDecimaisTotalProduto = 0;      
                        if ($t[1] > 0) {      
                            $qtdCasasDecimaisTotalProduto = 2;      
                            $produtos[$index]['qtdCasasDecimaisTotalProduto'] = $qtdCasasDecimaisTotalProduto;      
                        } 
                        
                        if ($prod['dsObservacoes'] != '') {      
                            $produtos[$index]['nmProduto'] .= '<br><small>'.$prod['dsObservacoes'].'</small>';      
                        }      
                    }      
                }      
                unset($colunas, $acoes);      
                require 'vermapa.formmaker.php';      
                $params['checkboxLateral'] = 'Sim';     
                $generate->formmaker($params);      
            ?>            
        
            <br>
            <br>		      
            <span style="font-size:30px">Este documento pertence a carga nº <?=number_format($cargasFields['idCarga'], 0, ',', '.'); ?></span>						
        <?      
    }      
?>
[04.1 fim]               
[04 fim]            
