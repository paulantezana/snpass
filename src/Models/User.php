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

            if($stmt->rowCount() == 0){
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
            if ($data['user_id'] < 2){
                $this->UpdateById((int)$data['user_id'],[
                   'login_count' => ((int)$data['login_count']) + 1,
                ]);
            }

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
}
