{
    "name": "nwidart/businesssettingsmodule",
    "description": "",
    "authors": [
        {
            "name": "Nicolas Widart",
            "email": "n.widart@gmail.com"
        }
    ],
    "extra": {
        "laravel": {
            "providers": [],
            "aliases": {

            }
        }
    },
    "autoload": {
        "psr-4": {
            "Modules\\BusinessSettingsModule\\": ""
        }
    }
}
                                                                                        <?php

namespace Modules\BusinessSettingsModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminateic function newFactory()
    {
        return \Modules\BusinessSettingsModule\Database\factories\BusinessSettingsFactory::new();
    }
}
