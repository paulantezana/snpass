<?php


class PassPassword extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("pass_password","pass_password_id", $connection);
    }

    public function Insert($passCustomer, $userId){
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO pass_password (title, description, user_name, password, web_site, key_char, pass_customer_id)
                    VALUES (:title, :description, :user_name, :password, :web_site, :key_char, :pass_customer_id)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
//                ":updated_at" => $currentDate,
//                ":created_at" => $currentDate,
//                ":created_user_id" => $userId,
//                ":updated_user_id" => $userId,

                ":title" => $passCustomer['title'],
                ":description" => $passCustomer['description'],
                ":user_name" => $passCustomer['userName'],
                ":password" => $passCustomer['password'],
                ":web_site" => $passCustomer['webSite'],
                ":key_char" => $passCustomer['keyChar'],
                ":pass_customer_id" => $passCustomer['passCustomerId'],
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

    public function GetAllByCustomerId($passCustomerId){
        $res = new Result();
        try{
            $sql = 'SELECT * FROM pass_password where pass_customer_id = :pass_customer_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':pass_customer_id' => $passCustomerId,
            ]);

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function actionAudit($action,$userId){
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO pass_password_audit (user_refer_id, table_action, description, pass_password_id, create_at)
                    VALUES (:user_refer_id, :table_action, :description, :pass_password_id, :create_at)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":user_refer_id" => $userId,
                ":table_action" => $action['tableAction'],
                ":description" => '',
                ":pass_password_id" => $action['passPasswordId'],
                ":create_at" => $currentDate,
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
