{
    "name": "CartModule",
    "alias": "cartmodule",
    "description": "",
    "keywords": [],
    "priority": 0,
    "providers": [
        "Modules\\CartModule\\Providers\\CartModuleServiceProvider"
    ],
    "aliases": {},
    "files": [],
    "requires": []
}
                                                                                              <?php

namespace Modules\CartModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CartModuleDatabaseSeeder extends Seeder
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
