<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('default_charset', 'utf8mb4');

//error_reporting(E_ALL^ E_WARNING); 
//ini_set('error_reporting','E_ALL ^ E_WARNING');
require 'function.php';
$res = (Object)Array();

    header('Content-Type: json');
    $req = json_decode(file_get_contents("php://input"));
// $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
    try {
        addAccessLogs($accessLogs, $req);
        switch ($handler) {
            case "index":
                echo "API Server";
                break;

            case "a":
		    echo "호호호";
		    break;

	    case "detail_view":
            $number =$vars['number'];
	    http_response_code(200);
	      if(!detail_view($number))
                {
                    $res->code=225;
                    $res->message="존재하지 않거나 이미 삭제된 게시글입니다.";

                }
	      else
	      { $res->result = detail_view($number);
            $res->code = 200;
            $res->message = "성공";
	      } echo json_encode($res, JSON_NUMERIC_CHECK);

//        echo phpinfo();
                break;

            case "ACCESS_LOGS":
//            header('content-type text/html charset=utf-8');
                header('Content-Type: text/html; charset=UTF-8');

               getLogs("./logs/access.log");
                break;
            case "ERROR_LOGS":
//            header('content-type text/html charset=utf-8');
                header('Content-Type: text/html; charset=UTF-8');

                getLogs("./logs/errors.log");
                break;
            /*
            * API No. 0
            * API Name : 테스트 API
            * 마지막 수정 날짜 : 18.08.16
            */
            case "boardlist":
		    http_response_code(200);
		 $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
		    if(isValidHeader($jwt,'JWT_SECRET_KEY'))
		    { $res->isSuccess=TRUE;
		$res->code = 120;
		$res->data = boardlist();
                $res->message = "타임라인 조회 성성공";
		echo json_encode($res, JSON_NUMERIC_CHECK);
		return;
		    }
		break;

            case "playerlist":
                http_response_code(200);
                $res->result = playerlist();
                $res->code = 700;
                $res->message = "프로필 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
		break;
		
	    case "login":
                http_response_code(200);    
		
		$Id=$req->user_Id;
		$Pw=$req->user_Pw;
                    if(isValidUser($req)==false)
                    {
                        $res->isSuccess=FALSE;
			$res->code=111;
                        $res->message="올바른 아이디 또는 패스워드를 입력하세요";
                    }
                    else
		    {
			    $res->isSuccess=TRUE;
			    $res->code = 110;
			    $res->data->user_Id=$Id;
                        $res->data->jwt=getJWToken($Id,$Pw,'JWT_SECRET_KEY');
                        $res->message="로그인 성공";
                    
		    }
		echo json_encode($res,JSON_NUMERIC_CHECK);
		break;

	    case"testjwt":
		http_response_code(200);
		$jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
                $res->result = isValidHeader($jwt,JWT_SECRET_KEY);
                $res->code = 600;
                $res->message = "토큰이 유효합니다";
		echo json_encode($res, JSON_NUMERIC_CHECK);
		break;

            case"signup":
                http_response_code(200);
		$Lastname=$req->user_Lastname;
		$Firstname=$req->user_Firstname;
		$Id=$req->user_Id;
		$Pw=$req->user_Pw;
		$Birth=$req->user_Birth;
		$Gender=$req->user_Gender;
		$Fullname=$Lastname.$Firstname;


$check_Id_Email=preg_match("/^[0-9A-Z]([-_\.]?[0-9A-Z])*@[0-9A-Z]([-_\.]?[0-9A-Z])*\.[A-Z]{2,20}$/i", $Id);
$check_Id_Number=preg_match("/^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/",$Id);
		 if((mb_strlen($Lastname,'utf-8')<1))
                {
                    $res->isSuccess=FALSE;
                        $res->code = 104;
		    $res->message="성을 입력하세요";
		     echo json_encode($res, JSON_NUMERIC_CHECK);
return; 
		 }
		if((mb_strlen($Firstname,'utf-8')<1))
                {
                    $res->isSuccess=FALSE;
                        $res->code = 105;
		    $res->message="이름을 입력하세요";
		     echo json_encode($res, JSON_NUMERIC_CHECK);
return;
                }
		if($check_Id_Email!=true&&$check_Id_Number!=true)	
	   	{   
			$res->isSuccess=FALSE;   
			$res->code = 102;
                        $res->message="아이디의 형식을 확인하세요.";                
 echo json_encode($res, JSON_NUMERIC_CHECK);
return;
		 }
		 if(idcheck($req)!=false)
                {
                    $res->isSuccess=FALSE;
                        $res->code = 101;
                    $res->message="중복된 아이디입니다";
 echo json_encode($res, JSON_NUMERIC_CHECK);
return; 
		 }
		if((mb_strlen($Pw,'utf-8')<1)||(mb_strlen($Pw,'utf-8')>20))
                 {    
			 $res->isSuccess=FALSE;  
			 $res->code = 103;
                         $res->message="비밀번호는 1~20의 문자열입니다.";                
 echo json_encode($res, JSON_NUMERIC_CHECK);
return;
		 }
		 if(mb_strlen($Birth,'utf-8')<10)
		 {
			$res->isSuccess=FALSE;
                         $res->code = 106;
                         $res->message="생년월일을 올바르게 입력하세요";
 echo json_encode($res, JSON_NUMERIC_CHECK);
return;
		 }
		 if(mb_strlen($Gender,'utf-8')>5)
                 {
                        $res->isSuccess=FALSE;
                         $res->code = 107;
                         $res->message="성별을 올바르게 입력하세요";
 echo json_encode($res, JSON_NUMERIC_CHECK);
return;
                 }
                  else
		    {
			signup($req,$Fullname);
                        $res->isSuccess=TRUE;
                        $res->code = 100;
                        $res->message="회원가입 성공";                
                     }
		echo json_encode($res, JSON_NUMERIC_CHECK);
		break;
/*
            * API No. 2
            * API Name : 회원가입 API
            * 마지막 수정 날짜 : 19.02.01
            */

case"writeboard":
                 http_response_code(200);  
                if(isValidHeader($jwt,JWT_SECRET_KEY)!=false)
                    {
                        $title=$req->title;
                        $content=$req->content;   
                        $id=getDataByJWToken($jwt, JWT_SECRET_KEY)->id;


		if(mb_strlen($title,'utf-8')<1)
		{
			 $res->code = 201;
                            $res->message="제목을 입력하세요";

		}
		else if(mb_strlen($content,'utf-8')<1)
                        {
                            $res->code = 202;
                            $res->message="내용을 입력하세요";                     
                        }
		else
                        {
                            $res->result=writeboard($req,$id);
                        $res->code=200;
                        $res->message="글 쓰기 성공";
                    }
                }
                echo json_encode($res, JSON_NUMERIC_CHECK);
		break;
	
		case"timeline":
                 http_response_code(200); 
               $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
                    if(isValidHeader($jwt,'JWT_SECRET_KEY'))
		    {
			$content=$req->content;
                        if(mb_strlen($content,'utf-8')<1)
                         {
                            $res->isSuccess=FALSE;
                            $res->code = 132;
                            $res->message="내용을 입력하세요";                     
  echo json_encode($res, JSON_NUMERIC_CHECK);
 
			    return;
                        }
                       else
		       {
			      
                 //      $username=getUserinfo($jwt)[0]['userFullname'];
			$userinfo=getUserinfo($jwt);
		//$username=$userinfo[0]['userFullname'];
		//echo $username;      
			timeline($content,$userinfo);
                       // $userinfo=getUserInfo($jwt);
                       // $username=$userinfo->userFullname;
                       // $username=$userinfo[0]['userFullname'];
                       $res->isSuccess=TRUE;
			$res->code=130;
			$res->writer=$userinfo;
                       $res->message="게시물 작성 성공";
  echo json_encode($res, JSON_NUMERIC_CHECK);

		       return; 
                      }
                    }
//                echo json_encode($res, JSON_NUMERIC_CHECK);
		    break;
		    /*
            * API No. 3
            * API Name : 타임라인 작성  API
            * 마지막 수정 날짜 : 19.02.02
            */

        case"friend":
		http_response_code(200);
		 $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
        if(isValidHeader($jwt,'JWT_SECRET_KEY')!=false)
           {
               $myName=getUserinfo($jwt);
               $friendName=$req->friendName;

               if(mb_strlen($friendName,'utf-8')<1)
               {
                $res->isSuccess=FALSE;   
                $res->code = 141;
                $res->message="친구 이름을 입력하세요 ";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;         
               }
               if(!isValidUserId($friendName))
               {
                $res->isSuccess=FALSE;
                $res->code = 142;
                $res->message="존재하지 않는 사용자입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;   
	       }
	       if(friendcheck($myName,$friendName))
                {
                    $res->isSuccess=FALSE;
                    $res->code=143;
                    $res->message="이미 친구입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
         return;
                }
               else
               {
                $res->isSuccess=TRUE;
                $res->code=140;
                friend($myName,$friendName);
                $res->data->friendName=$friendName;
                $res->message="친구가 되었습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;   
            }
       }
	break;
	/*
            * API No. 4
            * API Name : 친구신청  API
            * 마지막 수정 날짜 : 19.02.04
            */

case"friendlist":
                http_response_code(200);
                $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
                if(isValidHeader($jwt,'JWT_SECRET_KEY'))
                   {
                       $myName=getUserinfo($jwt);
                   //    $friend=friend($myname)[0]["friendName"];         
                        $res->isSuccess=TRUE;
                        $res->code=150;
                        friendlist($myName);
			$res->data->friendName=friendlist($myName)[0]["friendName"];                 
		//	$res->data->friendName=$friend;
                        $res->message="친구리스트 조회 성공";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        return;   
		}
	//	else
	//	{	$res->isSuccess=FALSE;
	//		$res->code=151;
	//		$res->message="친구리스트 조회 실패";
	//		echo json_encode($res, JSON_NUMERIC_CHECK);
	//		return;
	//	}
                        break;
/*
            * API No. 5
            * API Name : 친구리스트 조회  API
            * 마지막 수정 날짜 : 19.02.04
            */

  case"searchUserName":
                        http_response_code(200);
                        $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
                        if(isValidHeader($jwt,'JWT_SECRET_KEY'))
			{
				$userName =$vars['userName'];
                           // $userName=$req->userName;    
                            $res->isSuccess=TRUE;
                                $res->code=160;
                                $res->data->users=searchUserName($userName);
                                $res->message="사용자 조회 성공";
                                echo json_encode($res, JSON_NUMERIC_CHECK);
                                return;   
                            }
			break;
			/*
            * API No. 6
            * API Name : 사용자 조회  API
            * 마지막 수정 날짜 : 19.02.04
            */

            case"file":
                                http_response_code(200); 
                              //  $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
                              //  if(isValidHeader($jwt,'JWT_SECRET_KEY'))
                               //    { 
					   $res->code=170;
					   fileupload();
                                    //$res->data->files=fileupload();
                                    $res->message="파일 업로드 성공";            
                                    echo json_encode($res, JSON_NUMERIC_CHECK);
                                    return; 
                                 //   }
                               break;
               /*
            * API No. 7
            * API Name : 파일 업로드  API
            * 마지막 수정 날짜 : 19.02.06
            */

		case"writecomment":
			http_response_code(400);
			$jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
		       
		if(isValidHeader($jwt,'JWT_SECRET_KEY'))
                    {
                        $number=$req->number;
                        $userName=getUserinfo($jwt);
                        $content=$req->content;

                        if(mb_strlen($content,'utf-8')<1)
			{
		            $res->isSuccess=FALSE;
                            $res->code = 181;
                            $res->message="내용을 입력하세요";
			    echo json_encode($res, JSON_NUMERIC_CHECK);
		     return;	    
			}
                        else
			{
			writecomment($req,$userName);
			$res->isSuccess=TRUE;
                        $res->writer=getUserinfo($jwt);;
                        $res->code=180;
                        $res->message="댓글 쓰기 성공";
			echo json_encode($res, JSON_NUMERIC_CHECK);
		 return;	
			}
                }
		break;

/*
            * API No. 9
            * API Name : 댓글작성  API
            * 마지막 수정 날짜 : 19.02.06
          */
		case"deleteboard":
                http_response_code(200);
               if(isValidHeader($jwt,JWT_SECRET_KEY)!=false)
                   {
                       $number=$vars['number'];
                       $id=getDataByJWToken($jwt, JWT_SECRET_KEY)->id;
	      
	     	 if(!detail_view($number))
                   	 {
                        $res->code=801;
                        $res->message="존재하지 않거나 이미 삭제된 게시글입니다.";

                   	 }	      
	      
	     		  else  if (authoritycheck($number,$id)==false)
                       {

                        $res->code=802;
                        $res->message="접근 권한 없음";

                       }
                   
		    else { $res->result=deleteboard($number);
                       $res->code=800;
                       $res->message="삭제 성공";}

               }
 echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
	}               
    } catch (Exception $e) {
	  
	    return getSQLErrorException($errorLogs, $e, $req);
    }
