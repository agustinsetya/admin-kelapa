<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRolesModel extends Model
{
    protected $table         = 'm_role';
    protected $primaryKey    = 'm_role_id';
    protected $returnType    = 'object';
    protected $allowedFields = ['nama','role_scope','created_by','updated_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataUserRoles(array $filters = []): array
    {
        $role = $this->select('
                    m_role.m_role_id,
                    m_role.nama,
                    m_role.role_scope
                ');

        if (isset($filters['m_role_id']) && is_numeric($filters['m_role_id'])) {
            $role->where('m_role.m_role_id', (int)$filters['m_role_id']);
        }
        
		return $role->findAll();
    }

    public function saveDataUserRoles(array $data, $userRolesId = null): bool
    {
        $data['m_role_id'] = $userRolesId;

        $this->db->transStart();

        $exists = $this->where('m_role_id', $userRolesId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($userRolesId, $data);
        } else {
            $ok = $this->insert($data, false) !== false;
        }

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}