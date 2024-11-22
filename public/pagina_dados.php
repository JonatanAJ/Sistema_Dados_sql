<?php


require_once '../app/conexao_db.php';
include '../app/dados.php';
include '../app/financeiro.php';
include '../app/beneficiarios.php';
include '../app/emprestimos_atendimento.php';


?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Dados</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between">
    <a class="navbar-brand text-white" href="#">HF Solucoes</a>
    <button id="logout" class="btn btn-danger" onclick="window.location.href='../app/logout.php';">Sair</button>
</nav>



    <div class="container">
        <h1 class="text-center mt-4">Formulários</h1>
        <div class="text-center mb-4">
            <div class="button-container">
                <button class="btn btn-primary" onclick="showForm('identificacao')">Identificação</button>
                <button class="btn btn-primary" onclick="showForm('dados')">Dados</button>
                <button class="btn btn-primary" onclick="showForm('beneficiarios')">Beneficiarios</button>
                <button class="btn btn-primary" onclick="showForm('observacoes')">observacoes</button>
                <button class="btn btn-primary" onclick="showForm('emprestimos')">emprestimos</button>
                <button class="btn btn-primary" onclick="showForm('atendimento')">atendimento</button>
                <button class="btn btn-primary" id="mainButton" onclick="toggleSubButtons()">Financeiro</button>
                <div class="sub-buttons">
                    <button class="btn btn-secondary" onclick="showForm('parcelas')">Parcelas</button>
                    <button class="btn btn-secondary" onclick="showForm('acordos')">Acordos</button>
                    <button class="btn btn-secondary" onclick="showForm('adicional')">Adicional</button>
                    <button class="btn btn-secondary" onclick="showForm('servicos')">Servicos</button>
                </div>
            </div>

            <div id="formsContainer">

                <div class="tab-pane active" id="identificacao" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Identificação</h2>
                        <form>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="ID_contrato">Código:</label>

                                    <input type="text" class="form-control" id="ID_contrato" value="0<?php echo htmlspecialchars($pessoa['cdPlano']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="CPF_CNPJ">CPF/CNPJ:</label>
                                    <input type="text" class="form-control" id="CPF_CNPJ" value="<?php echo formatarDocumento(htmlspecialchars($pessoa['cpfPessoa']));  ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Nome">Nome:</label>
                                    <input type="text" class="form-control" id="Nome" value="<?php echo htmlspecialchars($pessoa['nmPessoa']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Sexo">Sexo:</label>
                                    <input type="text" class="form-control" id="Sexo" value="<?php echo htmlspecialchars($pessoa['nmSexo']); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="RG">RG:</label>
                                    <input type="text" class="form-control" id="RG" value="<?php echo formatarDocumento(htmlspecialchars($pessoa['rgPessoa'])) ; ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="UF">UF:</label>
                                    <input type="text" class="form-control" id="UF" value="<?php echo htmlspecialchars($pessoa['nmUF']); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Telefone1">Telefone1:</label>
                                    <input type="text" class="form-control" id="Telefone1" value="<?php echo formatarTelefone( htmlspecialchars($pessoa['fone1Pessoa'])); ?>" disabled>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Telefone2">Telefone2:</label>
                                    <input type="text" class="form-control" id="Telefone2" value="<?php echo formatarTelefone(htmlspecialchars($pessoa['fone2Pessoa'])) ; ?>" disabled>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Telefone3">Telefone3:</label>
                                    <input type="text" class="form-control" id="Telefone3" value="<?php echo formatarTelefone( htmlspecialchars($pessoa['fone3Pessoa'])); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 form-group">
                                    <label for="Email">Email:</label>
                                    <input type="text" class="form-control" id="Email" value="<?php echo htmlspecialchars($pessoa['emailPessoa']); ?>" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Endereço</h2>
                        <form>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="CEP">CEP:</label>
                                    <input type="text" class="form-control" id="CEP" value="<?php echo formatarCEP(htmlspecialchars($endereco['cepLogradouro'])) ; ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="UF">UF:</label>
                                    <input type="text" class="form-control" id="UF" value="<?php echo htmlspecialchars($endereco['nmUF']); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Cidade">Cidade:</label>
                                    <input type="text" class="form-control" id="Cidade" value="<?php echo htmlspecialchars($pessoa['nmCidade']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Bairro">Bairro:</label>
                                    <input type="text" class="form-control" id="Bairro" value="<?php echo htmlspecialchars($endereco['nmBairro']); ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Logradouro">Logradouro:</label>
                                    <input type="text" class="form-control" id="Logradouro" value="<?php echo htmlspecialchars($endereco['nmLogradouro']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Completo">Complemento:</label>
                                    <input type="text" class="form-control" id="Completo" value="<?php echo htmlspecialchars($endereco['compLogradouro']); ?>" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



                <div class="tab-pane" id="dados" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Dados</h2>
                        <form>

                        
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Responsavel">Responsável:</label>
                                    <input type="text" class="form-control" id="Responsavel" value="<?php echo htmlspecialchars($dadosPlano['nmResponsavel']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="empresa">Empresa:</label>
                                    <input type="text" class="form-control" id="Empresa" value="<?php echo htmlspecialchars($dadosPlano['nmEmpresa']); ?>" disabled>
                                </div>
                               
                            </div>


                            <div class="row">

                            <div class="col-md-6 form-group">
                                    <label for="Custo">C. Custo:</label>
                                    <input type="text" class="form-control" id="Custo" value="<?php echo htmlspecialchars($dadosPlano['nmCentroCusto']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="TipoPlano">Tipo de plano:</label>
                                    <input type="text" class="form-control" id="Tipo_Plano" value="<?php echo htmlspecialchars($dadosPlano['nmTipoPlano']); ?>" disabled>
                                </div>
                                
                            </div>


                            <div class="row">
                            <div class="col-md-6 form-group">
                                    <label for="TipoAdmissao">Tipo admissão:</label>
                                    <input type="text" class="form-control" id="Tipo_Admissao" value="<?php echo htmlspecialchars($dadosPlano['nmTipoVenda']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="TipoCobranca">Tipo cobrança:</label>
                                    <input type="text" class="form-control" id="Tipo_Cobranca" value="<?php echo htmlspecialchars($dadosPlano['nmTipoCobranca']); ?>" disabled>
                                </div>
                                
                            </div>


                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="funcionario">Funcionario:</label>
                                    <input type="text" class="form-control" id="Tipo_Cobranca" value="<?php echo htmlspecialchars($dadosPlano['nmFuncionario']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="sCobranca">Setor Cobranca:</label>
                                    <input type="text" class="form-control" id="Canal_Aquisicao" value="<?php echo htmlspecialchars($dadosPlano['nmSetorCobranca']); ?>" disabled>
                                </div>
                            </div>


                            <div class="row">
                            <div class="col-md-6 form-group">
                                    <label for="CanalAquisicao">Canal aquisição:</label>
                                    <input type="text" class="form-control" id="Canal_Aquisicao" value="<?php echo htmlspecialchars($dadosPlano['nmCanalAquisicao']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="EmpresaOrigem">Empresa de origem:</label>
                                    <input type="text" class="form-control" id="Empresa_Origem" value="<?php echo htmlspecialchars($dadosPlano['nmFuneraria']); ?>" disabled>
                                </div>
                               
                            </div>


                            <div class="row">

                            <div class="col-md-6 form-group">
                                    <label for="TipoEvento">Tipo evento:</label>
                                    <input type="text" class="form-control" id="Tipo_Evento" value="<?php echo htmlspecialchars($dadosPlano['nmTipoEventoPlano']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="PeriodoPagamento">Período pagamento:</label>
                                    <input type="text" class="form-control" id="Periodo_Pagamento" value="<?php echo htmlspecialchars($dadosPlano['nmPeriodoPagamento']); ?>" disabled>
                                </div>
                               
                            </div>


                            <div class="row">
                            <div class="col-md-6 form-group">
                                    <label for="FormaPagamento">Forma pagamento:</label>
                                    <input type="text" class="form-control" id="Forma_Pagamento" value="<?php echo htmlspecialchars($dadosPlano['nmFormaPagamento']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="FormaEntrega">Forma entrega:</label>
                                    <input type="text" class="form-control" id="Forma_Entrega" value="<?php echo htmlspecialchars($dadosPlano['nmFormaEntrega']); ?>" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="GrupoManutencao">Grupo de manutenção:</label>
                                    <input type="text" class="form-control" id="Grupo_Manutencao" value="<?php echo htmlspecialchars($dadosPlano['nmGrupoManutencao']); ?>" disabled>
                                </div>
                            </div>

                        </form>

                        <div class="profile-card mt-3">

                        <form>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="admissao">Admissão</label>
            <input type="text" class="form-control" id="CEP" value="<?php echo htmlspecialchars($dadosPlano['dtAdmissao']); ?>" disabled>
        </div>
        <div class="col-md-6 form-group">
            <label for="vencimento">Vencimento:</label>
            <input type="text" class="form-control" id="UF" value="<?php echo htmlspecialchars($dadosPlano['diaVencimento']); ?>" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="1vencimento">Data 1º Vencimento:</label>
            <input type="text" class="form-control" id="Cidade" value="<?php echo htmlspecialchars($dadosPlano['dtPrimeiroVencimento']); ?>" disabled>
        </div>
        <div class="col-md-6 form-group">
            <label for="DTadesao">Data Adesão:</label>
            <input type="text" class="form-control" id="Bairro" value="<?php echo htmlspecialchars($dadosPlano['dtAdesao']); ?>" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="planoAntigo">R$ Plano Antigo:</label>
            <input type="text" class="form-control" id="Logradouro" value="<?php echo htmlspecialchars(number_format($dadosPlano['vlPlanoAntigo'], 2, ',', '.')); ?>" disabled>
        </div>
        <div class="col-md-6 form-group">
            <label for="Vlplano">R$ Plano:</label>
            <input type="text" class="form-control" id="Completo" value="<?php echo htmlspecialchars(number_format($dadosPlano['vlTipoPlano'], 2, ',', '.')); ?>" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="adicionais">R$ Adicionais:</label>
            <input type="text" class="form-control" id="Logradouro" value="<?php echo htmlspecialchars(number_format($dadosPlano['vlAdicionalPlano'], 2, ',', '.')); ?>" disabled>
        </div>
        <div class="col-md-6 form-group">
            <label for="Vlplano">R$ Total Atual:</label>
            <input type="text" class="form-control" id="Completo" value="<?php echo htmlspecialchars(number_format($dadosPlano['vlPlano'], 2, ',', '.')); ?>" disabled>
        </div>
    </div>
</form>

                        </div>
                    </div>
                </div>



                <div class="tab-pane" id="beneficiarios" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Beneficiários</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ST</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Idade</th>
                                        <th scope="col">Sexo</th>
                                        <th scope="col">Admissão</th>
                                        <th scope="col">Carência</th>
                                        <th scope="col">Carência 2</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
foreach ($resultadosBeneficiarios as $beneficiarios) {
    $DadoBeneficiario = $beneficiarios;
    
    // Define a cor baseada no valor de carência
    $carencia = $DadoBeneficiario['carencia']; // Assume que você já tem essa lógica aplicada no SQL
    $cor = ''; // Inicializa a variável de cor
    
    // Verifica se o beneficiário possui uma data de falecimento
    if (!is_null($DadoBeneficiario['dtFalecimento'])) {
        $cor = 'black'; // Cor preta se o beneficiário está falecido
    } else {
        // Caso contrário, aplica a cor conforme o valor de carência
        switch ($carencia) {
            case 0:
                $cor = 'green'; // Sem carência
                break;
            case 1:
                $cor = 'red'; // Com carência
                break;
            case 2:
                $cor = 'black'; // Falecido do plano (já coberto pela verificação anterior)
                break;
            case 3:
                $cor = 'gray'; // Falecido em outro plano
                break;
            case 4:
                $cor = 'blue'; // Com carência finalizada (opcional)
                break;
            default:
                $cor = 'transparent'; // Para valores inesperados
        }
    }
?>
    <tr>
        <td class="st-col" style="background-color: <?php echo $cor; ?>;" value="<?php echo $DadoBeneficiario['carencia']; ?>">
        </td>
        <td><?php echo $DadoBeneficiario['nmTipoDependente']; ?></td>
        <td><?php echo $DadoBeneficiario['nmPessoa']; ?></td>
        <td><?php echo $DadoBeneficiario['idade']; ?></td>
        <td><?php echo $DadoBeneficiario['sexo']; ?></td>
        <td><?php echo $DadoBeneficiario['dtAdmissao']; ?></td>
        <td><?php echo $DadoBeneficiario['dtCarencia']; ?></td>
        <td><?php echo $DadoBeneficiario['dtCarencia2']; ?></td>

    </tr>
<?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>




                <div class="tab-pane" id="observacoes" role="tabpanel">
                    <div class="profile-card">
                        <h2 class="text-center">observacoes</h2>
                       <div class="table-responsive">
                       <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Titulo</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultadosAtendimento as $atendimento) {
                                     
                                    ?>
                                        <tr>
                                           
                                            <td><?php echo $atendimento['nmTipoObservacao'] ?></td>
                                            <td><?php echo $atendimento['tituloObservacao'] ?></td>
                                            <td><?php echo $atendimento['dtCadastro'] ?></td>
                                            <td><?php echo $atendimento['nmUsuario'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                       </div>
                    </div>
                </div>




                <div class="tab-pane" id="emprestimos" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Empréstimos</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ST</th>
                                        <th scope="col">Beneficiário</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">DT. Empréstimo</th>
                                        <th scope="col">DT. Devolução</th>
                                        <th scope="col">R$ Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultadosEmprestimos as $emprestimo) {
                                        // Define a cor com base no status
                                        switch ($emprestimo['status']) {
                                            case 1: // Ativo
                                                $cor = "green";
                                                break;
                                            case 2: // Atrasado
                                                $cor = "red";
                                                break;
                                            case 3: // Cancelado
                                                $cor = "black";
                                                break;
                                            case 4: // Devolvido
                                                $cor = "blue";
                                                break;
                                            default:
                                                $cor = "gray"; // Cor padrão, caso não tenha status conhecido
                                        }
                                    ?>
                                        <tr>
                                            <td class="st-col" style="background-color: <?php echo $cor; ?>;"></td>
                                            <td><?php echo $emprestimo['nmBeneficiario'] ?></td>
                                            <td><?php echo $emprestimo['nmProduto'] ?></td>
                                            <td><?php echo $emprestimo['dtEmprestimo'] ?></td>
                                            <td><?php echo $emprestimo['dtDevolucao'] ?></td>
                                            <td><?php echo number_format($emprestimo['vlProduto'], 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="atendimento" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Atendimento</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ST</th>
                                        <th scope="col">Beneficiário</th>
                                        <th scope="col">Conveniado</th>
                                        <th scope="col">Procedimento</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultadosAtendimento as $atendimento) {
                                        // Define a cor com base no status do atendimento
                                        switch ($atendimento['cdstatusAtendimento']) {
                                            case 1: // Ativo
                                                $cor = "green";
                                                break;
                                            case 2: // Confirmado
                                                $cor = "blue";
                                                break;
                                            case 3: // Cancelado
                                                $cor = "gray";
                                                break;
                                            default:
                                                $cor = "lightgray"; // Cor padrão, caso não tenha status conhecido
                                        }
                                    ?>
                                        <tr>
                                            <td class="st-col" style="background-color: <?php echo $cor; ?>;"></td>
                                            <td><?php echo $atendimento['nmBeneficiario'] ?></td>
                                            <td><?php echo $atendimento['nmConveniado'] ?></td>
                                            <td><?php echo $atendimento['nmProcedimento'] ?></td>
                                            <td><?php echo $atendimento['dtAtendimento'] ?></td>
                                            <td><?php echo $atendimento['horaAtendimento'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>




                <div class="tab-pane" id="parcelas" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Parcelas</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ST</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Competencia</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Desconto</th>
                                        <th scope="col">Acrescimo</th>
                                        <th scope="col">Juros</th>
                                        <th scope="col">Total Pago</th>
                                        <th scope="col">DT. pagamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
foreach ($dadosFinanceiros['parcelas'] as $parcelas) {
    $status = $parcelas['cdStatusMovimentacaoPlanoConta'];
    $dataParcela = DateTime::createFromFormat('d/m/Y', $parcelas['dtParcela']);
    $dataAtual = new DateTime();
    $corClasse = '';

    // Verifica se a parcela está vencida e ainda não foi paga
    if ($dataParcela < $dataAtual && $status == 1) {
        $corClasse = 'status-vencido'; // Vermelho para parcelas vencidas
    } else {
        // Define a cor com base no status
        switch ($status) {
            case 0:
                $corClasse = 'status-cancelado'; // Preto
                break;
            case 1:
                $corClasse = 'status-ativo'; // Azul Ciano
                break;
            case 2:
                $corClasse = 'status-pago'; // Verde
                break;
            case 3:
                $corClasse = 'status-acordo-cancelado'; // Cinza
                break;
            case 4:
                $corClasse = 'status-acordo'; // Cinza
                break;
        }
    }
?>
    <tr>
        <td class="st-col <?php echo $corClasse; ?>"></td>
        <td><?php echo $parcelas['dsMovimentoConta']; ?></td>
        <td><?php echo $parcelas['numCompetencia']; ?></td>
        <td><?php echo $parcelas['dtParcela']; ?></td>
        <td><?php echo number_format($parcelas['vlParcela'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($parcelas['vlDesconto'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($parcelas['vlAcrescimo'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($parcelas['vlJuros'], 2, ',', '.'); ?></td>
        <td><?php echo number_format($parcelas['vlTotal'], 2, ',', '.'); ?></td>
        <td><?php echo $parcelas['dtPagamento']; ?></td>
    </tr>
<?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



                <div class="tab-pane" id="acordos" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Acordos</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Funcionario</th>
                                        <th scope="col">R$ total</th>
                                        <th scope="col">R$ entrada</th>
                                        <th scope="col">DT. entrada</th>
                                        <th scope="col">Parc</th>
                                        <th scope="col">R$ parcela</th>
                                        <th scope="col">1 parcela</th>
                                        <th scope="col">Status</th>


                                    </tr>
                                </thead>
                                <tbody>



                                    <?php

                                    foreach ($dadosFinanceiros['acordos'] as $acordo) {



                                    ?>

                                        <tr>
                                            <td class="st-col <?php echo $corClasse; ?>"> <?php echo $corClasse; ?></td>

                                            <td><?php echo $acordo['cdAcordoPlano']; ?></td>
                                            <td><?php echo $acordo['dtAcordoPlano']; ?></td>
                                            <td><?php echo $acordo['funcionario']; ?></td>
                                            <td><?php echo number_format($acordo['valorTotal'], 2, ',', '.'); ?></td>
                                            <td><?php echo number_format($acordo['entrada'], 2, ',', '.'); ?></td>
                                            <td><?php echo $acordo['dtEntrada']; ?></td>
                                            <td><?php echo $acordo['qtParcelas']; ?></td>
                                            <td><?php echo number_format($acordo['vlParcelas'], 2, ',', '.'); ?></td>
                                            <td><?php echo $acordo['dtPrimeiraParcela']; ?></td>
                                            <td><?php echo $acordo['statusAcordo']; ?></td>

                                        <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              

                <div class="tab-pane" id="adicional" role="tabpanel">
    <div class="profile-card mt-3">
        <h2 class="text-center">Adicional</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ST</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Beneficiário</th>
                        <th scope="col">Início</th>
                      
                        <th scope="col">R$ Valor</th>
                        <th scope="col">Observação</th>
                        <th scope="col">Data Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Supondo que $dadosFinanceiros['adicionais'] contenha os dados da consulta
                foreach ($dadosFinanceiros['adicionais'] as $adicional) {
                    $statusComp = '';

                    // Determinar cor com base no cdStatus
                    switch ($adicional['competencias']) {
                        case 1: // Cancelado
                            $statusComp = 'status-vencido'; // Vermelho
                            break;
                        case 3: // Cobrando
                            $statusComp = 'status-cobrando'; // Verde
                            break;
                        case 2: // A cobrar
                            $statusComp = 'status-ativo'; // Azul
                            break;
                        case 4: // Acordo Finalizado
                            $statusComp= 'status-acordo'; // Amarelo
                            break;
                        default:
                            $statusComp= 'status-desconhecido'; // Classe adicional para outros casos
                            break;
                    }

                    
                ?>
                    <tr>
                        <!-- Célula colorida -->
                        <td class="st-col <?php echo $statusComp; ?>"></td>
                        <td><?php echo $adicional['nmTipoAdicional']; ?></td>
                        <td><?php echo $adicional['nmPessoa']; ?></td>
                        <td><?php echo $adicional['dsCompetencia']; ?></td>
                        <td><?php echo number_format($adicional['vlAdicionalPlano'], 2, ',', '.'); ?></td>
                        <td><?php echo $adicional['obsAdicionalPlano']; ?></td>
                        <td><?php echo $adicional['dtCadastro']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>





                <div class="tab-pane" id="servicos" role="tabpanel">
                    <div class="profile-card mt-3">
                        <h2 class="text-center">Servicos</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ST</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Competencia</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Desconto</th>
                                        <th scope="col">Acrescimo</th>
                                        <th scope="col">Juros</th>
                                        <th scope="col">Total pg</th>
                                        <th scope="col">DT. pg</th>



                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                // Supondo que $dadosFinanceiros['adicionais'] contenha os dados da consulta
                foreach ($dadosFinanceiros['servicos'] as $servico) {
                    $corStatus = '';

                    // Determinar cor com base no cdStatus
                    switch ($servico['nmStatusMovimentacaoPlanoConta']) {
                        case 'cancelado': 
                            $corStatus = 'status-vencido'; // Vermelho
                            break;
                        case 'PAGO': 
                            $corStatus = 'status-pago'; // Verde
                            break;
                        case 'ativo': 
                            $corStatus = 'status-ativo'; // Azul
                            break;
                        case 'acordo': 
                            $corStatus = 'status-acordo'; // Amarelo
                            break;
                        default:
                        case 'Acordo - Cancelado':
                            $corStatus = 'status-acordo-cancelado'; // Classe adicional para outros casos
                            break;
                    }
                

                    
                ?>

                                  

<tr>
<td class="st-col <?php echo $corStatus; ?>"></td>
    <td><?php echo $servico['dsMovimentoConta']; ?></td>
    <td><?php echo $servico['numCompetencia']; ?></td>
    <td><?php echo $servico['dtLancamento']; ?></td>
    <td><?php echo number_format($servico['vlParcela'], 2, ',', '.'); ?></td>
    <td><?php echo number_format($servico['vlDesconto'], 2, ',', '.'); ?></td>
    <td><?php echo number_format($servico['vlAcrescimo'], 2, ',', '.'); ?></td>
    <td><?php echo number_format($servico['vlJuros'], 2, ',', '.'); ?></td>
    <td><?php echo number_format($servico['vlTotal'], 2, ',', '.'); ?></td>
    <td><?php echo $servico['dtPagamento']; ?></td>
</tr>

                                        <?php } ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <script>
            function showForm(formId) {
                const forms = document.querySelectorAll('.tab-pane');
                forms.forEach(form => {
                    form.classList.remove('active');
                });
                document.getElementById(formId).classList.add('active');
            }

            function toggleSubButtons() {
                const mainButton = document.getElementById('mainButton');
                const buttonContainer = mainButton.closest('.button-container');
                buttonContainer.classList.toggle('show-sub-buttons');
            }
            document.addEventListener('DOMContentLoaded', () => {
                showForm('identificacao');
            });
        </script>