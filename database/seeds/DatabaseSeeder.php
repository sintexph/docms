<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('migrate:fresh');

        factory(App\User::class,10)->create();

        //$this->call(UsersTableSeeder::class);

        App\System::create([
            'code'=>'AM',
            'name'=>'Administration Management System',
            'created_by'=>'Administrator'
        ]);
        App\System::create([
            'code'=>'HM',
            'name'=>'Human Resource Management System',
            'created_by'=>'Administrator'
        ]);
        App\System::create([
            'code'=>'Q',
            'name'=>'Quality Management System',
            'created_by'=>'Administrator'
        ]);
        App\System::create([
            'code'=>'P',
            'name'=>'Production Management System',
            'created_by'=>'Administrator'
        ]);
        App\System::create([
            'code'=>'FM',
            'name'=>'Finance Management System',
            'created_by'=>'Administrator'
        ]);
        App\System::create([
            'code'=>'IC',
            'name'=>'Internal Control System',
            'created_by'=>'Administrator'
        ]);


        App\Section::create(['code'=>'DO','system_code'=>'AM','name'=>'Documentation','created_by'=>'Administrator']);
        App\Section::create(['code'=>'WE','system_code'=>'AM','name'=>'Working Environment','created_by'=>'Administrator']);
        App\Section::create(['code'=>'VE','system_code'=>'AM','name'=>'Vehicle','created_by'=>'Administrator']);
        App\Section::create(['code'=>'BU','system_code'=>'AM','name'=>'Budget','created_by'=>'Administrator']);
        App\Section::create(['code'=>'RE','system_code'=>'HM','name'=>'Recruitment','created_by'=>'Administrator']);
        App\Section::create(['code'=>'TR','system_code'=>'HM','name'=>'Training','created_by'=>'Administrator']);
        App\Section::create(['code'=>'AT','system_code'=>'HM','name'=>'Attendance','created_by'=>'Administrator']);
        App\Section::create(['code'=>'IR','system_code'=>'HM','name'=>'IR (grievance, Disciplinary)','created_by'=>'Administrator']);
        App\Section::create(['code'=>'SC','system_code'=>'HM','name'=>'Salary and compensation','created_by'=>'Administrator']);
        App\Section::create(['code'=>'DE','system_code'=>'HM','name'=>'Deployment','created_by'=>'Administrator']);
        App\Section::create(['code'=>'SE','system_code'=>'HM','name'=>'Separation','created_by'=>'Administrator']);

        App\Section::create(['code'=>'AC','system_code'=>'FM','name'=>'Accounting','created_by'=>'Administrator']);
        App\Section::create(['code'=>'PA','system_code'=>'FM','name'=>'Payroll','created_by'=>'Administrator']);
        App\Section::create(['code'=>'PU','system_code'=>'FM','name'=>'Purchasing','created_by'=>'Administrator']);
        App\Section::create(['code'=>'SH','system_code'=>'FM','name'=>'Shipping','created_by'=>'Administrator']);

        App\Section::create(['code'=>'SA','system_code'=>'IC','name'=>'Sale','created_by'=>'Administrator']);
        App\Section::create(['code'=>'PC','system_code'=>'IC','name'=>'Purchase','created_by'=>'Administrator']);
        App\Section::create(['code'=>'PR','system_code'=>'IC','name'=>'Production','created_by'=>'Administrator']);
        App\Section::create(['code'=>'PAIC','system_code'=>'IC','name'=>'Payroll','created_by'=>'Administrator']);
        App\Section::create(['code'=>'FI','system_code'=>'IC','name'=>'Finance','created_by'=>'Administrator']);
        App\Section::create(['code'=>'FA','system_code'=>'IC','name'=>'Fixed Asset','created_by'=>'Administrator']);
        
        App\Category::create(['code'=>'PO','name'=>'Policy','created_by'=>'Administrator']);
        App\Category::create(['code'=>'PR','name'=>'Procedure','created_by'=>'Administrator']);
        App\Category::create(['code'=>'ME','name'=>'Memorandum','created_by'=>'Administrator']);

        //factory(App\Section::class,10)->create();
        //factory(App\Category::class,10)->create();
    }
}
