<html> 
<center> 
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" > 
<table border="1" align="center"> 
<?php 

//first there will be script that randomly generate 5 ships for both users 

$step = $_POST['step']; 
//4 steps of the game 
if ($step > 3) { 
    $step = 1; 
} else { 
    $step += 1; 
}//end if 
//step 1, first player fireing 
if ($step == 1) { 
    echo "Player 1"; 
    for ($i = a; $i < k; $i++){ 
     echo "<tr><td width='20' align='right'>$i</td>"; 
       for ($j = 1; $j < 11; $j++){ 
         echo "<td><input type='submit' value='fire' name='$i$j'></td>"; 
       } // end for loop 
     echo "</tr>"; 
    } // end for loop 
    echo "<tr><td></td>"; 
    for ($j = 1; $j < 11; $j++){ 
     echo "<td>$j</td>"; 
    } 
    echo "</tr></table>"; 
    //step 2 result of first player fire 
} else if($step == 2) { 
    echo "Result 1"; 
        for ($i = a; $i < k; $i++){ 
     echo "<tr><td width='20' align='right'>$i</td>"; 
       for ($j = 1; $j < 11; $j++){ 
         echo "<td><input type='checkbox' type='submit' checked='checked' name='$i$j'></td>"; 
       } // end for loop 
     echo "</tr>"; 
    } // end for loop 
    echo "<tr><td></td>"; 
    for ($j = 1; $j < 11; $j++){ 
     echo "<td>$j</td>"; 
    } 
    echo "</tr></table><br><input type='submit' name='' value='Player 2 Turn'>"; 
    //step 3 second player fireing 
} else if($step == 3) { 
    echo "Player 2"; 
    for ($i = a; $i < k; $i++){ 
     echo "<tr><td width='20' align='right'>$i</td>"; 
       for ($j = 1; $j < 11; $j++){ 
         echo "<td><input type='submit' value='fire' name='$i$j'></td>"; 
       } // end for loop 
     echo "</tr>"; 
    } // end for loop 
    echo "<tr><td></td>"; 
    for ($j = 1; $j < 11; $j++){ 
     echo "<td>$j</td>"; 
    } 
    echo "</tr></table>"; 
    //step 4 result of second player fire 
} else { 
    echo "Result 2 "; 
    for ($i = a; $i < k; $i++){ 
     echo "<tr><td width='20' align='right'>$i</td>"; 
       for ($j = 1; $j < 11; $j++){ 
         echo "<td><input type='checkbox' checked='checked' disabled='disabled' name='$i$j'></td>"; 
       } // end for loop 
     echo "</tr>"; 
    } // end for loop 
    echo "<tr><td></td>"; 
    for ($j = 1; $j < 11; $j++){ 
     echo "<td>$j</td>"; 
    } 
    echo "</tr></table><br><input type='submit' name='' value='Player 1 Turn'>"; 
}//end if 
?> 

<input type="hidden" name="step" value="<?php echo $step; ?>" 
</form> 
</center> 
</html>  
