{
    "name": "BidModule",
    "alias": "bidmodule",
    "description": "",
    "keywords": [],
    "priority": 0,
    "providers": [
        "Modules\\BidModule\\Providers\\BidModuleServiceProvider"
    ],
    "aliases": {},
    "files": [],
    "requires": []
}
                                                                                                <?php

namespace Modules\BidModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BidModuleDatabaseSeeder extends Seeder
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
