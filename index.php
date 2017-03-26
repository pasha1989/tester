<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/handler.php';
 $info='';
 $info1 = '';
 $info2 = '';
 $info3 = '';
 
function formValidator(){
  
    if(preg_match('/[a-zA-Z0-9\-\_]{6,20}/', $_POST['username'])==1){
               
            if(preg_match('/[a-zA-Z0-9\-\_]{6,20}/', $_POST['name'])==1){
             
                if(preg_match('/[a-z0-9\_]+@{1}\w{2,20}\.{1}\w{2,4}\.{0,1}\w{0,4}/', $_POST['email'])==1){
                    if(strlen($_POST['textarea'])>19){
                        
                        $user = trim($_POST['username']);
                        $name = trim($_POST['name']);
                        $email = trim($_POST['email']);
                        $textarea = trim(htmlspecialchars($_POST['textarea']));
                        if(!empty($_FILES['file']['name'])){
                          $arr =  explode('.',$_FILES['file']['name']);
                          $array = array_reverse($arr);
                            
                  $upload=move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/upload/".date('Y-m-d_H-i-s').'.'.$array[0]);
                            if($upload){
                                $file="/upload/".date('Y-m-d_H-i-s').'.'.$array[0];
                            }else{
                                $file=null;
                            }     
                        }else{
                            $file=null;
                        }
                       
                  
                        if(selectForValidate($email)){
                            
                            dbInsert($user, $name, $email, $textarea, $file);
                           
                        }else{
                             global $info2;
                            $info2 = 'Such email already exist';
                        }
                    
                       
                    }else{
                        global $info3;
                        $info3 = 'Min length of your text must be 20 symbols';
                    }
                   
                }else{
                    global $info2;
                    $info2 = 'Your email is not correct';
                }
                
            }else{
                global $info1;
                 $info1 = 'Name must bu not less than 6 symbols';
            }
        }else{
        global $info;
         $info = 'User name must bu not less than 6 symbols';
        }
}
    
  
    if(isset($_POST['submit'])){
       
   formValidator();
        
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <!-- <link rel="stylesheet" href="/resources/demos/style.css">-->
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
   <div class="conteiner form">
       <div class="col-md-2">
           <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                  <div class="control-group">
                    <label class="control-label" >User name</label>
                    <div class="controls">
                      <input type="text" id="username" name="username" value="<?php echo $_POST['username'];?>" placeholder="User name"><br>
                      <h4 style="color:red;"><?php echo $info; ?></h4>
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label" >Name</label>
                    <div class="controls">
                      <input type="text" id="name" name="name" value="<?php echo $_POST['name'];?>" placeholder="Name"><br>
                      <h4 style="color:red;"><?php echo $info1; ?></h4>
                    </div>
                  </div>
                  
                  <div class="control-group">
                    <label class="control-label" >Email</label>
                    <div class="controls">
                      <input type="email" id="email" name="email" value="<?php echo $_POST['email'];?>" placeholder="example@gmail.com"><br>
                        <h4 style="color:red;"><?php echo $info2; ?></h4>
                    </div>
                    
                  </div>
                  <div class="control-group">
                    <label class="control-label" >Enter some text</label>
                      <div class="controls">
                       <textarea name="textarea"  class="textarea"><?php echo $_POST['textarea'];?></textarea><br>
                          <h4 style="color:red;"><?php echo $info3; ?></h4>
                      </div>
                      
                  </div>
                  <div class="control-group">
                    <label class="control-label" >Here you can attache a file</label><br>
                      <div class="controls">
                       <input type="file" name="file" class="file"/>
                      </div>
                  </div><br>
                  
                  <div class="control-group">
                      <div class="controls">
                       <input type="submit" name="submit" class="submit"/>
                      </div>
                  </div>
                  
            </form>
       </div>
           <div class="col-md-6">
             <table>
                 <thead>
                     <tr><td>User Name</td><td>Name</td><td>Email</td><td>Date</td></tr>
                 </thead>
                 <tbody>
                     
                 </tbody>
             </table>
           </div>
       <div class="col-md-4"> 
               <h3>Sort your data</h3><hr>
           
                <div class="datepick">Date from: <input type="text" id="dFrom" class="datepicker" size="30"/></div><br>
                 <div class="datepick"> Date to:  <input type="text" id="dTo" class="datepicker" size="32"/></div><br>
                 <button class="button">Сортировать</button><br><hr>
                 <label for="sel">От старых к новым</label>
                 <input type="radio" name="select" id="sel" value="toNew"/>
                 <label for="selBack">От новых к старым</label>
                 <input type="radio" name="select" id="selBack" value="fromNew" checked/><br>
                 <select class="sel">
                     <option>Данных пока нет</option>
                 </select>
                 
        </div>         
   </div>
   
    <script src="bootstrap-3.3.7-dist/js/jquery-3.1.1.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</body>
</html>
<script>
    $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $('.button').on('click', function(){
        var dFrom = $('#dFrom').val();
        var dTo = $('#dTo').val();
        var sort = $('input[type=radio]:checked').val();
        $.ajax({
            url:"http://tester.loc/handler.php",
            type:"POST",
            data:"dFrom="+dFrom+"&dTo="+dTo+"&sort="+sort,
            success:function(field){
                $('.sel').html(field);
                
               
            }
        });
    });
    $.ajax({
        url:"http://tester.loc/handler.php",
        type:"POST",
        data:"tab=1",
        dataType: 'json',
        success:function(str){
            var tab;
            $.each(str, function(key, val){
                tab += "<tr><td>"+val.username+"</td><td>"+val.name+"</td><td>"+val.email+"</td><td>"+val.date.substr(0,10)+"</td></tr>";
            });
            
            
            $('table tbody').html(tab);
        }
    });
</script>