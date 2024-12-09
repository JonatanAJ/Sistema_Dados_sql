<?php

// Inclui a conexão com o banco de dados
require_once 'conexao_db.php';



// Função para obter os dados da pessoa, endereço e plano
function getDadosPessoa($pdo, $cdPessoa) {
    // Verifica se o $pdo e $cdPessoa são válidos
    if (!$pdo || !$cdPessoa) {
        return null; // Retorna null se não houver conexão ou cdPessoa inválido
    }

    // Consulta para obter o cdPlano
    $cdPlano = getCdPlano($pdo, $cdPessoa);
    
    if ($cdPlano === false) {
        return null; // Retorna null se não encontrar o cdPlano
    }

    // Consulta para obter os detalhes da pessoa
    $sqlPessoa = "
        SELECT r.cdPlano, p.*, c.nmCidade, UF.nmUF, S.nmSexo
        FROM Pessoas p
        JOIN Planos r ON p.cdPessoa = r.cdPessoa
        JOIN Cidades c ON p.cdCidade = c.cdCidade
        JOIN Sexo S ON p.cdSexo = S.cdSexo
        JOIN UF UF ON p.cdUF = UF.cdUF
        WHERE r.cdPessoa = :cdPessoa;
    ";
    
    $stmt = $pdo->prepare($sqlPessoa);
    $stmt->bindParam(':cdPessoa', $cdPessoa, PDO::PARAM_INT);
    $stmt->execute();
    
    $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Consulta para obter o endereço da pessoa
    $sqlEndereco = "
        SELECT * 
        FROM vEnderecoPessoa E
        JOIN UF UF ON e.cdUF = UF.cdUF
        WHERE cdPessoa = :cdPessoa;
    ";
    
    $stmt = $pdo->prepare($sqlEndereco);
    $stmt->bindParam(':cdPessoa', $cdPessoa, PDO::PARAM_INT);
    $stmt->execute();
    
    $endereco = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Consulta para obter os dados do plano
    $sqlPlano = "
        SELECT 
            b.nmEmpresa,
            c.nmTipoPlano,
            d.nmTipoVenda,
            l.nmTipoCobranca,
            f.nmPessoa AS nmFuncionario,
            g.nmSetorCobranca,
            h.nmCanalAquisicao,
            i.nmFuneraria,
            j.nmTipoEventoPlano,
            k.nmPeriodoPagamento,
            CONVERT(VARCHAR(10), a.dtAdmissao, 103) AS dtAdmissao,
            CONVERT(VARCHAR(10), a.dtVencimento, 103) AS dtVencimento,
            a.diaVencimento,
            CONVERT(VARCHAR(10), a.dtPrimeiroVencimento, 103) AS dtPrimeiroVencimento,
            CONVERT(VARCHAR(10), a.dtRenovacao, 103) AS dtRenovacao,
            m.nmGrupoManutencao,
            a.vlTipoPlano,
            a.vlAdicionalPlano,
            a.qtPessoasPlano,
            a.vlPlano,
            n.nmCentroCusto,
            p.nmFormaPagamento,
            o.nmFormaEntrega,
            CONVERT(VARCHAR(10), a.dtContratoEntregue, 103) AS dtContratoEntregue,
            a.vlAdesao,
            a.mensagemCliente,
            CONVERT(VARCHAR(10), a.dtAdesao, 103) AS dtAdesao,
            q.tituloboletoConvenio,
            a.contratoEntregue,
            r.nmPessoa AS nmResponsavel,
            a.vlPlanoAntigo
        FROM 
            Planos a
        INNER JOIN 
            Empresas b ON a.cdEmpresa = b.cdEmpresa
        INNER JOIN 
            TiposPlano c ON a.cdTipoPlano = c.cdTipoPlano
        INNER JOIN 
            TiposVenda d ON a.cdTipoVenda = d.cdTipoVenda
        INNER JOIN 
            Funcionarios e ON a.cdFuncionario = e.cdFuncionario
        INNER JOIN 
            Pessoas f ON e.cdPessoa = f.cdPessoa
        INNER JOIN 
            SetorCobranca g ON a.cdSetorCobranca = g.cdSetorCobranca
        INNER JOIN 
            CanalAquisicao h ON a.cdCanalAquisicao = h.cdCanalAquisicao
        INNER JOIN 
            Funerarias i ON a.cdFuneraria = i.cdFuneraria
        INNER JOIN 
            TiposEventoPlano j ON a.cdTipoEventoPlano = j.cdTipoEventoPlano
        INNER JOIN 
            PeriodoPagamento k ON a.cdPeriodoPagamento = k.cdPeriodoPagamento
        INNER JOIN 
            TiposCobranca l ON a.cdTipoCobranca = l.cdTipoCobranca
        INNER JOIN 
            GrupoManutencao m ON a.cdGrupoManutencao = m.cdGrupoManutencao
        INNER JOIN 
            CentroCusto n ON a.cdCentroCusto = n.cdCentroCusto
        INNER JOIN 
            FormasEntrega o ON a.cdFormaEntrega = o.cdFormaEntrega
        INNER JOIN 
            FormasPagamento p ON a.cdFormaPagamento = p.cdFormaPagamento
        LEFT JOIN 
            BoletoConvenio q ON a.cdBoletoConvenio = q.cdBoletoConvenio
        INNER JOIN 
            Pessoas r ON a.cdPessoa = r.cdPessoa
        WHERE 
            r.cdPessoa = :cdPessoa;
    ";
    
    $stmt = $pdo->prepare($sqlPlano);
    $stmt->bindParam(':cdPessoa', $cdPessoa, PDO::PARAM_INT);
    $stmt->execute();
    
    $dadosPlano = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [$pessoa, $endereco, $dadosPlano]; // Retorna como um array
}

// Exemplo de uso
try {
    // Verificar se a conexão PDO foi criada corretamente
    $pdo = createConnection(); // Chama a função de criação da conexão PDO

    if (isset($_SESSION['cdPessoa']) && !empty($_SESSION['cdPessoa'])) {
        $cdPessoa = $_SESSION['cdPessoa']; 
        // Aqui, você pode chamar a função para buscar os dados
        list($pessoa, $endereco, $dadosPlano) = getDadosPessoa($pdo, $cdPessoa);
    } else {
        echo "Código da pessoa não fornecido.";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
?>
