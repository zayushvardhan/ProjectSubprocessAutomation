<?php
    //include("../others/detailed_fachead.php");
    include("../others/session.php");
    include("../others/proc_data.php");
    //include("../DBA_related/dbfunc.php");
    $i=0;
    session_begin();
    $user = session_resume();
    if(empty($user))
    {
            $base = "http://localhost:13138" ;
            header("Location: $base");
    }

    $co = connect();
    select_db();
    
    $obj = mysql_fetch_array(fetch_fac($user, fac_detail));
    $myname = $obj['name'];
    $post = $obj['post'];
    $my_branch = fetch1(branch, fac_detail, username, $user);
            
?>
<html>
    <head>
        <title>Home</title>
        <link rel="shortcut icon" href="../images/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="../css/dash.css" />
    </head>
    <body>

        <div class="nav">
            <div class="box">
                <div class="img"><img src="../images/upeslogo.png"/></div>
                <div class="id"><p><?php echo $user; ?></p></div>
                <?php if ($post == "ac"){?>
                <div class="id"><p><?php echo $my_branch; ?></p></div>
                <?php } ?>
            </div>
            <hr>
        <nav class="stufront">
            <ul>
            <li><a href="../pages/ac_front.php">Home</a></li>
            <li><a href="../pages/ac_newtitle.php">New Title(s)</a></li>
            <li><a href="../pages/ac_newabstracts.php">New Abstract(s)</a></li>
            <li><a href="../others/ac_msgfac.php">Messages</a></li>
            <li><a href="../others/ac_teamlistfac.php">Team List</a></li>
            <li><a href="../pages/fsubmission.php">Mid/End Evaluation</a></li>
            </ul>
        </nav>
        </div>
        <div class="main">
            <div class="header"><h1>Welcome, <?php echo $myname;?></h1>
            <div class="nav2"><ul>
                <li><a href="../pages/ac_profilefac.php"><img src="../images/user1.png" alt="Profile">Profile</a></li>
                <li><a href="../others/signout.php"><img src="../images/sout.png" alt="Signout">SignOut</a></li>
            </ul></div></div>

                        <div class="status">
        <div class="hbox"><h3>Abstract(s) Under You: </h3></div>
                <div class="sbox">        

<?php
    $query = "select pid from project_detail where ac = '$myname'";
    $result = myquery($query);
 
    $val = array();
    $pid = array();
    while($pd = mysql_fetch_array($result))
    {  
        array_push($pid, $pd['pid']);
    }
    for($i=0; $i<count($pid); $i++){
        $pd = $pid[$i];
        $query = "select * from project_abstract_info where pid = '$pd' and acceptance_ac = 1 and acceptance_mentor = 2";
        $result1 = myquery($query);
        $value1 = mysql_fetch_array($result1);
        if($value1)
        array_push($val, $value1);
    }
    
            if($val){        
                ?>
                    <div class="tbox"><h3>*Click on the Title to view about Project</h3></div>
                    <div class="tbox"><h3>Abstract(s) to Approve: </h3></div>
        
        <table border="1">
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Project Title</th>
                    <th>Approve?</th>
                    <th>Reject?</th>
                </tr>
            </thead>
            <tbody>
                <?php    
                for($i=0;$i<count($val);$i++){
                    ?>
                <tr>
                    <td><?php echo $val[$i]['pid'];?></td>
                    <td><a href="http://localhost:13138/others/displayinfo.php?passkey=<?php echo $val[$i]['pid'];?>"><?php echo $val[$i]['ps'];?></a></td>
                    <td><a href="http://localhost:13138/others/confirmation.php?passkey=2&passkey1=<?php echo $val[$i]['pid'];?>&type=1&ae=ac"><input type="button" value="Approve"></a></td>
                    <td><a href="http://localhost:13138/others/confirmation.php?passkey=1&passkey1=<?php echo $val[$i]['pid'];?>&type=1&ae=ac"><input type="button" value="Reject"></a></td>
                </tr>
            <?php                }?>
            </tbody>
        </table><br>
        <?php
            }else{
                ?> 
            <div class="tbox"><h3>No New Abstracts To Approve</h3></div>
        <?php
            }?>
</div>
</div><br>
</body>
</html>
<?php
    ///include("../others/main_foot.php");
?>