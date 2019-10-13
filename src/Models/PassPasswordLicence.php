<?php


class PassPasswordLicence extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("pass_password_licence","pass_password_licence_id", $connection);
    }
}