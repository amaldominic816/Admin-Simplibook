{
    "name": "SMSModule",
    "alias": "smsmodule",
    "description": "",
    "keywords": [],
    "priority": 0,
    "providers": [
        "Modules\\SMSModule\\Providers\\SMSModuleServiceProvider"
    ],
    "aliases": {},
    "files": [],
    "requires": []
}
                                                                                                <?php

namespace Modules\SMSModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SMSModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
