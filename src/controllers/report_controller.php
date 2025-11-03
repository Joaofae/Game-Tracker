<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php?erro=acesso_negado");
    exit;
}

require_once '../../config/database.php';
require_once '../../lib/fpdf/fpdf.php';


$id_usuario = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

try {
    $sql_jogos = "SELECT * FROM lista_pessoal_jogos WHERE id_usuario = ? ORDER BY status, nome_jogo";
    $stmt_jogos = $pdo->prepare($sql_jogos);
    $stmt_jogos->execute([$id_usuario]);
    $jogos = $stmt_jogos->fetchAll();

    $listas = [
        'quero_comprar' => ['titulo' => 'Quero Comprar', 'jogos' => []],
        'quero_jogar' => ['titulo' => 'Quero Jogar', 'jogos' => []],
        'finalizado' => ['titulo' => 'Finalizado', 'jogos' => []],
        'platinado' => ['titulo' => 'Platinado', 'jogos' => []]
    ];

    foreach ($jogos as $jogo) {
        $listas[$jogo['status']]['jogos'][] = $jogo;
    }

} catch (\PDOException $e) {
    die("Erro ao buscar dados para o relatório: " . $e->getMessage());
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'Minha Lista de Jogos', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Relatorio Pessoal de Jogos', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Usuario: ' . utf8_decode($usuario_nome), 0, 1, 'C');
$pdf->Ln(10);

foreach ($listas as $status => $lista) {
    if (!empty($lista['jogos'])) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 10, utf8_decode($lista['titulo']), 1, 1, 'L', true);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(120, 7, 'Nome do Jogo', 1);
        $pdf->Cell(70, 7, 'Plataforma', 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 10);
        foreach ($lista['jogos'] as $jogo) {
            $pdf->Cell(120, 7, utf8_decode($jogo['nome_jogo']), 1);
            $pdf->Cell(70, 7, utf8_decode($jogo['plataforma']), 1);
            $pdf->Ln();
        }
        $pdf->Ln(5);
    }
}
$pdf->Output('D', 'meu_relatorio_jogos.pdf');
exit;
?>