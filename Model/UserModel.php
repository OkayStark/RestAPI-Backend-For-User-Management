<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
    public function getUsers($limit)
    {
        $query = "SELECT * FROM users ORDER BY user_id ASC LIMIT ?";
        $params = ["i", $limit];

        return $this->select($query, $params);
    }

    public function addUser($userData)
    {
        $query = "INSERT INTO users (user_id, username, user_email, user_status) VALUES (?, ?, ?, ?)";
        $params = ["isii", $userData['user_id'], $userData['username'], $userData['user_email'], $userData['user_status']];

        return $this->insert($query, $params);
    }

    public function removeUser($user_id)
    {
        $query = "DELETE FROM users WHERE user_id = ?";
        $params = ["i", $user_id];

        return $this->delete($query, $params);
    }
    
    public function viewUser($user_id)
    {
        try {
            $query = "SELECT * FROM users WHERE user_id = ?";
            $params = ["i", $user_id];

            return $this->select($query, $params);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function searchUsers($searchParams)
    {
        try {
            $query = "SELECT * FROM users WHERE ";
            $params = [];

            foreach ($searchParams as $key => $value) {
                $query .= "$key LIKE ? AND ";
                $params[] = "s";
                $params[] = "%$value%";
            }

            // Remove the trailing "AND" from the query
            $query = rtrim($query, "AND ");

            return $this->select($query, $params);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
