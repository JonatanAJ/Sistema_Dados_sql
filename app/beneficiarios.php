<?php
// Incluir a conexão ao banco de dados
require_once 'conexao_db.php';

// Verificar se o cdPlano está definido na sessão
if (isset($_SESSION['cdPlano'])) {
    $cdPlano = $_SESSION['cdPlano'];
} else {
    die('Plano não fornecido.');
}



// Consultar os beneficiários
$sqlBeneficiarios = "
    SELECT 
        carencia = (
            CASE 
                WHEN (CONVERT(DATE, a.dtCarencia) >= CONVERT(DATE, GETDATE())) THEN 1 
                ELSE 
                    CASE 
                        WHEN g.qtCarencias = 1 THEN 0 
                        ELSE 
                            CASE 
                                WHEN (CONVERT(DATE, a.dtCarencia2) >= CONVERT(DATE, GETDATE())) THEN 2 
                                ELSE 0 
                            END 
                    END 
            END
        ), 
        a.cdPessoaPlano,
        a.cdTipoDependente,
        c.nmTipoDependente,
        a.cdPessoa,
        b.nmPessoa,
        CONVERT(VARCHAR(10), a.dtAdmissao, 103) AS dtAdmissao,
        CONVERT(VARCHAR(10), a.dtCarencia, 103) AS dtCarencia,
        CONVERT(VARCHAR(10), a.dtCarencia2, 103) AS dtCarencia2,
        CONVERT(VARCHAR(10), b.dtNascimento, 103) AS dtNascimento,
        f.nmUsuario AS nmUsuarioFalecimento,
        CONVERT(VARCHAR(10), a.dtCadastro, 103) AS dtCadastro,
        e.nmUsuario,
        d.nmParentesco,
        CONVERT(VARCHAR(10), b.dtFalecimento, 103) AS dtFalecimento,
        sexo = (CASE WHEN b.cdSexo = 0 THEN 'F' ELSE 'M' END),
        idade = FLOOR(DATEDIFF(DAY, b.dtNascimento, ISNULL(b.dtFalecimento, GETDATE())) / 365.25),
        b.cdPlanoFalecimento
    FROM 
        PessoasPlano a
    INNER JOIN 
        Pessoas b ON a.cdPessoa = b.cdPessoa
    INNER JOIN 
        TiposDependente c ON a.cdTipoDependente = c.cdTipoDependente
    INNER JOIN 
        Parentesco d ON a.cdParentesco = d.cdParentesco
    INNER JOIN 
        Usuarios e ON a.cdUsuario = e.cdUsuario
    LEFT JOIN 
        Usuarios f ON a.cdUsuarioFalecimento = f.cdUsuario
    INNER JOIN 
        Configuracoes g ON 1 = 1
    WHERE 
        a.cdStatusBeneficiario = 1 
        AND a.cdPlano = :cdPlano  
    ORDER BY 
        a.cdTipoDependente, b.nmPessoa;
";

$stmtBeneficiarios = $pdo->prepare($sqlBeneficiarios);
$stmtBeneficiarios->bindParam(':cdPlano', $cdPlano, PDO::PARAM_INT);
$stmtBeneficiarios->execute();
$resultadosBeneficiarios = $stmtBeneficiarios->fetchAll(PDO::FETCH_ASSOC);

// Consultar as observações
$sqlObservacoes = "
    SELECT 
        a.cdObservacaoPlano AS cdObservacao, 
        a.tituloObservacao,  
        a.dsObservacao, 
        b.nmUsuario, 
        c.nmTipoObservacao, 
        CONVERT(varchar(10), a.dtCadastro, 103) AS dtCadastro
    FROM 
        ObservacaoPlano a
    INNER JOIN 
        Usuarios b ON a.cdUsuario = b.cdUsuario
    INNER JOIN 
        TiposObservacao c ON a.cdTipoObservacao = c.cdTipoObservacao
    WHERE 
        a.cdPlano = :cdPlanoObservacao
    ORDER BY  
        CONVERT(date, a.dtCadastro, 103) DESC, 
        c.nmTipoObservacao;
";

$stmtObservacoes = $pdo->prepare($sqlObservacoes);
$stmtObservacoes->bindParam(':cdPlanoObservacao', $cdPlano, PDO::PARAM_INT);
$stmtObservacoes->execute();
$resultadosObservacoes = $stmtObservacoes->fetchAll(PDO::FETCH_ASSOC);


?>
