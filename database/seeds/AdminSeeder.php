<?php

use App\MenuModel;
use App\RoleModel;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');
        DB::table('menus')->truncate();
        DB::table('menus')->insert([
            ['nama_menu' => 'Dashboard', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 1, 'slug' => 'dashboard', 'icon' => 'fas fa-warehouse', 'no_urut' => 1],
            ['nama_menu' => 'Nasabah', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 2, 'slug' => 'nasabah', 'icon' => 'fas fa-users', 'no_urut' => 2],
            ['nama_menu' => 'Administrator', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 3, 'slug' => 'administrator', 'icon' => 'fas fa-user-secret', 'no_urut' => 3],
            ['nama_menu' => 'Transaksi', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 4, 'slug' => 'transaksi', 'icon' => 'fas fa-exchange-alt', 'no_urut' => 4],
            ['nama_menu' => 'Angsuran', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 5, 'slug' => 'angsuran', 'icon' => 'fas fa-money-bill-wave', 'no_urut' => 5],
            ['nama_menu' => 'Master', 'parent_menu_id' => 0, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 8, 'slug' => 'master', 'icon' => 'fas fa-database', 'no_urut' => 8],

            ['nama_menu' => 'List Nasabah', 'parent_menu_id' => 2, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-nasabah', 'icon' => 'null', 'no_urut' => 1],
            ['nama_menu' => 'Tambah Nasabah', 'parent_menu_id' => 2, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-nasabah', 'icon' => 'null', 'no_urut' => 2],


            ['nama_menu' => 'List Kelompok', 'parent_menu_id' => 8, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-kelompok', 'icon' => 'null', 'no_urut' => 1],
            ['nama_menu' => 'Tambah Kelompok', 'parent_menu_id' => 8, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-kelompok', 'icon' => 'null', 'no_urut' => 2],

            ['nama_menu' => 'List Transaksi', 'parent_menu_id' => 4, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-transaksi', 'icon' => 'null', 'no_urut' => 1],
            ['nama_menu' => 'Tambah Transaksi', 'parent_menu_id' => 4, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-transaksi', 'icon' => 'null', 'no_urut' => 2],

            ['nama_menu' => 'List Angsuran', 'parent_menu_id' => 5, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-angsuran', 'icon' => 'null', 'no_urut' => 1],
            ['nama_menu' => 'Tambah Angsuran', 'parent_menu_id' => 5, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-angsuran', 'icon' => 'null', 'no_urut' => 2],
            ['nama_menu' => 'Scan QR-Code', 'parent_menu_id' => 5, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'scan-qr-code', 'icon' => 'null', 'no_urut' => 3],

            ['nama_menu' => 'List Margin Keuntungan', 'parent_menu_id' => 8, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-marginkeuntungan', 'icon' => 'null', 'no_urut' => 3],
            ['nama_menu' => 'Add Margin Keuntungan', 'parent_menu_id' => 8, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-marginkeuntungan', 'icon' => 'null', 'no_urut' => 4],

            ['nama_menu' => 'Tambah Admin', 'parent_menu_id' => 3, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'add-admin', 'icon' => 'null', 'no_urut' => 1],
            ['nama_menu' => 'List Admin', 'parent_menu_id' => 3, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'list-admin', 'icon' => 'null', 'no_urut' => 2],
            ['nama_menu' => 'Role Admin', 'parent_menu_id' => 3, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s'), 'menu_id' => 0, 'slug' => 'role-admin', 'icon' => 'null', 'no_urut' => 3],
        ]);
        date_default_timezone_set('Asia/Jakarta');
        // seeder departement
        DB::table('departements')->truncate();
        DB::table('departements')->insert(
            ['nama_departement' => 'ADMIN', 'kode_departement' => 'A001', 'urutan' => 1, 'deskripsi' => 'ADMIN', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
        );
        $data_departement = DB::table('departements')->get();
        DB::table('roles')->truncate();
        foreach ($data_departement as $i => $j) {
            DB::table('roles')->insert(
                ['id_departement' => $j->id, 'name' => 'ADMIN', 'status' => 1, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
            );
        }
        //seeder2
        DB::table('role_menus')->truncate();
        $data_array = MenuModel::all();
        $id_role    = RoleModel::select('id')->pluck('id');
        foreach ($data_array as $k => $v) {
            date_default_timezone_set('Asia/Jakarta');
            DB::table('role_menus')->insert(
                ['role_id' => $id_role[0], 'menu_id' => $v->id, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s')]
            );
        }
        //seeder3
        DB::table('admin')->truncate();
        $data_array = RoleModel::all();
        foreach ($data_array as $k => $v) {
            date_default_timezone_set('Asia/Jakarta');
            DB::table('admin')->insert(
                ['id_role' => $v->id, 'nama' => 'admin', 'no_hp' => '081320938989', 'email' => 'admin@gmail.com', 'password' => bcrypt('admin123'), 'confirm_password' => encrypt('admin123'), 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s')]
            );
        }
    }
}