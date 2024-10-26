<?php

namespace Palmo\repository\impl;

use Palmo\entitys\Loggable;
use Palmo\entitys\User;
use Palmo\entitys\Validatable;
use Palmo\repository\Repo;
use Palmo\repository\Repository;
use PDO;
use PDOException;


class UserRepo extends Repository implements Repo
{
    use Loggable, Validatable {
        Loggable::log insteadof Validatable;
        Validatable::log as validateLog;
    }

    public function getUserByEmail($email, $isLogin = false): User | null
    {
        try {

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->dbh->prepare("SELECT u.id, u.userName, u.password_hash, ur.ROLE FROM users AS u 
                                                LEFT JOIN user_roles AS ur
                                                ON ur.id = u.role_id
                                              WHERE u.email = ?");
            $stmt->execute([$email]);
            $userArray = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($userArray)) {
                return null;
            }
            $user = new User($userArray['userName'], $email);
            $user->setUserId($userArray['id']);
            $user->setIsAdmin($userArray['ROLE'] === 'Admin');
            $user->setPasswordHash($userArray['password_hash']);
            if ($isLogin) {
                $this->log("The user with email {$user->getEmail()}, was successfully login");
            }
            return $user;
        } catch (PDOException $e) {
            if ($isLogin) {
                $this->log("The user with email {$user->getEmail()}, can not login, cause " . $e->getMessage());
            } else {
                $this->log("The user with email {$user->getEmail()}, can not get data from DB, cause " . $e->getMessage());
            }
            echo "Error connecting to the database: " . $e->getMessage();
        }
        return null;
    }

    public function getUserById($id, $isLogin = false): User | null
    {
        try {

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->dbh->prepare("SELECT u.id, u.userName, u.password_hash, ur.ROLE, u.email FROM users AS u 
                                                LEFT JOIN user_roles AS ur
                                                ON ur.id = u.role_id
                                              WHERE u.id = ?");
            $stmt->execute([$id]);
            $userArray = $stmt->fetch(PDO::FETCH_ASSOC);

            $user = new User($userArray['userName'], $userArray['email']);
            $user->setUserId($userArray['id']);
            $user->setIsAdmin($userArray['ROLE'] === 'Admin');
            $user->setPasswordHash($userArray['password_hash']);
            $user->setRole($userArray['ROLE']);
            if ($isLogin) {
                $this->log("The user with email {$user->getEmail()}, was successfully login");
            }

            return $user;
        } catch (PDOException $e) {
            if ($isLogin) {
                $this->log("The user with id {$id}, can not login, cause " . $e->getMessage());
            } else {
                $this->log("The user with id {$id}, can not get data from DB, cause " . $e->getMessage());
            }
            echo "Error connecting to the database: " . $e->getMessage();
        }
        return null;
    }


    public function saveData($entity): string
    {
        try {
            $this-> validateLog("The user with email {$entity->getEmail()}, was successfully verified");
            $this->log("The user with email {$entity->getEmail()}, was successfully created in DB");
            if ($this->getUserByEmail($entity->getEmail()) != null) {
                return 'The email already exists.';
            }

            $stmt = $this->dbh->prepare("INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, 1)");
            $hashedPassword = password_hash($entity->getpassword(), PASSWORD_DEFAULT);
            $stmt->execute([$entity->getUserName(), $entity->getEmail(), $hashedPassword]);
            return '';
        } catch (PDOException $e) {
            $this->log("The user with email {$entity->getEmail()}, can not save in DB, cause ".$e->getMessage());
            echo "Error: " . $e->getMessage();
        }
        return 'Is problem then save data';
    }

    public function updateUser($entity): void
    {
        try {

            $stmt = $this->dbh->prepare("UPDATE users 
                                                SET username = :username, email = :email, role_id = :role_id
                                                WHERE id = :userId");
            $role_id = $entity->getRole() === "User" ? 1 : 2;
            $stmt->execute(['username' => $entity->getUserName(), 'email' => $entity->getEmail(), 'role_id' => $role_id, 'userId' => $entity->getUserId()]);
        } catch (PDOException $e) {
            $this->log("The user with email {$entity->getEmail()}, can not update, cause ".$e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public function countUsers($searchQuery, $searchField, $filterRole): int
    {
        try {
            $query = "SELECT count(u.id) AS countRecords FROM users AS u 
                                                LEFT JOIN user_roles AS ur
                                                ON ur.id = u.role_id";
            $bindFilter = false;
            $bindSearchQuery= false;

            if ((empty($searchQuery) || empty($searchField)) && !empty($filterRole)) {
                $query .= " WHERE ur.ROLE = :role";
                $bindFilter = true;
            }
            if ((!empty($searchQuery) && !empty($searchField)) && empty($filterRole)) {
                $query .= " WHERE u.{$searchField} LIKE :searchQuery";
                $bindSearchQuery = true;
            }

            if ((!empty($searchQuery) && !empty($searchField)) && !empty($filterRole)) {
                $query .= " WHERE u.{$searchField} LIKE :searchQuery
                                                    AND ur.ROLE = :role";
                $bindFilter = true;
                $bindSearchQuery = true;
            }
            $stmt = $this->dbh->prepare($query);
            if ($bindFilter) {
                $stmt->bindValue(':role', $filterRole);
            }
            if ($bindSearchQuery) {
                $stmt->bindValue(':searchQuery', '%'.$searchQuery.'%');
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return 0;
    }

    public function getUsers($searchQuery, $searchField, $filterRole, $limit, $offset): array
    {
        $users = [];
        try {
            $query = "SELECT u.id, u.userName, ur.ROLE, u.email FROM users AS u 
                                                LEFT JOIN user_roles AS ur
                                                ON ur.id = u.role_id";
            $bindFilter = false;
            $bindSearchQuery= false;
            if ((empty($searchQuery) || empty($searchField)) && !empty($filterRole)) {
                $query .= " WHERE ur.ROLE = :role";
                $bindFilter = true;
            }
            if ((!empty($searchQuery) && !empty($searchField)) && empty($filterRole)) {
                $query .= " WHERE u.{$searchField} LIKE :searchQuery";
                $bindSearchQuery = true;
            }

            if ((!empty($searchQuery) && !empty($searchField)) && !empty($filterRole)) {
                $query .= " WHERE u.{$searchField} LIKE :searchQuery
                                    AND ur.ROLE = :role";
                $bindFilter = true;
                $bindSearchQuery = true;
            }
            $query .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->dbh->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if ($bindFilter) {
                $stmt->bindValue(':role', $filterRole);
            }
            if ($bindSearchQuery) {
                $stmt->bindValue(':searchQuery', '%'.$searchQuery.'%');
            }
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User($row['userName'], $row['email']);
                $user->setUserId($row['id']);
                $user->setRole($row['ROLE']);
                $users[] = $user;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $users;
    }


}