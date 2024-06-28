<!--

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class LockersLog extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         "locker_id",
//         "user_id",
//         "time_in",
//         "time_out"
//     ];

//     public function locker()
//     {
//         return $this->belongsTo(Locker::class);
//     }


//     public static function getLockersLogsWithUsers()
//     {
//         // Fetch LockersLog data with associated user information
//         $lockersLogsWithUsers = self::with('user')->get();

//         // Return the data
//         return $lockersLogsWithUsers;
//     }



//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }

// }


























//LOCKERHISTORY MODEL//


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerHistory extends Model
{

    use HasFactory;

    protected $table = 'lockers_history';


    protected $fillable = [
        "locker_id",
        "user_id",
        "time_in",
        "time_out",
        'action',
        'log'
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }


    public static function getLockersHistoryWithUsers()
    {
        // Fetch LockersLog data with associated user information
        $lockersHistoryWithUsers = self::with('user')->get();

        // Return the data
        return $lockersHistoryWithUsers;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
