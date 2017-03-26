<?php

function dbCon(){
    $dsn = "mysql:host=localhost; dbname=tester";
    $opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
    $connect = new PDO($dsn, 'root', '', $opt);
    return $connect;
}

function dbInsert($username, $name, $email, $text, $file=null){
    $pdo = dbCon();
    if($pdo){
        $pdo->beginTransaction();
        try{
            $ins = "INSERT INTO user (username, name, email, text, path) VALUES(:username, :name, :email, :text, :path)";
        $stmn = $pdo->prepare($ins);
        $stmn->bindParam(':username', $username);
        $stmn->bindParam(':name', $name);
        $stmn->bindParam(':email', $email);
        $stmn->bindParam(':text', $text);
        $stmn->bindParam(':path', $file);
        $stmn->execute();
            
        $pdo->commit();
            
            echo"<script>alert('Записано успешно'); window.location.href=window.location.href;</script>";
            
        } catch(Exception $e){
            echo $e->getMessage();
            $pdo->rollBack();
        }
        
    }
}

function selectForValidate($email){
    $pdo = dbCon();
    if(pdo){
        $query = "SELECT email FROM user WHERE email=:email";
        $stmn = $pdo->prepare($query);
        $stmn->bindParam('email', $email);
        $stmn->execute();
        $row=$stmn->fetch(PDO::FETCH_ASSOC);
          if(!empty($row['email'])){
              return false;
          }else{
              return true;
          }
        }
    }


//--------- для выборки в datepiecker
if(isset($_POST['dFrom']) && isset($_POST['dTo'])){
    
    $dFrom = $_POST['dFrom'];
    $dTo = $_POST['dTo'];
    $sort = $_POST['sort'];
    selectByDate($dFrom, $dTo, $sort);
   
}

function selectByDate($dFrom, $dTo, $radio){
    $pdo = dbCon();
    if($pdo){
        if($radio =='toNew'){
            $sort = "ORDER BY date DESC";
        }
        if($radio =='fromNew'){
            $sort = "ORDER BY date ASC";
        }
        $query = "SELECT * FROM user WHERE date BETWEEN :dFrom AND :dTo $sort";
        $stmn = $pdo->prepare($query);
        $stmn->bindParam(':dFrom', $dFrom);
        $stmn->bindParam(':dTo', $dTo);
        $stmn->execute();
        while($row=$stmn->fetch(PDO::FETCH_ASSOC)){
            $str.="<option>$row[username]-$row[name]-$row[email]</option>";
        }
    }
    echo $str;
}

//------- конец выборки в datepiecker

if(isset($_POST['tab']) && $_POST['tab'] == 1){
    selectItemsInTab();
}
function selectItemsInTab(){
     $pdo = dbCon();
    if($pdo){
        $query = "SELECT * FROM user ";
        $stmn = $pdo->query($query);
        $stmn->execute();
        $row=$stmn->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
    
}

?>