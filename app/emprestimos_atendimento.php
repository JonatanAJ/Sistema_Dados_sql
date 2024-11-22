<?php
// Inclua sua conexão com o banco de dados aqui
require_once 'conexao_db.php';

// Supondo que $cdPlano já esteja definido
// Certifique-se de que $cdPlano tenha um valor válido antes de executar as consultas

try {
    // Consulta de empréstimos
    $sqlEmprestimos = "
    

	SELECT 
    a.cdEmprestimo,
    CASE 
        WHEN a.dtDevolucao IS NOT NULL THEN 4 
        WHEN a.dtCancelamento IS NOT NULL THEN 3 
        WHEN a.dtPrevisao <= CONVERT(date, GETDATE()) THEN 2 
        ELSE 1 
    END AS status,
    CONVERT(varchar(10), a.dtEmprestimo, 103) AS dtEmprestimo,
    CONVERT(varchar(10), a.dtPrevisao, 103) AS dtPrevisao,
    CONVERT(varchar(10), a.dtDevolucao, 103) AS dtDevolucao,
    e.nmProduto,
    a.qtProduto,
    a.vlProduto,
    a.vlTotalProduto,
    f.nmUsuario,
    g.nmUsuario AS nmUsuarioDevolucao,
    k.nmPessoa AS nmBeneficiario,
    d.nmDepositoProduto AS nmDeposito,
    i.nmDepositoProduto AS nmDepositoDevolucao
FROM 
    Emprestimo a
INNER JOIN 
    Pessoas b ON a.cdPessoa = b.cdPessoa
INNER JOIN 
    EstoqueProduto c ON a.cdEstoqueProduto = c.cdEstoqueProduto
INNER JOIN 
    vDepositoCentroCusto d ON c.cdDepositoProduto = d.cdDepositoProduto
INNER JOIN 
    Produtos e ON c.cdProduto = e.cdProduto
	INNER JOIN 
    Usuarios f ON a.cdUsuario = f.cdUsuario
LEFT JOIN 
    Usuarios g ON a.cdUsuarioDevolucao = g.cdUsuario
LEFT JOIN 
    EstoqueProduto h ON a.cdEstoqueProdutoDevolucao = h.cdEstoqueProduto
LEFT JOIN 
    vDepositoCentroCusto i ON h.cdDepositoProduto = i.cdDepositoProduto
INNER JOIN 
    PessoasPlano j ON a.cdPessoaPlano = j.cdPessoaPlano
INNER JOIN 
    Pessoas k ON j.cdPessoa = k.cdPessoa
WHERE 
    a.cdPlano = :cdPlano
ORDER BY 
    a.dtEmprestimo DESC, 
    a.cdEmprestimo DESC;
    ";

    $stmt = $pdo->prepare($sqlEmprestimos);
    $stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);
    $stmt->execute();
    $resultadosEmprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta de atendimentos
    $sqlAtendimento = "
    	SELECT 
    a.cdAtendimentoPlano,
    a.cdPessoaPlano,
    g.nmPessoa AS nmBeneficiario,
    a.cdEspecialidadeConveniado,
    e.nmProcedimento,
    CONVERT(VARCHAR(10), a.dtAtendimento, 103) AS dtAtendimento,
    CONVERT(VARCHAR(10), a.horaAtendimento, 108) AS horaAtendimento,
    a.vlAtendimentoSemDesconto,
    d.nmPessoa AS nmConveniado,
    a.vlDesconto,
    a.vlAtendimentoComDesconto,
    a.cdUsuario,
    a.cdstatusAtendimento 
FROM 
    atendimentoPlanos a 
INNER JOIN 
    EspecialidadesConveniado b ON a.cdEspecialidadeConveniado = b.cdEspecialidadeConveniado 
INNER JOIN 
    Conveniados c ON b.cdConveniados = c.cdConveniados 
INNER JOIN 
    pessoas d ON c.cdpessoa = d.cdPessoa 
INNER JOIN 
    Procedimentos e ON b.cdProcedimento = e.cdProcedimento 
INNER JOIN 
    PessoasPlano f ON a.cdPessoaPlano = f.cdPessoaPlano 
INNER JOIN 
    pessoas g ON f.cdpessoa = g.cdPessoa 
WHERE 
    cdplano = :cdPlano
ORDER BY 
    a.cdAtendimentoPlano DESC;
    ";

    $stmt = $pdo->prepare($sqlAtendimento);
    $stmt->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);
    $stmt->execute();
    $resultadosAtendimento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
