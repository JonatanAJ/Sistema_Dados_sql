<?php

// Inclui a conexão com o banco de dados e a função getCdPlano
require_once 'conexao_db.php';

// Função para obter os dados financeiros
function getDadosFinanceiros($pdo, $cdPessoa) {
    // Usa a função getCdPlano já declarada em conexao_db.php
    $cdPlano = getCdPlano($pdo, $cdPessoa);
    
    if (!$cdPlano) {
        return null; // Retorna null se não encontrar o cdPlano
    }

    // Consulta para obter as parcelas
    $sqlParcelas = "
        SELECT 
            a.cdMovimentoPlanoConta AS cdParcela,
            SUBSTRING(f.numCompetencia, 1, 4) AS nmAnoGeracao,
            dbo.fcNumParcela(c.numCompetencia) AS numCompetencia,
            CONVERT(VARCHAR(10), a.dtMovimentoPlanoConta, 103) AS dtParcela,
            CONVERT(VARCHAR(10), a.dtpagamento, 103) AS dtPagamento,
            CONVERT(VARCHAR(10), a.dtcadastro, 103) AS dtLancamento,
            a.vlMovimentoPlanoConta AS vlParcela,
            a.vlDdescontoPlanoConta AS vlDesconto,
            a.vlAcrescimoPlanoConta AS vlAcrescimo,
            c.numCompetencia AS numParcela,
            CASE 
                WHEN a.cdStatusMovimentacaoPlanoConta = 1 THEN 
                    (CASE 
                        WHEN (CONVERT(DATE, a.dtMovimentoPlanoConta) < DATEADD(DAY, -n.diasmulta, CONVERT(DATE, GETDATE()))) THEN 
                            ROUND((a.vlMovimentoPlanoConta * (n.vlMulta / 100)), 2) + 
                            (((n.vlJuros / 100) * a.vlMovimentoPlanoConta / 30) * 
                            DATEDIFF(DAY, CONVERT(DATE, a.dtMovimentoPlanoConta), CONVERT(DATE, GETDATE())))
                        ELSE 0 
                    END) 
                ELSE a.vlJurosPlanoConta 
            END AS vlJuros,
            ROUND(CASE 
                WHEN a.cdStatusMovimentacaoPlanoConta = 1 THEN 
                    (
                        a.vlMovimentoPlanoConta + a.vlAcrescimoPlanoConta - a.vlDdescontoPlanoConta + 
                        CASE 
                            WHEN (CONVERT(DATE, a.dtMovimentoPlanoConta) < DATEADD(DAY, -n.diasmulta, CONVERT(DATE, GETDATE()))) THEN 
                                ROUND((a.vlMovimentoPlanoConta * (n.vlMulta / 100)), 2) + 
                                (((n.vlJuros / 100) * a.vlMovimentoPlanoConta / 30) * 
                                DATEDIFF(DAY, CONVERT(DATE, a.dtMovimentoPlanoConta), CONVERT(DATE, GETDATE())))
                            ELSE 0 
                        END
                    ) 
                ELSE vlTotalPlanoConta 
            END, 2) AS vlTotal,
            d.nmUsuario,
            a.obsMovimentosPlanoConta AS obsParcela,
            a.obsMovimentosPlanoConta AS obsPagamento,
            IIF(a.obsMovimentosPlanoConta LIKE '%.ret', 'Agência Bancária', 
                IIF(dtPagamento IS NULL, ' ', 'Empresa')) AS nmLocalPagamento,
            k.nmPessoa AS nmFuncionario,
            i.nmContaCorrente AS nmCaixa,
            e.nmFormaPagamento,
            b.dsMovimentoConta,
            a.cdStatusMovimentacaoPlanoConta,
            g.nmStatusMovimentacaoPlanoConta,
            h.nmUsuario AS UsuarioGerador,
            m.nmPessoa,
            l.cdPlano AS cdPlano 
        FROM 
            MovimentosPlanoConta a
        INNER JOIN 
            MovimentosConta b ON a.cdMovimentoConta = b.cdMovimentoConta
        INNER JOIN 
            Competencia c ON a.cdCompetencia = c.cdCompetencia
        LEFT JOIN 
            Usuarios d ON a.cdUsuario = d.cdUsuario
        INNER JOIN 
            FormasPagamento e ON a.cdFormaPagamento = e.cdFormaPagamento
        INNER JOIN 
            Competencia f ON b.cdCompetencia = f.cdCompetencia
        INNER JOIN 
            statusMovimentacaoPlanoConta g ON a.cdStatusMovimentacaoPlanoConta = g.cdStatusMovimentacaoPlanoConta
        INNER JOIN 
            Usuarios h ON b.cdusuario = h.cdusuario
        LEFT JOIN 
            contaCorrente i ON a.cdContaCorrente = i.cdContaCorrente
        LEFT JOIN 
            funcionarios j ON a.cdFuncionario = j.cdFuncionario
        LEFT JOIN 
            pessoas k ON j.cdPessoa = k.cdPessoa
        INNER JOIN 
            planos l ON l.cdPlano = b.cdPlano
        INNER JOIN 
            pessoas m ON l.cdPessoa = m.cdPessoa
        INNER JOIN 
            configuracoes n ON (1 = 1)
        WHERE 
            b.cdPlano = :cdPlano AND 
            a.cdStatusMovimentacaoPlanoConta IN (1, 2) 
        ORDER BY 
            CONVERT(DATE, dtMovimentoPlanoConta) DESC, 
            b.dsMovimentoConta;
    ";

    $stmt = $pdo->prepare($sqlParcelas);
    $stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);
    $stmt->execute();
    $parcelas = $stmt->fetchAll(PDO::FETCH_ASSOC);



    
    $sqlAcordo = "
    SELECT 
        a.cdAcordoPlano,
        d.nmPessoa AS funcionario,
        a.vlAcordoPlano AS valorTotal,
        a.vlEntrada AS entrada,
        CONVERT(VARCHAR, a.dtEntrada, 103) AS dtEntrada,
        CONVERT(VARCHAR, a.dtAcordoPlano, 103) AS dtAcordoPlano,
        a.qtParcelas,
        CONVERT(VARCHAR, a.dtPrimeiraParcela, 103) AS dtPrimeiraParcela,
        a.vlParcelas,
        CASE 
            WHEN a.stAcordoPlano = 1 THEN 'Ativo' 
            ELSE 'Inativo' 
        END AS statusAcordo
    FROM 
        AcordoPlano a
    INNER JOIN 
        Pessoas b ON a.cdPessoa = b.cdPessoa
    INNER JOIN 
        Funcionarios c ON a.cdFuncionario = c.cdFuncionario
    INNER JOIN 
        Pessoas d ON c.cdPessoa = d.cdPessoa
    WHERE 
        a.cdplano = :cdPlano  -- Use a variável nomeada para vinculação
    ORDER BY 
        a.cdAcordoPlano DESC;
";

$stmt = $pdo->prepare($sqlAcordo);
$stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);  // Vincula o parâmetro
$stmt->execute();
$acordos = $stmt->fetchAll(PDO::FETCH_ASSOC);






$sqlAdicionais = "
    
    		DECLARE @competenciaAtual INT = (SELECT dbo.fcCompetenciaAtual());

    SELECT 
        a.cdAdicionalPlano,
		a.cdstatus,
        f.nmPessoa,
        g.nmUsuario,
        b.dsCompetencia,
        c.dsCompetencia AS dsCompetenciaFinal,
        h.nmTipoMovimentoFinanceiro,
        d.nmTipoAdicional,
        a.vlAdicionalPlano,
        a.obsAdicionalPlano,
        dtCadastro = CONVERT(VARCHAR(10), a.dtCadastro, 103),
        competencias = CASE 
            WHEN a.cdStatus = 0 THEN 4
            ELSE 
                CASE 
                    WHEN @competenciaAtual < b.cdCompetencia AND a.cdStatus = 1 THEN 2
                    WHEN @competenciaAtual >= b.cdCompetencia 
                         AND @competenciaAtual <= ISNULL(c.cdCompetencia, 9999) 
                         AND a.cdStatus = 1 THEN 1
                    WHEN @competenciaAtual > ISNULL(c.cdCompetencia, 9999) 
                         AND a.cdStatus = 1 THEN 3
                END
        END
    FROM 
        adicionaisplano a
    INNER JOIN 
        Competencia b ON a.cdCompetencia = b.cdCompetencia
    LEFT JOIN 
        Competencia c ON a.cdCompetenciaFinal = c.cdCompetencia
    INNER JOIN 
        tipoAdicional d ON a.cdTipoAdicional = d.cdTipoAdicional
    INNER JOIN 
        PessoasPlano e ON a.cdPessoaPlano = e.cdPessoaPlano
    INNER JOIN 
        Pessoas f ON e.cdPessoa = f.cdPessoa
    INNER JOIN 
        Usuarios g ON a.cdUsuario = g.cdUsuario
    INNER JOIN 
        tipoMovimentoFinanceiro h ON a.cdTipoMovimentoFinanceiro = h.cdTipoMovimentoFinanceiro
    WHERE 
        a.cdPlano = :cdPlano
    ORDER BY 
        competencias ASC, 
        nmPessoa, 
        CONVERT(DATE, a.dtCadastro) DESC;
";

$stmt = $pdo->prepare($sqlAdicionais);
$stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);  // Vincula o parâmetro
$stmt->execute();
$adicionais = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sqlservicos = " 
    SELECT 
        a.cdMovimentoPlanoConta AS cdParcela,
        nmAnoGeracao = (SUBSTRING(f.numCompetencia, 1, 4)),
        dbo.fcNumParcela(c.numCompetencia) AS numCompetencia,
        CONVERT(VARCHAR(10), a.dtMovimentoPlanoConta, 103) AS dtParcela,
        CONVERT(VARCHAR(10), a.dtPagamento, 103) AS dtPagamento,
        dtLancamento = CONVERT(VARCHAR(10), a.dtCadastro, 103),
        a.vlMovimentoPlanoConta AS vlParcela,
        a.vlDdescontoPlanoConta AS vlDesconto,
        a.vlAcrescimoPlanoConta AS vlAcrescimo,
        a.vlJurosPlanoConta AS vlJuros,
        a.vlTotalPlanoConta AS vlTotal,
        d.nmUsuario,
        a.obsMovimentosPlanoConta AS obsParcela,
        a.obsMovimentosPlanoConta AS obsPagamento,
        '' AS nmLocalPagamento,
        NULL AS nmFuncionario,
        e.nmFormaPagamento,
        b.dsMovimentoConta,
        a.cdStatusMovimentacaoPlanoConta,
        g.nmStatusMovimentacaoPlanoConta,
        h.nmUsuario AS UsuarioGerador
    FROM 
        MovimentosPlanoConta a
    INNER JOIN 
        MovimentosConta b ON a.cdMovimentoConta = b.cdMovimentoConta
    INNER JOIN 
        Competencia c ON a.cdCompetencia = c.cdCompetencia
    LEFT JOIN 
        Usuarios d ON a.cdUsuario = d.cdUsuario
    INNER JOIN 
        FormasPagamento e ON a.cdFormaPagamento = e.cdFormaPagamento
    INNER JOIN 
        Competencia f ON b.cdCompetencia = f.cdCompetencia
    INNER JOIN 
        statusMovimentacaoPlanoConta g ON a.cdStatusMovimentacaoPlanoConta = g.cdStatusMovimentacaoPlanoConta
    INNER JOIN 
        Usuarios h ON b.cdUsuario = h.cdUsuario
    LEFT JOIN 
        Vendas i ON b.cdVenda = i.cdVenda
    WHERE 
        i.cdPlano = :cdPlano  -- Usando parâmetro nomeado
        AND b.cdVenda IS NOT NULL
    ORDER BY 
        CONVERT(DATE, dtMovimentoPlanoConta) DESC, 
        b.dsMovimentoConta;
";

$stmt = $pdo->prepare($sqlservicos);
$stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);  // Vincula o parâmetro
$stmt->execute();
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);






    return [
        'parcelas' => $parcelas,
        'acordos' => $acordos,
        'adicionais' => $adicionais,
        'servicos' => $servicos,
        
        // Pode adicionar outras consultas aqui, como acordos, adicionais, etc.
    ];
}


$dadosFinanceiros = getDadosFinanceiros($pdo, $cdPessoa);

