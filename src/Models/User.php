<?php


class User extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("user","user_id",$db);
    }

    public function login($user, $password)
    {
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            // Hash password
            $password = sha1(trim($password));

            $sql = 'SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1 ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':email' => $user,
                ':password' => $password,
            ]);

            $data = $stmt->fetch();

            if(!$data){
                $sql = 'SELECT * FROM user WHERE user_name = :user_name AND password = :password LIMIT 1';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                    ':user_name' => $user,
                    ':password' => $password,
                ]);

                if($stmt->rowCount() == 0){
                    throw new Exception("El usuario o contraseñas es icorrecta");
                }
            }

            $data = $stmt->fetch();
            if ($data['state'] == '0'){
                throw new Exception("Usted no esta autorizado para ingresar al sistema.");
            }

            // Session
            $sql = 'INSERT INTO user_session (user_id, session_date, description) 
                        VALUES (:user_id, :session_date, :description)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':session_date' => $currentDate,
                ':description' => 'in',
            ]);
            // if ($data['login_count'] < 20){
                $this->UpdateById((int)$data['user_id'],[
                   'login_count' => ((int)$data['login_count']) + 1,
                ]);
            // }

            $data['fa2_secret_enabled'] = !(strlen($data['fa2_secret']) === 0);
            $data['fa2_secret'] = '';

            $res->result = $data;
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Insert($user, $userId){
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO user (updated_at, created_at, created_user_id, updated_user_id, password, email,
                                        avatar, user_name, state, user_role_id)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :password, :email,
                                        :avatar, :user_name, :state, :user_role_id)";

            $stmt = $this->db->prepare($sql);

            if(!$stmt->execute([
                ":updated_at" => $currentDate,
                ":created_at" => $currentDate,
                ":created_user_id" => $userId,
                ":updated_user_id" => $userId,

                ":password" => sha1($user['password'] ?? '') ,
                ":email" => $user['email'] ?? '',
                ":avatar" => '',
                ":user_name" => $user['userName'] ?? '',
                ":state" => $user['state'] ?? false,

                ":user_role_id" => $user['userRoleId'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $lastInsertId = (int)$this->db->lastInsertId();

            $res->result = $lastInsertId;
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' [PDO]';
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetById(int $id) : Result {
        $res = new Result();
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at FROM user WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id"=>$id]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetByIdFa2(int $id) : Result {
        $res = new Result();
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at, fa2_secret FROM user WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id"=>$id]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetBy(string $columnName, $value) : Result {
        $res = new Result();
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at FROM user WHERE $columnName = :$columnName LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$columnName" => $value]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}
