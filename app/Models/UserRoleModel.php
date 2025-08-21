<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table         = 'm_role';
    protected $primaryKey    = 'm_role_id';
    protected $returnType    = 'object';
    protected $allowedFields = ['nama','role_scope'];
    protected $useTimestamps = true;

    public function getDataUserRole()
    {
        $role = $this->select('
                    m_role.m_role_id,
                    m_role.nama,
                    m_role.role_scope
                ')
                ->findAll();
        
		return $role;
    }
}