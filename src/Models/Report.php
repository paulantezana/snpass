<?php


class Report
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function PaginateAudit($page = 1, $limit = 10, $filter = []) : Result {
        $res = new Result();
        try{
            $offset = ($page - 1) * $limit;

            // Total pages
            $totalRows = $this->db->query('SELECT COUNT(*) FROM pass_password_audit')->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            // Query by pages
            $sql = "SELECT pass.*, user.user_name as user_name, pp.title as pass_title, pf.name as customer FROM pass_password_audit as pass
                        INNER JOIN user ON pass.user_refer_id = user.user_id
                        INNER JOIN pass_password pp on pass.pass_password_id = pp.pass_password_id
                        INNER JOIN pass_folder pf on pp.pass_folder_id = pf.pass_folder_id";

            $filterNumber = 0;
            if ($filter['folderId'] ?? false){
                $sql .= " WHERE pp.pass_folder_id = {$filter['folderId']}";
                $filterNumber++;
            }
            if ($filter['userId'] ?? false){
                $sql .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sql .= "pass.user_refer_id = {$filter['userId']}";
                $filterNumber++;
            }
            if ($filter['from'] ?? false){
                $sql .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sql .= "pass.create_at >= '{$filter['from']}'";
                $filterNumber++;
            }
            if ($filter['to'] ?? false){
                $sql .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sql .= "pass.create_at <= '{$filter['to']}'";
            }

//            $sql .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
            $sql .= " LIMIT $offset, $limit";

            $stmt = $this->db->prepare($sql);

            // Execute
            $stmt->execute();
            $data = $stmt->fetchAll();

            $res->result = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetSessionByMonth(){
        $res = new Result();
        try{
            $sql = 'SELECT DAY(user_session.session_date) as day, count(*) as count FROM user_session
                    INNER JOIN user u on user_session.user_id = u.user_id
                    GROUP BY MONTH(user_session.session_date)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetTotal(){
        $res = new Result();
        try{
            $sql = 'SELECT count(*) as count FROM user';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetch()['count'];

            $sql = 'SELECT count(*) as count FROM pass_folder';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $customer = $stmt->fetch()['count'];

            $sql = 'SELECT count(*) as count FROM user_session';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $userSession = $stmt->fetch()['count'];

            $res->result = [
                'user' => $user,
                'customer' => $customer,
                'session' => $userSession,
            ];
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function TopUserCopy(){
        $res = new Result();
        try{
            $sql = 'SELECT u.user_name, u.avatar, count(*) as count FROM  pass_password_audit ppa
                         INNER JOIN user u on ppa.user_refer_id = u.user_id
                    GROUP BY u.user_id LIMIT 10';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function TopCustomerCopy(){
        $res = new Result();
        try{
            $sql = 'SELECT pf.name, count(*) as count FROM pass_password_audit ppa
                    INNER JOIN pass_password pp on ppa.pass_password_id = pp.pass_password_id
                    INNER JOIN pass_folder pf on pp.pass_folder_id = pf.pass_folder_id
                    GROUP BY pf.name LIMIT 10';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}