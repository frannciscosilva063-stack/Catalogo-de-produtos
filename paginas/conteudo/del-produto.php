<?php
include('../../config/conexao.php');

if(isset($_GET['idDel'])){
    $id = $_GET['idDel'];

    // Primeiro busca o nome da foto para deletar o arquivo físico
    $select = "SELECT foto_produto FROM tb_produtos WHERE id_produto=:id";
    try {
        $result = $conect->prepare($select);
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        if($result->rowCount() > 0){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $foto = $row['foto_produto'];
            
            // Deleta a foto física se não for a padrão
            if ($foto != 'produto-sem-foto.jpg') {
                $filePath = "../../img/prod/" . $foto; // Ajuste o caminho conforme sua estrutura

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Primeiro deleta os registros relacionados em tb_estoque (devido à integridade referencial)
        $deleteEstoque = "DELETE FROM tb_estoque WHERE id_produto = :id";
        $resultEstoque = $conect->prepare($deleteEstoque);
        $resultEstoque->bindValue(':id', $id, PDO::PARAM_INT);
        $resultEstoque->execute();

        // Agora deleta o registro do produto
        $delete = "DELETE FROM tb_produtos WHERE id_produto = :id";
        try {
            $result = $conect->prepare($delete);
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount() > 0) {
                header("Location: ../produtos.php?msg=produto_deletado");
            } else {
                header("location: ../produtos.php?msg=erro_ao_deletar");
            }
            
        } catch (PDOException $e) {
            echo "<strong>ERRO DE DELETE PRODUTO: </strong>" . $e->getMessage();
        } 
    } catch (PDOException $e) {
        echo "<strong>ERRO DE SELECT: </strong>" . $e->getMessage();
       
    }
} else {
    header("Location: ../home.php");
}