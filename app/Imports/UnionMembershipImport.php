<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UnionMembership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnionMembershipImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        print_r($row);die();
        $user = new UnionMembership([
            "union_name" => $row['union_name'],
            "union_member_ID" => $row['union_member_ID'],
            "name" => $row['name'],
            "surname" => $row['surname'],
            "address" => $row['address'],
            "phone_number" => $row['phone_number'],
            "created_at" => date('Y-m-d H:i:s'),
        ]);

        // Delete Any Existing Role
        //DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
        // Assign Role To User
        //$user->assignRole($user->role_id);

        return $user;
    }
}
