<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

    function boardlist(){
        $pdo = pdoSqlConnect();
        $query = "SELECT * FROM board order by number desc;";

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

function writecomment($user,$id)
    {
        $pdo = pdoSqlConnect();
	    $query = "INSERT into comment (number,id,content) values(?,?,?);";
	    $st = $pdo ->prepare($query);
        
        $st->execute([$user->number,$id,$user->content]);
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

