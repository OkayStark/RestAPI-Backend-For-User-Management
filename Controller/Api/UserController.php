<?php

class UserController extends BaseController
{
    public function AddAction()
    {
        $strErrorDesc = '';
        $responseData = null;

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                // Retrieve user data from URL parameters
                $requestData = [
                    'user_id' => isset($arrQueryStringParams['user_id']) ? $arrQueryStringParams['user_id'] : rand(1, 100),
                    'username' => isset($arrQueryStringParams['username']) ? $arrQueryStringParams['username'] : 'user_' . rand(1000, 9999),
                    'user_email' => isset($arrQueryStringParams['user_email']) ? $arrQueryStringParams['user_email'] : 'user' . rand(100, 999) . '@example.com',
                    'user_status' => isset($arrQueryStringParams['user_status']) ? $arrQueryStringParams['user_status'] : rand(0, 1),
                ];

                // Assuming you have a method in UserModel to add a user
                $userModel->addUser($requestData);

                $responseData = json_encode(['message' => 'User added successfully']);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    public function listAction()
    {
        $strErrorDesc = '';
        $responseData = null;

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                // Set default limit
                $intLimit = 10;

                // Check if custom limit is provided in the query parameters
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }

                // Get users from the UserModel
                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    public function RemoveAction()
    {
        $strErrorDesc = '';
        $responseData = null;

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                // Check if user_id is provided in the URL parameters
                if (isset($arrQueryStringParams['user_id'])) {
                    $user_id = $arrQueryStringParams['user_id'];

                    // Assuming you have a method in UserModel to remove a user
                    $deletedRows = $userModel->removeUser($user_id);

                    if ($deletedRows > 0) {
                        $responseData = json_encode(['message' => 'User removed successfully']);
                    } else {
                        throw new Exception('User not found or could not be removed');
                    }
                } else {
                    throw new Exception('Missing required parameter: user_id');
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    public function ViewAction()
    {
        $strErrorDesc = '';
        $responseData = null;

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                // Check if user_id is provided in the URL parameters
                if (isset($arrQueryStringParams['user_id'])) {
                    $user_id = $arrQueryStringParams['user_id'];

                    // Assuming you have a method in UserModel to view a user
                    $userData = $userModel->viewUser($user_id);

                    if ($userData) {
                        $responseData = json_encode($userData);
                    } else {
                        throw new Exception('User not found');
                    }
                } else {
                    throw new Exception('Missing required parameter: user_id');
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    public function SearchAction()
    {
        $strErrorDesc = '';
        $responseData = null;
    
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
    
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
    
                // Dynamically construct search parameters
                $searchParams = [];
                $allowedParams = ['user_id', 'username', 'user_email', 'user_status']; // Add more parameters as needed
    
                foreach ($allowedParams as $param) {
                    if (isset($arrQueryStringParams[$param])) {
                        $searchParams[$param] = $arrQueryStringParams[$param];
                    }
                }
    
                // Assuming you have a method in UserModel to search for users
                $searchResults = $userModel->searchUsers($searchParams);
                $responseData = json_encode($searchResults);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    
        // Send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }
    
}
