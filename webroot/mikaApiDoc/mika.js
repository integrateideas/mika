/*************************************Social Login******************************************************************

/**
    * @api {POST} /api/users/socialLogin Social Login
    * @apiDescription Social Login
    * @apiVersion 1.0.0
    * @apiName Social Login
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json"
    * @apiParam {String} uid                   Facebook identifier
    * @apiParam {String} displayName           Name
    * @apiParam {String} email                 Email address
    * @apiParam {String} password              Password
    * @apiParam {String} username              Username 
    * @apiParam {Integer} role_id              User's Role 


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
    *     "status": true,
    *     "data":
    *       {
            "expertSpecializations": [],
            "id": {
              "id": 13,
              "first_name": "kshitiz",
              "last_name": "sekhri",
              "email": "kshitizsekhri12@gmail.com",
              "phone": "12345678",
              "role_id": 3,
              "is_deleted": null,
              "created": "2017-10-04T13:38:16+00:00",
              "modified": "2017-10-04T13:38:16+00:00",
              "username": "kshitizsekhri12@gmail.com",
              "experts": [
                {
                  "id": 12,
                  "user_id": 13,
                  "user_salon_id": null,
                  "expert_specializations": []
                }
              ]
            },
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTI0MTQsImV4cGVydF9pZCI6MTJ9.zuqe8cw_2Eqha_7VJz0xbach3y-f_1CotYLW5_MONbc",
            "expires": "2018-01-30 06:06:54"
          }
    *   }
  *  }

 **/

 /*************************************Add User's Salon******************************************************************

/**
    * @api {POST} /api/userSalons/add Add User's Salon
    * @apiDescription Add Expert's Salon Details 
    * @apiVersion 1.0.0
    * @apiName Add User's Salon
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E" 
    * @apiParam {String} salon_name            Name of the Salon
    * @apiParam {String} location              Location of the Salon
    * @apiParam {String} zipcode               Zipcode of that area


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
          "userSalon": {
            "salon_name": "Weirdo",
            "location": "LA",
            "zipcode": "90115",
            "user_id": 13,
            "status": true,
            "created": "2017-10-06T12:45:58+00:00",
            "modified": "2017-10-06T12:45:58+00:00",
            "id": 2
          },
          "success": true
     *   }
  *  }
 **/

  /************************************* Edit User's Salon******************************************************************

/**
    * @api {POST} /api/userSalons/edit/1 Edit User's Salon
    * @apiDescription Edit Expert's Salon Details 
    * @apiVersion 1.0.0
    * @apiName Edit User's Salon
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {String} salon_name            Name of the Salon
    * @apiParam {String} location              Location of the Salon
    * @apiParam {String} zipcode               Zipcode of that area


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
          "userSalon": {
            "id": 1,
            "user_id": 13,
            "salon_name": "Stylist",
            "location": "New York",
            "status": true,
            "is_deleted": null,
            "created": "2017-10-03T12:46:22+00:00",
            "modified": "2017-10-06T12:52:45+00:00",
            "zipcode": "92010"
          }
  *   }
  *  }
 **/

   /*************************************Add Expert Availabilities******************************************************************

/**
    * @api {POST} /api/ExpertAvailabilities/add Add Expert Availabilities
    * @apiDescription Add Expert Availabilities Details 
    * @apiVersion 1.0.0
    * @apiName Add Expert Availabilities
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Datetime} available_from            Expert Available-from 
    * @apiParam {Datetime} available_to              Expert Available-to
    * @apiParam {Boolean}  overlapping_allowed       Expert is avaialble for some other user in this time-slot


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "expertAvailabilities":
           {
               "available_from":
               {
                   "date": "2017-10-11 07:00:00.000000",
                   "timezone_type": 3,
                   "timezone": "UTC"
               },
               "available_to":
               {
                   "date": "2017-10-11 08:00:00.000000",
                   "timezone_type": 3,
                   "timezone": "UTC"
               },
               "overlapping_allowed": true,
               "expert_id": 12,
               "status": true,
               "created": "2017-10-10T07:27:35+00:00",
               "modified": "2017-10-10T07:27:35+00:00",
               "id": 3
           },
           "success": true
 *   }
*  }
 **/

    /*************************************Edit Expert Availabilities******************************************************************

/**
    * @api {POST} /api/ExpertAvailabilities/edit/1 Edit Expert Availabilities
    * @apiDescription Edit Expert Availabilities Details 
    * @apiVersion 1.0.0
    * @apiName Edit Expert Availabilities
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Datetime} available_from            Expert Available-from 
    * @apiParam {Datetime} available_to              Expert Available-to
    * @apiParam {Boolean}  overlapping_allowed       Expert is avaialble for some other user in this time-slot


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
   "expertAvailabilities":
       {
               "id": 3,
               "expert_id": 12,
               "available_from":
               {
                   "date": "2017-10-11 09:00:00.000000",
                   "timezone_type": 3,
                   "timezone": "UTC"
               },
               "available_to":
               {
                   "date": "2017-10-11 10:00:00.000000",
                   "timezone_type": 3,
                   "timezone": "UTC"
               },
               "overlapping_allowed": false,
               "status": true,
               "created": "2017-10-10T07:27:35+00:00",
               "modified": "2017-10-10T07:32:17+00:00"
           },
           "success": true
  *   }
*  }
**/

    /*************************************View Expert Availabilities******************************************************************

/**
    * @api {GET} /api/ExpertAvailabilities/view/1 View Expert Availabilities
    * @apiDescription View Expert Availabilities Details 
    * @apiVersion 1.0.0
    * @apiName View Expert Availabilities
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
   "expertAvailabilities":
   
       {
           "id": 3,
           "expert_id": 12,
           "available_from": "2017-10-11T09:00:00+00:00",
           "available_to": "2017-10-11T10:00:00+00:00",
           "overlapping_allowed": false,
           "status": true,
           "created": "2017-10-10T07:27:35+00:00",
           "modified": "2017-10-10T07:32:17+00:00",
           "expert":
           {
               "id": 12,
               "user_id": 13,
               "user_salon_id": 8
           }
       }
 *   }
*  }
**/

    /*************************************Expert Availabilities******************************************************************

/**
    * @api {GET} /api/ExpertAvailabilities/index Expert Availabilities Index
    * @apiDescription Expert Availabilities Index Details 
    * @apiVersion 1.0.0
    * @apiName Expert Availabilities Index
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
   "expertAvailabilities":
   
        [
           {
               "id": 1,
               "expert_id": 1,
               "available_from": "2017-10-06T01:25:28+00:00",
               "available_to": "2017-10-06T02:05:28+00:00",
               "overlapping_allowed": true,
               "status": true,
               "created": "2017-10-06T07:58:27+00:00",
               "modified": "2017-10-06T07:58:27+00:00",
               "expert":
               {
                   "id": 1,
                   "user_id": 2,
                   "user_salon_id": null
               }
           },
           {
               "id": 2,
               "expert_id": 12,
               "available_from": "2017-10-07T01:25:28+00:00",
               "available_to": "2017-10-07T02:05:28+00:00",
               "overlapping_allowed": false,
               "status": true,
               "created": "2017-10-06T08:26:21+00:00",
               "modified": "2017-10-06T08:31:41+00:00",
               "expert":
               {
                   "id": 12,
                   "user_id": 13,
                   "user_salon_id": 8
               }
           },
           {
               "id": 3,
               "expert_id": 12,
               "available_from": "2017-10-11T09:00:00+00:00",
               "available_to": "2017-10-11T10:00:00+00:00",
               "overlapping_allowed": false,
               "status": true,
               "created": "2017-10-10T07:27:35+00:00",
               "modified": "2017-10-10T07:32:17+00:00",
               "expert":
               {
                   "id": 12,
                   "user_id": 13,
                   "user_salon_id": 8
               }
           }
       ]
 *   }
*  }
**/

    /*************************************Search User Salon******************************************************************

/**
    * @api {GET} /api/UserSalons/searchSalon Search User Salon
    * @apiDescription Search User Salon Details 
    * @apiVersion 1.0.0
    * @apiName Search User Salon
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "getSearchSalons":
           [
               {
                   "id": 1,
                   "user_id": 2,
                   "salon_name": "Stylist",
                   "location": "New York",
                   "status": true,
                   "is_deleted": null,
                   "created": "2017-10-03T12:46:22+00:00",
                   "modified": "2017-10-06T12:52:45+00:00",
                   "zipcode": "92010",
                   "user":
                   {
                       "id": 2,
                       "first_name": "kshitiz",
                       "last_name": "Sekhri",
                       "email": "kshitizsekhri@gmail.com",
                       "phone": "9876543234",
                       "role_id": 3,
                       "is_deleted": null,
                       "created": "2017-10-03T08:45:28+00:00",
                       "modified": "2017-10-03T08:45:28+00:00",
                       "username": "kshitizsekhri@gmail.com",
                       "experts":
                       [
                           {
                               "id": 1,
                               "user_id": 2,
                               "user_salon_id": null,
                               "expert_availabilities":
                               [
                                   {
                                       "id": 1,
                                       "expert_id": 1,
                                       "available_from": "2017-10-11T10:25:28+00:00",
                                       "available_to": "2017-10-11T16:25:28+00:00",
                                       "overlapping_allowed": true,
                                       "status": true,
                                       "created": "2017-10-06T07:58:27+00:00",
                                       "modified": "2017-10-06T07:58:27+00:00"
                                   }
                               ]
                           }
                       ]
                   },
                   "_matchingData":
                   {
                       "Users":
                       {
                           "id": 2,
                           "first_name": "kshitiz",
                           "last_name": "Sekhri",
                           "email": "kshitizsekhri@gmail.com",
                           "phone": "9876543234",
                           "role_id": 3,
                           "is_deleted": null,
                           "created": "2017-10-03T08:45:28+00:00",
                           "modified": "2017-10-03T08:45:28+00:00",
                           "username": "kshitizsekhri@gmail.com"
                       },
                       "Experts":
                       {
                           "id": 1,
                           "user_id": 2,
                           "user_salon_id": null
                       },
                       "ExpertAvailabilities":
                       {
                           "id": 1,
                           "expert_id": 1,
                           "available_from": "2017-10-11T10:25:28+00:00",
                           "available_to": "2017-10-11T16:25:28+00:00",
                           "overlapping_allowed": true,
                           "status": true,
                           "created": "2017-10-06T07:58:27+00:00",
                           "modified": "2017-10-06T07:58:27+00:00"
                       }
                   }
               },
               {
                   "id": 3,
                   "user_id": 13,
                   "salon_name": "Stylist",
                   "location": "New York",
                   "status": true,
                   "is_deleted": null,
                   "created": "2017-10-06T14:25:53+00:00",
                   "modified": "2017-10-06T14:25:53+00:00",
                   "zipcode": "92010",
                   "user":
                   {
                       "id": 13,
                       "first_name": "kshitiz",
                       "last_name": "sekhri",
                       "email": "kshitizsekhri12@gmail.com",
                       "phone": "12345678",
                       "role_id": 3,
                       "is_deleted": null,
                       "created": "2017-10-04T13:38:16+00:00",
                       "modified": "2017-10-04T13:38:16+00:00",
                       "username": "kshitizsekhri12@gmail.com",
                       "experts":
                       [
                           {
                               "id": 12,
                               "user_id": 13,
                               "user_salon_id": 8,
                               "expert_availabilities":
                               [
                                   {
                                       "id": 3,
                                       "expert_id": 12,
                                       "available_from": "2017-10-11T12:25:28+00:00",
                                       "available_to": "2017-10-11T15:25:28+00:00",
                                       "overlapping_allowed": true,
                                       "status": true,
                                       "created": "2017-10-10T07:27:35+00:00",
                                       "modified": "2017-10-10T13:21:35+00:00"
                                   }
                               ]
                           }
                       ]
                   },
                   "_matchingData":
                   {
                       "Users":
                       {
                           "id": 13,
                           "first_name": "kshitiz",
                           "last_name": "sekhri",
                           "email": "kshitizsekhri12@gmail.com",
                           "phone": "12345678",
                           "role_id": 3,
                           "is_deleted": null,
                           "created": "2017-10-04T13:38:16+00:00",
                           "modified": "2017-10-04T13:38:16+00:00",
                           "username": "kshitizsekhri12@gmail.com"
                       },
                       "Experts":
                       {
                           "id": 12,
                           "user_id": 13,
                           "user_salon_id": 8
                       },
                       "ExpertAvailabilities":
                       {
                           "id": 3,
                           "expert_id": 12,
                           "available_from": "2017-10-11T12:25:28+00:00",
                           "available_to": "2017-10-11T15:25:28+00:00",
                           "overlapping_allowed": true,
                           "status": true,
                           "created": "2017-10-10T07:27:35+00:00",
                           "modified": "2017-10-10T13:21:35+00:00"
                       }
                   }
               }
           ]
        }
 *   }
*  }
**/

   /*************************************Link User with Facebook******************************************************************

/**
    * @api {POST} /api/Users/linkUserWithFb Link User with Facebook
    * @apiDescription Link User with Facebook Details 
    * @apiVersion 1.0.0
    * @apiName Link User with Facebook
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {String} uid                         Facebook Identifier

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "updateUser":
           {
               "user_id": 13,
               "fb_identifier": "45678",
               "status": true,
               "created": "2017-10-11T07:22:27+00:00",
               "modified": "2017-10-11T07:22:27+00:00",
               "id": 6
           },
           "success": true
        }
    }
 **/

   /*************************************Add Appointment******************************************************************

/**
    * @api {POST} /api/Appointments/add Add Appointment
    * @apiDescription Add Appointment with Experts 
    * @apiVersion 1.0.0
    * @apiName Add Appointment
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Integer} expert_availability_id               Expert Availability Id
    * @apiParam {Integer} expert_specialization_service_id     Expert Specialization Service Id
    * @apiParam {Integer} expert_specialization_id             Expert Specialization Id

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "appointment":
           {
               "user_id": 13,
               "expert_id": 12,
               "expert_availability_id": 1,
               "expert_specialization_service_id": 1,
               "expert_specialization_id": 1,
               "id": 2
           },
           "success": true
*   }
*  }
 **/

   /*************************************Add Appointment Transactions******************************************************************

/**
    * @api {POST} /api/AppointmentTransactions/add Add Appointment Transactions
    * @apiDescription Add Appointment Transactions
    * @apiVersion 1.0.0
    * @apiName Add Appointment Transactions
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Integer} appointment_id               Appointment Id
    * @apiParam {Integer} transaction_amount           Transaction Amount for the appointment of expert
    * @apiParam {Integer} charge_id                    The Stripe ID for the prior charge
    * @apiParam {String} remark                        Details for the Appointment Transaction

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "appointmentTransaction":
           {
               "appointment_id": 1,
               "transaction_amount": 500,
               "charge_id": "555",
               "status": true,
               "remark": "haircut",
               "id": 1
           }
*   }
*  }
 **/

 /*************************************View Appointment******************************************************************

/**
    * @api {GET} /api/Appointments/view/1 View Appointment Index
    * @apiDescription View Appointment Details 
    * @apiVersion 1.0.0
    * @apiName View Appointment Index
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "appointment":
           {
               "id": 1,
               "user_id": 13,
               "expert_id": 12,
               "expert_availability_id": 1,
               "expert_specialization_service_id": 1,
               "expert_specialization_id": 1,
               "is_confirmed": null,
               "is_completed": null,
               "appointment_transactions":
               [
               ],
               "expert_specialization":
               {
                   "id": 1,
                   "expert_id": 1,
                   "specialization_id": 1,
                   "description": "qwerty",
                   "created": "2017-10-11T00:00:00+00:00",
                   "modified": "2017-10-11T00:00:00+00:00"
               },
               "expert_specialization_service":
               {
                   "id": 1,
                   "expert_id": 1,
                   "expert_specialization_id": 1,
                   "specialization_service_id": 1,
                   "price": "10",
                   "description": "qwerty",
                   "created": "2017-10-11T00:00:00+00:00",
                   "modified": "2017-10-11T00:00:00+00:00",
                   "duration": 2
               },
               "expert_availability":
               {
                   "id": 1,
                   "expert_id": 1,
                   "available_from": "2017-10-11T10:25:28+00:00",
                   "available_to": "2017-10-11T16:25:28+00:00",
                   "overlapping_allowed": true,
                   "status": true,
                   "created": "2017-10-06T07:58:27+00:00",
                   "modified": "2017-10-06T07:58:27+00:00"
               },
               "expert":
               {
                   "id": 12,
                   "user_id": 13,
                   "user_salon_id": 8
               },
               "user":
               {
                   "id": 13,
                   "first_name": "kshitiz",
                   "last_name": "sekhri",
                   "email": "kshitizsekhri12@gmail.com",
                   "phone": "12345678",
                   "role_id": 3,
                   "is_deleted": null,
                   "created": "2017-10-04T13:38:16+00:00",
                   "modified": "2017-10-04T13:38:16+00:00",
                   "username": "kshitizsekhri12@gmail.com"
               }
           }
*   }
*  }
**/

 /*************************************View Appointment Transaction******************************************************************

/**
    * @api {GET} /api/AppointmentTransactions/view/1 View Appointment Transaction
    * @apiDescription View Appointment Transaction Details 
    * @apiVersion 1.0.0
    * @apiName View Appointment Transaction
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"


 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "appointmentTransaction":
           {
               "id": 1,
               "appointment_id": 1,
               "transaction_amount": 500,
               "charge_id": "555",
               "status": true,
               "remark": "haircut",
               "appointment":
               {
                   "id": 1,
                   "user_id": 13,
                   "expert_id": 12,
                   "expert_availability_id": 1,
                   "expert_specialization_service_id": 1,
                   "expert_specialization_id": 1,
                   "is_confirmed": null,
                   "is_completed": null
               }
           }
*   }
*  }
**/

   /*************************************Add Expert Specializations******************************************************************

/**
    * @api {POST} /api/ExpertSpecializations/add Add Expert Specializations
    * @apiDescription Add Expert Specializations
    * @apiVersion 1.0.0
    * @apiName Add Expert Specializations
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Integer} specialization_id            Specialization Id

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *  {
           "expertSpecialization":
           {
               "specialization_id": 2,
               "expert_id": 1,
               "created": "2017-10-13T08:15:42+00:00",
               "modified": "2017-10-13T08:15:42+00:00",
               "id": 3
           },
           "success": true
*   }
*  }
 **/

    /*************************************Delete Expert Specializations******************************************************************

/**
    * @api {DELETE} /api/ExpertSpecializations/delete/1 Delete Expert Specializations
    * @apiDescription Delete Expert Specializations
    * @apiVersion 1.0.0
    * @apiName Delete Expert Specializations
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Integer} specialization_id            Specialization Id

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "success": true
*   }
*  }
 **/

     /*************************************Delete Expert Specializations Services******************************************************************

/**
    * @api {DELETE} /api/ExpertSpecializationServices/delete/1 Delete Expert Specializations Services
    * @apiDescription Delete Expert Specializations Services
    * @apiVersion 1.0.0
    * @apiName Delete Expert Specializations Services
    * @apiGroup Mika API
    * @apiHeader {String} Accept-Type               This is the content types that are acceptable for the response.
    * @apiHeader {String} Content-Type              This is the MIME type of the body of the request (used with POST and PUT requests)
    * @apiHeader {String} Authorization             User's Access Token
    * @apiHeaderExample {php} Header-Example:
    *       "Accept"=> "application/json"
    *       "Content-Type"=> "application/json" 
    *       "Authorization "=> "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEzLCJleHAiOjE1MTcyOTAwMDUsImV4cGVydF9pZCI6MTJ9.Cw-f3dKt0uUVljCkO7-si_2pRCQDzmj3D5rz2NHXB1E"
    * @apiParam {Integer} specialization_id            Specialization Id

 * @apiSuccess {Boolean} status response of the Api.
    * @apiSuccess {Array} data contains response.
    * @apiSuccessExample Success-Response:
    * HTTP/1.1 200 OK
    * {
    *   "response":
    *   {
           "success": true
*   }
*  }
 **/