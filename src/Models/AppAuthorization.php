<?php


class AppAuthorization extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("app_authorization","app_authorization_id", $connection);
    }
    public function GetMenu($userRoleId){
        $res = new Result();
        try{
            $sql = 'SELECT app.module, GROUP_CONCAT(app.description) FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId,
            ]);

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetAllByUserRoleId($userRoleId){
        $res = new Result();
        try{
            $sql = 'SELECT * FROM user_role_authorization WHERE user_role_id = :user_role_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId
            ]);

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Save($authIds, $userRoleId, $userId){
        $res = new Result();
        try{
            $this->db->beginTransaction();

            $sql = 'DELETE FROM user_role_authorization WHERE user_role_id = :user_role_id';
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
                ':user_role_id' => $userRoleId,
            ])){
                throw new Exception("No se pudo elimiar el registro");
            }

            foreach ($authIds as $row){
                $sql = 'INSERT INTO user_role_authorization (user_role_id, app_authorization_id) 
                        VALUES (:user_role_id, :app_authorization_id)';
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':user_role_id' => $userRoleId,
                    ':app_authorization_id' => $row,
                ])){
                    throw new Exception("No se pudo insertar el registro");
                }
            }

            $this->db->commit();
            $res->result = '';
            $res->success = true;
            $res->message = 'Se actualizarón ' . count($authIds) . ' Registros';
        } catch (Exception $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function IsAuthorized($module,$action,$userRoleId){
        $res = new Result();
        try{
            $sql = 'SELECT count(*) as count FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId,
                ':module' => $module,
                ':action' => $action,
            ]);

            if ($stmt->fetch()['count'] > 0){
                $res->result = $stmt->fetch();
                $res->success = true;
            }
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}