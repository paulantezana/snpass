<?php


class UserRole extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("user_role","user_role_id", $connection);
    }
    public function Insert($userRole, $userId){
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO user_role (name)
                    VALUES (:name)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":name" => $userRole['name'],
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