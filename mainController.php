<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

    require 'function.php';
    $res = (Object)Array();
    header('Content-Type: json');
    $req = json_decode(file_get_contents("php://input"));
 $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
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
                $res->result = boardlist();
                $res->code = 500;
                $res->message = "성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);

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
                        $res->data->jwt=getJWToken($Id,$Pw,JWT_SECRET_KEY);
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

		 if((mb_strlen($Lastname,'utf-8')<1))
                {
                    $res->isSuccess=FALSE;
                        $res->code = 104;
                    $res->message="성을 입력하세요";
                }
		 else if((mb_strlen($Firstname,'utf-8')<1))
                {
                    $res->isSuccess=FALSE;
                        $res->code = 105;
                    $res->message="이름을 입력하세요";
                }
		 else if((mb_strlen($Id,'utf-8')<1)||(mb_strlen($Id,'utf-8')>20))	   
                 {   
			$res->isSuccess=FALSE;   
			$res->code = 102;
                        $res->message="아이디는 1~20의 문자열입니다.";                
		 }
		 else  if(idcheck($req)!=false)
                {
                    $res->isSuccess=FALSE;
                        $res->code = 101;
                    $res->message="중복된 아이디입니다";
                }
                 else if((mb_strlen($Pw,'utf-8')<1)||(mb_strlen($Pw,'utf-8')>20))
                 {    
			 $res->isSuccess=FALSE;  
			 $res->code = 103;
                         $res->message="비밀번호는 1~20의 문자열입니다.";                
		 }
		 else if(mb_strlen($Birth,'utf-8')<10)
		 {
			$res->isSuccess=FALSE;
                         $res->code = 106;
                         $res->message="생년월일을 올바르게 입력하세요";

		 }
		  else if(mb_strlen($Gender,'utf-8')>5)
                 {
                        $res->isSuccess=FALSE;
                         $res->code = 107;
                         $res->message="성별을 올바르게 입력하세요";

                 }
                  else
		    {
			signup($req);
                        $res->isSuccess=TRUE;
                        $res->code = 100;
                        $res->message="회원가입 성공";                
                     }
		echo json_encode($res, JSON_NUMERIC_CHECK);
		break;

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

		case"writecomment":
                 http_response_code(400);
                if(isValidHeader($jwt,JWT_SECRET_KEY)!=false)
                    {
                        $number=$req->number;
                        $id=getDataByJWToken($jwt, JWT_SECRET_KEY)->id;
                        $content=$req->content;

                        if(mb_strlen($content,'utf-8')<1)
                        {
                            $res->code = 401;
                            $res->message="내용을 입력하세요";
                        }
                        else
                        {
                         $res->result=writecomment($req,$id);
                        $res->code=400;
                        $res->message="댓글 쓰기 성공";
                    }
                }
                echo json_encode($res, JSON_NUMERIC_CHECK);
		break;
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
