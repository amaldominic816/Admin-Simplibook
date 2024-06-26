{
    "name": "nwidart/transactionmodule",
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
            "Modules\\TransactionModule\\": ""
        }
    }
}
                                                                                                  <?php

namespace Modules\TransactionModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserManagement\Entit$this->belongsTo(User::class,'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\TransactionModule\Database\factories\LoyaltyPointTransactionFactory::new();
    }
}
