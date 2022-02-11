<?php

class Contas extends Conexao{

    public function setTransaction($tipo, $valor){
            $pdo=parent::get_instace();
            $sql="INSERT INTO historico(id_conta, tipo, valor, data_operacao) VALUES(
                :id_conta, :tipo, :valor, NOW())";
            $sql=$pdo->prepare($sql);
            $sql->bindValue(":id_conta",$_SESSION['login']);
            $sql->bindValue(":tipo",$tipo);
            $sql->bindValue(":valor",$valor);
            $sql->execute();

            if($tipo == 'Deposito'){
                $sql="UPDATE contas SET saldo = saldo + :valor WHERE id=:id";
                $sql=$pdo->prepare($sql);
                $sql->binValue(":id", $_SESSION['login']);
                $sql->execute();
            }
            else{
                $sql="UPDATE contas SET saldo = saldo + :valor WHERE id=:id";
                $sql=$pdo->prepare($sql);
                $sql->binValue(":valor", $valor);
                $sql->binValue(":id", $_SESSION['login']);
                $sql->execute();
            }
    }
    
    public function listAccounts(){
        $pdo = parent::get_instace();
        $sql = "SELECT * FROM contas ORDER BY id ASC";
        $sql = $pdo->prepare($sql);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            return $sql->fetchAll();
        }
    }
    public function listHistoric($id){
        $pdo=parent::get_instace();
        $sql="SELECT * FROM historico WHERE id_conta = :id_conta";
        $sql=$pdo->prepare($sql);
        $sql->bindValue(":id_conta", $id);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            return $sql->fetchAll();
        }
    }
    public function getInfo($id){
        $pdo=parent::get_instace();
        $sql="SELECT * FROM contas WHERE id = :id";
        $sql=$pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            return $sql->fetchAll();
        }
    }

    public function setLogged($agencia, $conta, $senha){

        $pdo=parent::get_instace();
        $sql="SELECT * FROM  contas WHERE agencia =:agencia AND conta = :conta AND senha=:senha";
        $sql=$pdo->prepare($sql);
        $sql->bindValue(":agencia",$agencia);
        $sql->bindValue(":conta",$conta);
        $sql->bindValue(":senha",$senha);
        $sql->execute();

        if($sql->rowCount() > 0 ){
            $sql=$sql->fetch();
            $_SESSION['login']=$sql['id'];

            header("location: ../index.php?login_success");
            exit;
        }else{
            header("Location:../login.php?not_login");
        }
    }

    public function logout(){
        unset($_session['login']);
    }
}

?>