<?php
require __DIR__ . '/classes/JwtHandler.php';



class Auth extends JwtHandler
{
    protected $db;
    protected $headers;
    protected $token;

    

    public function __construct($db, $headers)
    {
        parent::__construct();
        $this->db = $db;
        $this->headers = $headers;
    }

////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
//////////////////////////////////////


//FETCH USER DATA 

    public function isValid($token)
    {

       // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

          //  $data = $this->jwtDecodeData($matches[1]);
          
           $data = $this->jwtDecodeData($token);

            if (
                isset($data['data']->user_id) &&
                $user = $this->fetchUser($data['data']->user_id)
            ) :
                return $user;
                
            else :
                return [
                    "success" => 0,
                    "message" => $data['message'],
                ];
            endif;
      /*  } else {
            return [
                "success" => 0,
                "message" => "Token not found in request"
            ];
        }*/
    }



    protected function fetchUser($user_id)
    {
        try {
            $fetch_user_by_id = "SELECT `customer_name`,`customer_emailid`, `customer_address`, `customer_mobileno`, `customer_registerdate`, `customer_pincode`, `id` FROM `cnz_customerregister` WHERE `id`=:id";
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $query_stmt->execute();

            if ($query_stmt->rowCount()) :
                return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return false;
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }



/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////



//FETCH CATEGORY LIST 

    public function FetchCategories()
    {

        // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

        //     $data = $this->jwtDecodeData($matches[1]);

            if (
                // isset($data['data']->user_id) &&
                $user = $this-> get_categories_list()
            ) :
                return [
                    "success" => 200,
                    "user" => $user
                ];
            else :
                return [
                    "success" => 0,
                    // "message" => $data['message'],
                ];
            endif;
        // } else {
        //     return [
        //         "success" => 0,
        //         "message" => "Token not found in request"
        //     ];
        // }
    }






   protected function get_categories_list(){

    try {
        $fetch_get_categories_list = "SELECT `category_id`,`category_name` FROM `cnz_categorymaster`";
        $query_stmt = $this->db->prepare($fetch_get_categories_list);
       // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $query_stmt->execute();
        return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($query_stmt->rowCount()) :
          
        else :
            return false;
        endif;
    } catch (PDOException $e) {
        return null;
    }

   }

   /////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////

   //FETCH SERVICE LIST by category id 


   public function fetchServiceByCategories($category_id)
   {

    //    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //        $data = $this->jwtDecodeData($matches[1]);

           if (
            //    isset($data['data']->user_id) &&
               $user = $this-> get_service_list_by_category($category_id)
           ) :
               return [
                   "success" => 200,
                   "user" => $user
               ];
           else :
               return [
                   "success" => 0,
                //    "message" => $data['message'],
               ];
           endif;
    //    } else {
    //        return [
    //            "success" => 0,
    //            "message" => "Token not found in request"
    //        ];
    //    }
   }






  protected function get_service_list_by_category($category_id){

   try {
       $fetch_get_service_list= "SELECT `category_id`,`id`, `category_servicename` FROM `cnz_categoryservicemaster` WHERE `category_id` = $category_id";
       $query_stmt = $this->db->prepare($fetch_get_service_list);
      // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
       $query_stmt->execute();
       return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
       if ($query_stmt->rowCount()) :
         
       else :
           return false;
       endif;
   } catch (PDOException $e) {
       return null;
   }

  }
   


  /////////////////////////////
  ////////////////

  //INSERT BOOKING SERVICE

  public function Booked($booked_user_id,
  $booked_order_id,
  $booked_user_time,
  $booked_user_date,

  $booked_user_type,
 
  $bookservice_categoryid,
  $bookservice_categoryname,
  $bookservice_qty,
  $bookservice_defaultdescription,
  $bookservice_description,
  $bookservice_location,
  $bookservice_contactperson,
  $bookservice_mobileno,
  $bookservice_areapincode,
  $bookservice_emailid,
  $bookservice_order_type,
  $bookservice_paymentmode
  
  )
   {

    //    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //        $data = $this->jwtDecodeData($matches[1]);

           if (
            //    isset($data['data']->user_id) &&
               $user = $this-> insert_booked_data($booked_user_id,
  $booked_order_id,
  $booked_user_time,
  $booked_user_date,

  $booked_user_type,
  $bookservice_categoryid,
  $bookservice_categoryname,
  $bookservice_qty,
  $bookservice_defaultdescription,
  $bookservice_description,
  $bookservice_location,
  $bookservice_contactperson,
  $bookservice_mobileno,
  $bookservice_areapincode,
  $bookservice_emailid,
  $bookservice_order_type,
  $bookservice_paymentmode
  
  )
           ) :
              
           else :
               return [
                   "success" => 200,
                  "message" => "booking successful",
               ];
           endif;
    //    } else {
    //        return [
    //            "success" => 0,
    //            "message" => "Token not found in request"
    //        ];
    //    }
   }






  protected function insert_booked_data($booked_user_id,
  $booked_order_id,
  $booked_user_time,
  $booked_user_date,

  $booked_user_type,
  $bookservice_categoryid,
  $bookservice_categoryname,
  $bookservice_qty,
  $bookservice_defaultdescription,
  $bookservice_description,
  $bookservice_location,
  $bookservice_contactperson,
  $bookservice_mobileno,
  $bookservice_areapincode,
  $bookservice_emailid,
  $bookservice_order_type,
  $bookservice_paymentmode
  
  ){

   try {

    /*$insert_book_details = "INSERT INTO 
    `cnz_bookservice` 
    (`booked_user_type`, 
    `booked_user_id`, 
    `bookservice_categoryid`, 
    `bookservice_categoryname`, 
    `bookservice_qty`, 
    `bookservice_defaultdescription`, 
    `bookservice_description`, 
    `bookservice_location`, 
    `bookservice_contactperson`, 
    `bookservice_mobileno`, 
    `bookservice_areapincode`, 
    `bookservice_paymentmode`) 
    VALUES 
    (
    $booked_user_type, 
    $booked_user_id, 
    $bookservice_categoryid, 
    $bookservice_categoryname,
    $bookservice_qty,
    $bookservice_defaultdescription, 
    $bookservice_description,   
    $bookservice_location, 
    $bookservice_contactperson, 
    $bookservice_mobileno , 
    $bookservice_areapincode, 
    $bookservice_paymentmode)";*/
    
/*   $insert_book_details = "INSERT INTO `cnz_bookservice` (`id`, `book_status`, `booked_user_type`, `booked_user_id`, `bookservice_date`, `bookservice_categoryid`, `bookservice_categoryname`, `bookservice_qty`, `bookservice_defaultdescription`, `bookservice_description`, `bookservice_location`, `bookservice_contactperson`, `bookservice_mobileno`, `bookservice_areapincode`, `bookservice_paymentmode`, `status`, `created_by`, `updated_by`) VALUES (NULL, '', '$booked_user_type', '$booked_user_id', '$bookservice_categoryid', CURRENT_DATE() ,'$bookservice_categoryname', '$bookservice_qty', '$bookservice_defaultdescription', '$bookservice_description', '$bookservice_location', '$bookservice_contactperson', '$bookservice_mobileno', '$bookservice_areapincode', '$bookservice_paymentmode', NULL, NULL, NULL)";*/


$insert_book_details = "INSERT INTO `cnz_bookservice` (`id`,`booked_order_id`,`booked_user_time`,`booked_user_date`, `book_status`, `booked_user_type`, `booked_user_id`, `bookservice_date`, `bookservice_categoryid`, `bookservice_categoryname`, `bookservice_qty`, `bookservice_defaultdescription`, `bookservice_description`, `bookservice_location`, `bookservice_contactperson`, `bookservice_mobileno`, `bookservice_areapincode`, `bookservice_emailid` ,`bookservice_order_type`, `bookservice_paymentmode`, `status`, `created_by`, `updated_by`) VALUES (NULL,'$booked_order_id','$booked_user_time','$booked_user_date', 'process', '$booked_user_type', '$booked_user_id', CURRENT_DATE(), '$bookservice_categoryid', '$bookservice_categoryname', '$bookservice_qty', '$bookservice_defaultdescription', '$bookservice_description', '$bookservice_location', '$bookservice_contactperson', '$bookservice_mobileno', '$bookservice_areapincode', '$bookservice_emailid','$bookservice_order_type', '$bookservice_paymentmode', NULL, NULL, NULL)";

       $query_stmt = $this->db->prepare($insert_book_details);
      // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
       $query_stmt->execute();

       return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
      // if ($query_stmt->rowCount()) :
         
       //else :
       //  return false;
       //endif;
   } catch (PDOException $e) {
       return null;
   }

  }
   


///////////////////////////////////////
//////////////////////
// UPDATE PROFILE 


  public function update_user_profile($customer_name, $customer_emailid, $customer_mobileno, $customer_address, $id, $customer_pincode)
  {

   //    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

   //        $data = $this->jwtDecodeData($matches[1]);

          if (
           //    isset($data['data']->user_id) &&
              $user = $this-> updateProfile($customer_name, $customer_emailid, $customer_mobileno, $customer_address, $id, $customer_pincode)
          ) :
              return [
                  "success" => 200,
                  "user" => $user
              ];
          else :
              return [
                  "success" => 200,
                 "message" => "Profile updated",
              ];
          endif;
   //    } else {
   //        return [
   //            "success" => 0,
   //            "message" => "Token not found in request"
   //        ];
   //    }
  }





 protected function updateProfile($customer_name, $customer_emailid, $customer_mobileno, $customer_address, $id, $customer_pincode){

  try {
      $fetch_update_profile_user= "UPDATE `cnz_customerregister` SET `customer_name` = '$customer_name', `customer_mobileno` = ' $customer_mobileno', `customer_emailid` = '$customer_emailid', `customer_address` = '$customer_address', `customer_pincode` = '$customer_pincode' WHERE `cnz_customerregister`.`id` = $id";
      $query_stmt = $this->db->prepare($fetch_update_profile_user);
     // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
      $query_stmt->execute();
      //return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($query_stmt->rowCount()) :
        
      else :
          return false;
      endif;
  } catch (PDOException $e) {
      return null;
  }

 }
  




 
 /////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////

   //FETCH default description LIST by service id


   public function fetchDefaultDescByService($service_id)
   {

    //    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //        $data = $this->jwtDecodeData($matches[1]);

           if (
            //    isset($data['data']->user_id) &&
               $user = $this-> get_default_desc_by_service($service_id)
           ) :
               return [
                   "success" => 200,
                   "user" => $user
               ];
           else :
               return [
                   "success" => 0,
                //    "message" => $data['message'],
               ];
           endif;
    //    } else {
    //        return [
    //            "success" => 0,
    //            "message" => "Token not found in request"
    //        ];
    //    }
   }






  protected function get_default_desc_by_service($service_id){

   try {
       $fetch_default_desc_by_service= "SELECT `id`, `category_servicename`,`category_defaultdesc` FROM `cnz_categoryservicemaster` WHERE `id` = $service_id";
       $query_stmt = $this->db->prepare($fetch_default_desc_by_service);
      // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
       $query_stmt->execute();
       return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
       if ($query_stmt->rowCount()) :
         
       else :
           return false;
       endif;
   } catch (PDOException $e) {
       return null;
   }

  }
   




   ///////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////

   //RESET PASSWORD FROM USER ID


   public function resetPassword($user_id, $new_password)
   {

    //    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //        $data = $this->jwtDecodeData($matches[1]);


    if(strlen($new_password) < 8)
    {

        return [
            "success" => 400,
            "user" =>  'Your password must be at least 8 characters long!'
        ];
        
    } else {

        if (
            //    isset($data['data']->user_id) &&
               $user = $this-> reset_to_new_password($user_id, $new_password)
           ) : 
               return [
                   "success" => 200,
                   "user" => $user
               ];
           else :
               return [
                   "success" => 200,
                   "message" => "password updated",
               ];
           endif;
    }
         
    //    } else {
    //        return [
    //            "success" => 0,
    //            "message" => "Token not found in request"
    //        ];
    //    }
   }






  protected function reset_to_new_password($user_id, $new_password){

   try {
    
    
     $encrypt_password = password_hash($new_password, PASSWORD_DEFAULT);
    $insert_query = "UPDATE `cnz_customerregister` SET `customer_password` = '$encrypt_password' WHERE `cnz_customerregister`.`id` = $user_id";
       
    $query_stmt = $this->db->prepare($insert_query);
    
    $query_stmt->execute();


       return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
       if ($query_stmt->rowCount()) :
         
       else :
           return false;
       endif;
   } catch (PDOException $e) {
       return null;
   }

  }
   


/////////////////////////////////////////////////
//SEARCH ON DB 

public function Search($query)
{

    // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //     $data = $this->jwtDecodeData($matches[1]);

        if (
            // isset($data['data']->user_id) &&
            $user = $this-> search_on_db(trim($query))
        ) :
            return [
                "success" => 200,
                "user" => $user
            ];
        else :
            return [
                "success" => 0,
                // "message" => $data['message'],
            ];
        endif;
    // } else {
    //     return [
    //         "success" => 0,
    //         "message" => "Token not found in request"
    //     ];
    // }
}






protected function search_on_db($query){

try {
    $fetch_search_result = "SELECT `id`, `category_name`, `category_defaultdesc`,`category_servicename`,`category_defaultdesc` FROM `cnz_categoryservicemaster` WHERE ( category_name LIKE '%$query%' OR category_servicename LIKE '%$query%' OR category_defaultdesc LIKE '%$query%' )";
    $query_stmt = $this->db->prepare($fetch_search_result);
   // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $query_stmt->execute();
    return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($query_stmt->rowCount()) :
      
    else :
        return false;
    endif;
} catch (PDOException $e) {
    return null;
}

}


/////////////////
/////////

// GET ALL BOOKING LIST PHP


public function getAllBooking($user_id)
{

    // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //     $data = $this->jwtDecodeData($matches[1]);

        if (
            // isset($data['data']->user_id) &&
            $user = $this-> book_list_of_user(trim($user_id))
        ) :
            return [
                "success" => 200,
                "user" => $user
            ];
        else :
            return [
                "success" => 0,
                // "message" => $data['message'],
            ];
        endif;
    // } else {
    //     return [
    //         "success" => 0,
    //         "message" => "Token not found in request"
    //     ];
    // }
}





protected function book_list_of_user($user_id){

try {
    $fetch_result = "SELECT `id`, `book_status`, `bookservice_date`,`bookservice_qty`,`bookservice_defaultdescription` FROM `cnz_bookservice` WHERE `booked_user_id` = '$user_id'";
    $query_stmt = $this->db->prepare($fetch_result);
   // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
   
    $query_stmt->execute();
    return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($query_stmt->rowCount()) :
      
    else :
        return false;
    endif;
} catch (PDOException $e) {
    return null;
}

}


/////////



/////////////////
/////////

// GET ALL BOOKING LIST O ALL USER PHP


public function getAllBooking_of_all_user($order_status,$order_type,$date)
{

    // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //     $data = $this->jwtDecodeData($matches[1]);

        if (
            // isset($data['data']->user_id) &&
            $user = $this-> getAllBooking_of_all_user_($order_status,$order_type,$date)
        ) :
            return [
                "success" => 200,
                "user" => $user
            ];
        else :
            return [
                "success" => 0,
                // "message" => $data['message'],
            ];
        endif;
    // } else {
    //     return [
    //         "success" => 0,
    //         "message" => "Token not found in request"
    //     ];
    // }
}





protected function getAllBooking_of_all_user_($order_status,$order_type,$date){

try {
    
     $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `bookservice_date` = '$date' OR `book_status` = '$order_status' AND `bookservice_order_type` = '$order_type' ORDER BY bookservice_date DESC LIMIT 10";
   
/*   if ($order_status== "" ) {
    $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `bookservice_date` = '$date' AND `bookservice_order_type` = '$order_type') ORDER BY bookservice_date DESC";
    
} else if ($order_type == "") {
   
    $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `bookservice_date` = '$date' AND `book_status` = '$order_status' ORDER BY bookservice_date DESC LIMIT 10";
    
} else if ($date == "") {
    
   $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `book_status` = '$order_status' AND `bookservice_order_type` = '$order_type' ORDER BY bookservice_date DESC LIMIT 10";
   
} else {
    $fetch_result = "SELECT * FROM `cnz_bookservice` ORDER BY `cnz_bookservice`.`bookservice_date` DESC LIMIT 10";
    
}
*/


  /* 
    */
    
    
    
    $query_stmt = $this->db->prepare($fetch_result);
   // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
   
    $query_stmt->execute();
    return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($query_stmt->rowCount()) :
      
    else :
        return false;
    endif;
} catch (PDOException $e) {
    return null;
}

}






/////////////////
/////////

// UPDATE THE STATUS OF USER 


public function updateStatus($user_id, $value)
{

    // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //     $data = $this->jwtDecodeData($matches[1]);

        if (
            // isset($data['data']->user_id) &&
            $user = $this-> updateStatus_fun(trim($user_id), trim($value))
        ) :
            return [
                "success" => 200,
                "user" => $user
            ];
        else :
            return [
                "success" => 200,
                // "message" => $data['message'],
            ];
        endif;
    // } else {
    //     return [
    //         "success" => 0,
    //         "message" => "Token not found in request"
    //     ];
    // }
}





protected function updateStatus_fun($user_id, $value){

try {
    $fetch_result = "UPDATE `cnz_bookservice` SET `book_status` = '$value' WHERE `cnz_bookservice`.`id` = $user_id";
    $query_stmt = $this->db->prepare($fetch_result);
   // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
   
    $query_stmt->execute();
    return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($query_stmt->rowCount()) :
      
    else :
        return false;
    endif;
} catch (PDOException $e) {
    return null;
}

}




///////////////
///
// DATE FILTER






public function date_filter($filter_type, $date, $page, $per_page)
{

    // if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

    //     $data = $this->jwtDecodeData($matches[1]);

        if (
            // isset($data['data']->user_id) &&
            $user = $this-> date_filter_fun($filter_type, $date, $page, $per_page)
        ) :
            return [
                "success" => 200,
                "user" => $user
            ];
        else :
            return [
                "success" => 0,
                // "message" => $data['message'],
            ];
        endif;
    // } else {
    //     return [
    //         "success" => 0,
    //         "message" => "Token not found in request"
    //     ];
    // }
}






 protected function date_filter_fun($filter_type, $date, $page, $per_page){

        try {


	
/*	$page_no = 1;

    $total_records_per_page = 10;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;

	https://myapi.com/data?page=2&per_page=10 */




            // Set default values for pagination parameters
            $page = isset($page) ? $page : 1;
            $perPage = isset($per_page) ? $per_page : 20;

// Calculate the offset
            $offset = ($page - 1) * $perPage;

            switch ($filter_type) {
                case "user_date":

                    $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `booked_user_date` = '$date' ORDER BY bookservice_date DESC LIMIT $perPage OFFSET $offset";

                    break;
                case "order_date":
                    $fetch_result = "SELECT * FROM `cnz_bookservice` WHERE `bookservice_date` = '$date' ORDER BY bookservice_date DESC LIMIT $perPage OFFSET $offset";

                    break;

                default:
                    echo "Please select order type..";
            }

 $query_stmt = $this->db->prepare($fetch_result);
   // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
   
    $query_stmt->execute();
    return $query_stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($query_stmt->rowCount()) :
      
    else :
        return false;
    endif;
} catch (PDOException $e) {
    return null;
}

}


} // end function


