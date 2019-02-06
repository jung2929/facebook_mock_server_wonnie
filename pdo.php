<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
ini_set('default_charset', 'utf8mb4');

    function boardlist(){
        $pdo = pdoSqlConnect();
        $query = "SELECT * FROM timeline order by date desc;";

        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return $res;
}
function playerlist(){
        $pdo = pdoSqlConnect();
        $query = "SELECT * FROM player_profile order by back_number;";

        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return $res;
    }

 function detail_view($number){
	 $pdo = pdoSqlConnect();
	  $query = "SELECT board.number, board.title, board.content as bo_content, board.id as bo_id, board.date as bo_date,comment.number,comment.comment_number,comment.id,comment.content,comment.date from board LEFT OUTER JOIN comment on board.number=comment.number where board.number=?;";
        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute([$number]);
//	$st->execute();   
   	$st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return $res;
 }

     function signup($user,$Fullname){
        $pdo = pdoSqlConnect();
	$query = "insert into user_TB values (?,?,?,?,?,?,?);";

        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute([$user->user_Lastname,$user->user_Firstname,$user->user_Id,$user->user_Pw,$user->user_Birth,$user->user_Gender,$Fullname]);
        $st=null;$pdo = null;

        return;
     }

    function isValidUser($user){
	    $pdo = pdoSqlConnect();
	    $query = "SELECT EXISTS(SELECT * FROM user_TB WHERE user_Id = ? AND user_Pw = ?) as result;";

	    $st = $pdo ->prepare($query);
	    $st->execute([$user->user_Id,$user->user_Pw]);
	    // $st->execute();
	    $st->setFetchMode(PDO::FETCH_ASSOC);
	    $res = $st->fetchAll();

	    $st=null;$pdo = null;

	    return intval($res[0]["result"]);
    }

     function idcheck($user){
	    $pdo = pdoSqlConnect();
	    $query = "SELECT EXISTS(SELECT * FROM user_TB WHERE user_Id = ?) as result;";
	    $st = $pdo ->prepare($query);
	    $st->execute([$user->user_Id]);
	    // $st->execute();
	    $st->setFetchMode(PDO::FETCH_ASSOC);
	    $res = $st->fetchAll();

	    $st=null;$pdo = null;

	    return intval($res[0]["result"]);
     }

function getUserinfo($jwt)
{
    $pdo = pdoSqlConnect();
    $userId=getDataByJWToken($jwt,'JWT_SECRET_KEY')->user_Id;
     $query = "SELECT * from user_TB where user_Id=?;";
       $st = $pdo->prepare($query);
   //    $st->execute([$param,$param]);
       $st->execute([$userId]);
//	$st->execute();   
      $st->setFetchMode(PDO::FETCH_ASSOC);
       $res = $st->fetchAll();

       $st=null;$pdo = null;

       return $res[0]["userFullname"];
}

function timeline($content,$userFullname)
    	{
        $pdo = pdoSqlConnect();   
	    $query = "INSERT into timeline (content,name) values(?,?);";
	    $st = $pdo ->prepare($query);

        $st->execute([$content,$userFullname]);
        $st=null;$pdo = null;

        return;
	}
//	function writeboard($user,$id)
 //   	{
  //      $pdo = pdoSqlConnect();
//	    $query = "INSERT into board (title,content,id) values(?,?,?);";
//	    $st = $pdo ->prepare($query);

//        $st->execute([$user->title,$user->content,$id]);

  //      $st=null;$pdo = null;

    //    return;
//	}

function writecomment($user,$userName)
    {
        $pdo = pdoSqlConnect();
	    $query = "INSERT into comment (number,userFullname,content) values(?,?,?);";
	    $st = $pdo ->prepare($query);
        
        $st->execute([$user->number,$userName,$user->content]);
        $st=null;$pdo = null;

        return;
}

 function deleteboard($number)
    {
        $pdo = pdoSqlConnect();
	    $query = "DELETE from board where number = ? ;";
	    $st = $pdo ->prepare($query);

        $st->execute([$number]);
        $st=null;$pdo = null;

        return;
    }

function authoritycheck($number,$id)
    {
        $pdo = pdoSqlConnect();
	    $query = "SELECT exists(select * from board where number=? and id=?) as result ;";
	    $st = $pdo ->prepare($query);

        $st->execute([$number,$id]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();
        $st=null;$pdo = null;

        return intval($res[0]["result"]);
    }
function friend($myName,$friendName){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO friend_TB (myName,friendName) values(?,?);";

        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute([$myName,$friendName]);

        $st=null;$pdo = null;

        return $res;
}
 function friendlist($myName){
        $pdo = pdoSqlConnect();
        $query = "SELECT * FROM friend_TB where myName=?;";

        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute([$myName]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();
    
        $st=null;$pdo = null;

        return $res;
 }

function isValidUserId($friendName){
	    $pdo = pdoSqlConnect();
	    $query = "SELECT EXISTS(SELECT * FROM user_TB WHERE userFullname = ? ) as result;";

	    $st = $pdo ->prepare($query);
	    $st->execute([$friendName]);
	    // $st->execute();
	    $st->setFetchMode(PDO::FETCH_ASSOC);
	    $res = $st->fetchAll();

	    $st=null;$pdo = null;

	    return intval($res[0]["result"]);
    }

function searchUserName($userName){
        $pdo = pdoSqlConnect();
        $query = "SELECT userFullname FROM user_TB where userFullName=?;";
        
        $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
        $st->execute([$userName]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();
    
        $st=null;$pdo = null;

        return $res;
    }
 function friendcheck($myName,$friendName)
    {
        $pdo = pdoSqlConnect();
	    $query = "SELECT exists(select * from friend_TB where myName=? and friendName=?) as result ;";
	    $st = $pdo ->prepare($query);

        $st->execute([$myName,$friendName]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();
        $st=null;$pdo = null;
        
        return intval($res[0]["result"]);
    }
